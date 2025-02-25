<template>
  <layout-page-no-menu>
    <div class="row">
      <div class="col-md-8 col-12">
        <div class="card">
          <div class="card-header terminal-header text-center p-2">
            {{ labelTerminal }}
          </div>
          <div class="card-body terminal-body custom-scroll ">
            <div class="row">
              <div v-html="terminalOutputPermanente" class="col-12 lh-2 mt-2"
                   id="terminalOutputPermanente">

              </div>
              <div v-html="terminalOutputTemporario" class="col-12 lh-2 mt-2 "
                   id="terminalOutputTemporario">

              </div>
              <div class="col-12">
                <div class="d-flex align-items-center">
                  <span>$</span>
                  <input @keyup.enter="enviarComando()" autocomplete="off" type="text"
                         class="terminal-input text-start ms-2"
                         id="terminalInput">
                </div>

              </div>
            </div>
          </div>

        </div>
      </div>
      <div class="col-md-4 col-12">
        <index-comands></index-comands>
      </div>
    </div>

  </layout-page-no-menu>
</template>
<script>
import LayoutPageNoMenu from "@/components/page/layoutPageNoMenu.vue";
import IndexComands from "@/views/comands/IndexComands.vue";
import ConnectionsService from "@/services/connections.service";
import SshService from "@/services/ssh.service";
import {trim} from "core-js/internals/string-trim";


export default {
  name: "terminalConnections",
  components: {IndexComands, LayoutPageNoMenu},
  data() {
    return {
      labelTerminal: 'Carregando...',
      terminalOutputPermanente: '',
      terminalOutputTemporario: '',
      diretorioAtual: '',
    }
  },
  methods: {
    async labelConection() {
      let hash = this.$route.params.hash;
      let connection_id = atob(hash);
      let tableService = new ConnectionsService();
      let response = await tableService.view(connection_id);
      this.labelTerminal = response.data.host;
      this.labelTerminal += '@' + response.data.nome;
    },
    focusTerminal() {
      document.getElementById("terminalInput").focus()
    },
    copyText() {
      let elements = document.querySelectorAll('.copy-text');
      console.log(elements)
      for (let i = 0; i < elements.length; i++) {

        elements[i].addEventListener('click', (event) => {

          navigator.clipboard.writeText(event.target.dataset.copy)
              .then(() => {
                console.log("Texto copiado com sucesso!");
              })
              .catch(err => {
                console.error("Erro ao copiar texto: ", err);
              });
        });
      }

    },

    async enviarComando() {
      let comandoOriginal = document.getElementById("terminalInput").value;

      let commandsend = 'cd ' + this.diretorioAtual + "\n" + comandoOriginal;

      let parar = this.comandoInterno(comandoOriginal);

      if (parar) return false;

      this.blockTerminal()

      let response = await this.executeCommand(commandsend);

      let responseStatus = await this.checarStatus(response);

      while (responseStatus.data.status === 'running') {
        responseStatus = await this.checarStatus(response);
        this.sleep(3000);
      }

      if (responseStatus.data.status === 'waiting_confirmation') {
        let messagerRow = this.buildTemplate(comandoOriginal, responseStatus)
        this.displayTemporario(messagerRow);
        this.unblockTerminal()
        this.focusTerminal();
      }

      if (responseStatus.data.status === 'completed') {
        let messagerRow = this.buildTemplate(comandoOriginal, responseStatus)
        this.displayPermanente(messagerRow);
        this.diretorioAtual = this.retornarUltimaLinha(responseStatus.data.output)

      }


      if (responseStatus.data.status === 'not_found' || responseStatus.data.status === 'failed') {
        let errorRow = this.buildTemplateError(comandoOriginal, responseStatus)
        this.displayPermanente(errorRow);
      }


      this.copyText();
      this.unblockTerminal()
      this.focusTerminal();
    },
    blockTerminal() {
      document.getElementById("terminalInput").value = 'Carregando...';
      document.getElementById("terminalInput").disabled = true;
    },
    unblockTerminal() {
      document.getElementById("terminalInput").value = '';
      document.getElementById("terminalInput").disabled = false;
    },
    async executeCommand(command) {
      let hash = this.$route.params.hash;
      let connection_id = atob(hash);
      let sshService = new SshService();

      return await sshService.execute(connection_id, command, this.$route.query.i);

    },
    buildTemplateError(comand, response) {
      let output = '<div class="row border border-1 border-gray-200 rounded p-1 m-1">' +
          '<div class="col-12">' +
          '<strong >:comand</strong></br>' +
          '</div>' +
          '<div class="col-11">' +
          '<pre class="text-:type overflow-visible" >' +
          ':output' +
          '</pre>' +

          '</div>' +
          '<div class="col-1">' +
          '<i data-copy=":output" class="btn btn-light btn-xsm bi bi-clipboard-fill copy-text bi-xsm"></i>' +
          '</div>' +
          '</div>';
      let outputTerminal = response.data.output;
      outputTerminal = this.removeUltimaQuebraDeLinha(outputTerminal);
      outputTerminal = this.removeUltimaQuebraDeLinha(outputTerminal);

      output = output.replace(':comand', comand);
      output = output.replaceAll(':output', outputTerminal);
      output = output.replaceAll(':type', 'danger');
      return output;
    },
    buildTemplate(comand, response) {
      let output = '<div class="row border border-1 border-gray-200 rounded p-1 m-1">' +
          '<div class="col-12">' +
          '<strong >:comand</strong></br>' +
          '</div>' +
          '<div class="col-11">' +
          '<pre class="text-:type overflow-visible" >' +
          ':output' +
          '</pre>' +

          '</div>' +
          '<div class="col-1">' +
          '<i data-copy=":output" class="btn btn-light btn-xsm bi bi-clipboard-fill copy-text bi-xsm"></i>' +
          '</div>' +
          '</div>';
      let outputTerminal = response.data.output;
      outputTerminal = this.removeUltimaQuebraDeLinha(outputTerminal);
      outputTerminal = this.removeUltimaQuebraDeLinha(outputTerminal);
      output = output.replace(':comand', comand);
      output = output.replaceAll(':output', outputTerminal);
      output = output.replaceAll(':type', 'success');
      console.log(output)
      return output;
    },
    checarStatus(response) {
      if (response.data.success) {
        let execution_id = response.data.execution_id;
        let sshService = new SshService();
        return sshService.checkStatus(execution_id)
      }
    },
    displayPermanente(texto) {
      this.terminalOutputPermanente = this.terminalOutputPermanente + texto;
      this.terminalOutputTemporario = '';

    },
    displayTemporario(texto) {
      this.terminalOutputTemporario = texto
    },
    comandoInterno(command) {
      this.blockTerminal()
      command = trim(command);
      if (command === 'clear') {
        this.terminalOutputPermanente = '';
        this.unblockTerminal()
        this.focusTerminal()
        return true;
      }
      this.focusTerminal()
      this.unblockTerminal()
      return false;
    },
    sleep(ms) {
      return new Promise(resolve => setTimeout(resolve, ms));
    },
    generateUniqueId() {
      return Math.random().toString(36).substr(2, 9);
    },
    mountLink() {
      let terminal_id = this.$route.query.i;
      console.log(terminal_id !== undefined)
      if (!terminal_id) {
        this.$router.push({query: {i: this.generateUniqueId()}});
      }
    },
    removeUltimaQuebraDeLinha(texto) {
      // Verifica se a string termina com uma quebra de linha
      if (texto.endsWith('\n')) {
        // Remove a última quebra de linha
        return texto.slice(0, -1);
      }
      // Se não houver quebra de linha no final, retorna o texto original
      return texto;
    },
    retornarUltimaLinha(texto) {
      // Divide o texto em linhas usando a quebra de linha como delimitador
      const linhas = texto.split('\n');
      // Remove o último elemento se for uma string vazia (caso o texto termine com \n)
      if (linhas[linhas.length - 1] === '') {
        linhas.pop();
      }
      // Retorna a última linha
      return linhas[linhas.length - 1];
    },
    async initDiretorioAtual() {

      this.blockTerminal()
      let response = await this.executeCommand('pwd');
      let responseStatus = await this.checarStatus(response);
      while (responseStatus.data.status === 'running') {
        responseStatus = await this.checarStatus(response);
        this.sleep(3000);
      }
      this.diretorioAtual = await this.retornarUltimaLinha(responseStatus.data.output)
      this.unblockTerminal()

    }

  },
  mounted() {
    this.mountLink()
    this.initDiretorioAtual()
    this.labelConection();
    this.focusTerminal()


  },


}

