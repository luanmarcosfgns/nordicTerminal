create table anexos
(
    id                   bigint auto_increment
        primary key,
    tipo                 enum ('pessoas', 'movimentos', 'produtos', 'unidades', 'eventos') null,
    model_id             bigint                                                            null,
    nome                 varchar(255)                                                      null,
    anexo                longblob                                                          null,
    disponivel_ecommerce tinyint(1)                                                        null,
    descritivo           text                                                              null,
    created_user_id      bigint                                                            null,
    updated_at           timestamp                                                         null,
    created_at           timestamp                                                         null
);

create table categorias
(
    id              bigint auto_increment
        primary key,
    nome            varchar(255)                                                               not null,
    descritivo      text                                                                       null,
    tipo            enum ('unidades', 'cidades', 'users', 'pessoas', 'produtos', 'relatorios') not null,
    ativo           tinyint(1)                                                                 null,
    created_user_id bigint                                                                     null,
    updated_at      timestamp                                                                  null,
    created_at      timestamp                                                                  null
);

create table cidades
(
    id           bigint auto_increment
        primary key,
    codigo       varchar(9)   not null,
    nome         varchar(255) not null,
    uf           varchar(2)   not null,
    grupo_id     bigint       not null,
    categoria_id bigint       not null,
    updated_at   timestamp    null,
    created_at   timestamp    null
);

create table conta_movimentos
(
    id           bigint auto_increment
        primary key,
    conta_id     bigint    null,
    movimento_id bigint    null,
    created_at   timestamp null,
    updated_at   datetime  null
);

create table conta_pagamentos
(
    id             bigint auto_increment
        primary key,
    conta_id       bigint                                                                                                                   not null,
    valor          decimal                                                                                                                  null,
    tipo           enum ('dinheiro', 'cartao_credito', 'cartao_debito', 'transferencia_bancaria', 'boleto', 'pix', 'cheque')                null,
    vencimento     datetime                                                                                                                 null,
    payment_id     bigint                                                                                                                   null,
    payment_status enum ('new', 'pending', 'authorized', 'approved', 'in_process', 'in_mediation', 'cancelled', 'refunded', 'charged_back') null,
    updated_at     timestamp                                                                                                                null,
    created_at     timestamp                                                                                                                null
);

create table contas
(
    id              bigint auto_increment
        primary key,
    pessoa_id       bigint                                          null,
    descritivo      text                                            null,
    created_user_id bigint                                          null,
    unidade_id      bigint                                          null,
    tipo            enum ('receber', 'pagar')                       null,
    estado          enum ('novo', 'andamento', 'cancelado', 'pago') null,
    parcelas        int                                             null,
    valor           decimal                                         null,
    updated_at      timestamp                                       null,
    created_at      timestamp                                       null
);

create table ecommerce_produtos
(
    id           bigint auto_increment
        primary key,
    e_ativo      tinyint(1) null,
    produto_id   bigint     null,
    updated_at   timestamp  null,
    created_at   timestamp  null,
    deleted_at   timestamp  null,
    ecommerce_id bigint     null
);

create table ecommerces
(
    id         bigint auto_increment
        primary key,
    nome       varchar(255) null,
    descritivo text         null,
    e_ativo    tinyint(1)   null,
    updated_at timestamp    null,
    created_at timestamp    null,
    deleted_at timestamp    null
);

create table enderecos
(
    id              bigint auto_increment
        primary key,
    tipo            enum ('pessoas', 'unidades', 'users') null,
    model_id        bigint                                null,
    logradouro      varchar(255)                          null,
    numero          varchar(9)                            null,
    complemento     varchar(255)                          null,
    bairro          varchar(255)                          null,
    cidade_id       bigint                                null,
    descritivo      text                                  null,
    created_user_id bigint                                null,
    updated_at      timestamp                             null,
    created_at      timestamp                             null,
    deleted_at      timestamp                             null
);

create table grupos
(
    id              bigint auto_increment
        primary key,
    nome            varchar(255)                                                               not null,
    descritivo      text                                                                       null,
    tipo            enum ('unidades', 'cidades', 'users', 'pessoas', 'produtos', 'relatorios') not null,
    ativo           tinyint(1)                                                                 not null,
    created_user_id bigint                                                                     null,
    updated_at      timestamp                                                                  null,
    created_at      timestamp                                                                  null
);

