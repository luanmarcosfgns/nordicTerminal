#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <libssh/libssh.h>
#include <glib.h>
#include <getopt.h>

typedef struct {
    ssh_session session;
    int is_connected;
} SSHConnection;

GHashTable *connections;

void erro(const char *mensagem, ssh_session session) {
    if (session) fprintf(stderr, "Erro SSH: %s\n", ssh_get_error(session));
    else fprintf(stderr, "Erro: %s\n", mensagem);
    exit(1);
}

SSHConnection *criar_conexao(const char *id, const char *host, const char *user, const char *pass, int port) {
    ssh_session session = ssh_new();
    if (session == NULL) erro("Falha ao criar sessão SSH.", NULL);

    ssh_options_set(session, SSH_OPTIONS_HOST, host);
    ssh_options_set(session, SSH_OPTIONS_USER, user);
    ssh_options_set(session, SSH_OPTIONS_PORT, &port);

    if (ssh_connect(session) != SSH_OK) erro("Falha na conexão.", session);

    if (ssh_userauth_password(session, NULL, pass) != SSH_AUTH_SUCCESS) erro("Falha na autenticação.", session);

    SSHConnection *conn = malloc(sizeof(SSHConnection));
    conn->session = session;
    conn->is_connected = 1;
    
    g_hash_table_insert(connections, g_strdup(id), conn);
  

    return conn;
}

SSHConnection *buscar_conexao(const char *id) {
    return g_hash_table_lookup(connections, id);
}

void fechar_conexao(const char *id) {
    SSHConnection *conn = buscar_conexao(id);
    if (!conn) {
        printf("Conexão %s não encontrada.\n", id);
        return;
    }

    ssh_disconnect(conn->session);
    ssh_free(conn->session);
    g_hash_table_remove(connections, id);
    
}

void executar_comando(SSHConnection *conn, const char *comando) {
    if (!conn || !conn->is_connected) erro("Conexão inválida ou fechada.", NULL);

    ssh_channel channel = ssh_channel_new(conn->session);
    if (!channel) erro("Falha ao criar canal SSH.", conn->session);

    if (ssh_channel_open_session(channel) != SSH_OK) erro("Falha ao abrir sessão.", conn->session);
    
    if (ssh_channel_request_exec(channel, comando) != SSH_OK) erro("Falha ao executar comando.", conn->session);

    char buffer[256];
    int bytes;
    while ((bytes = ssh_channel_read(channel, buffer, sizeof(buffer), 0)) > 0) {
        fwrite(buffer, 1, bytes, stdout);
    }

    ssh_channel_close(channel);
    ssh_channel_free(channel);
}

int main(int argc, char *argv[]) {
    connections = g_hash_table_new_full(g_str_hash, g_str_equal, free, free);

    char *usuario = NULL, *host = NULL, *senha = NULL, *id = NULL, *comando = NULL;
    int porta = 22;
    int fechar = 0;

    int opt;
    while ((opt = getopt(argc, argv, "u:h:s:p:i:c:")) != -1) {
        switch (opt) {
            case 'u': usuario = optarg; break;
            case 'h': host = optarg; break;
            case 's': senha = optarg; break;
            case 'p': porta = atoi(optarg); break;
            case 'i': id = optarg; break;
            case 'c': comando = optarg; break;
            case '?': erro("Uso incorreto do programa!", NULL);
        }
    }

    if (id == NULL) erro("ID da conexão (-i) é obrigatório.", NULL);

    if (comando && strcmp(comando, "-ic") == 0) {
        fechar_conexao(id);
        return 0;
    }

    SSHConnection *conn = buscar_conexao(id);
    if (!conn && (usuario && host && senha)) {
        conn = criar_conexao(id, host, usuario, senha, porta);
    } else if (!conn) {
        erro("Conexão não encontrada e dados de autenticação ausentes.", NULL);
    }

    if (comando) executar_comando(conn, comando);

    return 0;
}