</script>

<style scoped>
@import "toastr/build/toastr.css";
@import "bootstrap-icons/font/bootstrap-icons.min.css";

.bi-white::before {
  color: white;
  font-weight: 800 !important;
}

.terminal-body {
  height: 500px !important;
  background-color: #282c34;
  color: white;
  font-weight: bold;
}

.terminal-row {
  height: 500px !important;
  background-color: #313640 !important;
  color: white;
  font-weight: bold;
}

.terminal-header {

  background-color: #191a20;
  color: white;
  font-weight: bold;
}

.terminal-input {
  width: 100%;
  background-color: #282c34;
  color: white;
  border: none;
  outline: none;
}

.terminal-input:focus {
  width: 100%;
  background-color: #282c34;
  color: white;
  border: none;
  outline: none;
}

.terminal-input:focus-visible {
  width: 100%;
  background-color: #282c34;
  color: white;
  border: none;
  outline: none;
}

.terminal-input:visited {
  width: 100%;
  background-color: #282c34;
  color: white;
  border: none;
  outline: none;
}

.custom-scroll {
  overflow-y: scroll !important;
  scrollbar-gutter: stable !important;

}

/* Aplicar a personalização */
.custom-scroll::-webkit-scrollbar {
  width: 8px !important;
}

.custom-scroll::-webkit-scrollbar-thumb {
  background: linear-gradient(180deg, #ff6600, #cc5500) !important;
  border-radius: 4px !important;
}

.custom-scroll::-webkit-scrollbar-track {
  background: #1e1e1e !important;
}


.btn-xsm {
  --bs-btn-padding-x: 0.45rem;
  --bs-btn-padding-y: 0.45rem;

}

.bi-xsm::before {
  font-size: 8px !important;
}

</style>