create table movimentos
(
    id              bigint auto_increment
        primary key,
    descritivo      text                                                 null,
    model_id        bigint                                               null,
    created_user_id bigint                                               null,
    unidade_id      bigint                                               null,
    estado          enum ('novo', 'andamento', 'cancelado', 'concluido') null,
    produto_id      bigint                                               null,
    quantidade      bigint                                               null,
    valor           decimal                                              null,
    desconto        decimal                                              null,
    updated_at      timestamp                                            null,
    created_at      timestamp                                            null,
    tipo            enum ('saida', 'entrada')                            not null
);

create table pessoas
(
    id                  bigint auto_increment
        primary key,
    nome                varchar(255)                   not null,
    descritivo          text                           null,
    email               varchar(255)                   null,
    cnpj                varchar(14)                    not null,
    cpf                 varchar(11)                    not null,
    tipo                enum ('cliente', 'fornecedor') not null,
    telefone_principal  varchar(14)                    not null,
    telefone_secundario varchar(14)                    null,
    email_principal     varchar(255)                   not null,
    email_secundario    varchar(255)                   null,
    unidade_id          bigint                         not null,
    created_user_id     bigint                         not null,
    ativo               tinyint(1)                     not null,
    grupo_id            bigint                         null,
    categoria_id        bigint                         null,
    updated_at          timestamp                      null,
    created_at          timestamp                      null
);

create table produto_unidades
(
    id           bigint auto_increment
        primary key,
    quantidade   int      not null,
    produto_id   bigint   not null,
    unidade_id   bigint   not null,
    created_at   datetime null,
    movimento_id bigint   not null,
    updated_at   datetime null
);

create table produtos
(
    id              bigint auto_increment
        primary key,
    nome            varchar(255) not null,
    descritivo      text         null,
    created_user_id bigint       not null,
    ativo           tinyint(1)   not null,
    categoria_id    bigint       not null,
    grupo_id        bigint       not null,
    updated_at      timestamp    null,
    created_at      timestamp    null
);

create table relatorio_unidades
(
    id           bigint auto_increment
        primary key,
    unidade_id   bigint    null,
    updated_at   timestamp null,
    created_at   timestamp null,
    relatorio_id bigint    null
);

create table relatorios
(
    id         bigint auto_increment
        primary key,
    nome       varchar(255) null,
    `sql`      longtext     null,
    updated_at timestamp    null,
    created_at timestamp    null
);

create table tabela_precos
(
    id              bigint auto_increment
        primary key,
    nome            varchar(255)              null,
    descritivo      text                      null,
    created_user_id bigint                    null,
    ativo           tinyint(1)                null,
    updated_at      timestamp                 null,
    created_at      timestamp                 null,
    tipo            enum ('entrada', 'saida') not null,
    unidade_id      bigint                    not null
);

create table tabela_precos_produtos
(
    id              bigint auto_increment
        primary key,
    produto_id      bigint    null,
    tabela_preco_id bigint    null,
    valor_minimo    decimal   null,
    desconto_maximo decimal   null,
    updated_at      timestamp null,
    created_at      timestamp null,
    deleted_at      timestamp null
);

create table tabela_precos_unidades
(
    id              bigint auto_increment
        primary key,
    unidade_id      bigint    null,
    tabela_preco_id bigint    null,
    updated_at      timestamp null,
    created_at      timestamp null
);

create table unidades
(
    id                       bigint auto_increment
        primary key,
    nome                     varchar(255)                  not null,
    descritivo               text                          null,
    codigo_unidade           varchar(14)                   null,
    cnpj_unidade             varchar(14)                   not null,
    cpf_titular              varchar(11)                   not null,
    ie_unidade               varchar(11)                   null,
    rg_titular               varchar(11)                   null,
    telefone_principal       varchar(14)                   not null,
    telefone_secundario      varchar(14)                   null,
    email_principal          varchar(255)                  not null,
    email_secundario         varchar(255)                  null,
    tipo                     enum ('revendedor', 'gestor') not null,
    ativo                    tinyint(1)                    not null,
    categoria_id             bigint                        null,
    grupo_id                 bigint                        null,
    updated_at               timestamp                     null,
    created_at               timestamp                     null,
    mercadopago_access_token varchar(255)                  null,
    mercadopago_public_token varchar(255)                  null
);

create table users
(
    id         bigint auto_increment
        primary key,
    nome       varchar(255) null,
    descritivo text         null,
    email      varchar(255) null,
    password   varchar(255) null,
    unidade_id bigint       null,
    e_admin    tinyint(1)   null,
    e_venda    tinyint(1)   null,
    e_compra   tinyint(1)   null,
    ativo      tinyint(1)   null,
    updated_at timestamp    null,
    created_at timestamp    null
);

