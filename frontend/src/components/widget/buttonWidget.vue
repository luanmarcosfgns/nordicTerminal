<template>
    <router-link :to="href" :class="classList">
        <slot></slot>
    </router-link>
</template>

<script>
export default {
    name: "buttonWidget",
    props: {
        href: String,
        cor: String,
        tamanho: String
    },
    data() {
        return {
            classList: "btn btn-primary"
        }
    },
    created() {

        if (this.cor == "azul") {
            this.classList = 'btn btn-primary';
        } else if (this.cor == "cinza") {
            this.classList = 'btn btn-secondary';
        } else if (this.cor == "verde") {
            this.classList = 'btn btn-success';
        } else if (this.cor == "vermelho") {
            this.classList = 'btn btn-secondary';
        } else if (this.cor == "amarelo") {
            this.classList = 'btn btn-danger';
        } else if (this.cor == "azul-marinho") {
            this.classList = 'btn btn-info';
        } else if (this.cor == "preto") {
            this.classList = 'btn btn-dark';
        } else if (this.cor == "branco") {
            this.classList = 'btn btn-light';
        }


        if (this.tamanho == "P") {
            this.classList += this.classList + ' btn-sm';
        } else if (this.cor == "M") {
            this.classList += this.classList + ' btn-md';
        } else if (this.cor == "G") {
            this.classList += this.classList + ' btn-lg';
        }
    }

}
</script>

<style scoped></style>