<template>
  <div>
    <nav-bar></nav-bar>
    <main class="content">
      <menu-row></menu-row>
      <div class="row">
        <div class="col-12 p-2">

        </div>

      </div>
    </main>
  </div>

</template>
<script>
export default {
  name: "DashBoardPage",

  components: {},
  data() {
    return {
      numeroVendas: 0,
      numeroProdutos: 0,
      numeroPessoas: 0,
      rows: null,
      rowsEventos: null,
      page: 'panel'
    }
  },
  methods: {

  },
  mounted() {

  }
}


</script>

<style scoped>
@import "toastr/build/toastr.css";
@import "bootstrap-icons/font/bootstrap-icons.min.css";
</style>
<style scoped>
.bg-smoke {
  background-color: #f3f3f3;
}

.bi {
  color: white !important;
}
</style>

<script setup>

import NavBar from "@/components/page/navBar.vue";
import MenuRow from "@/components/page/menuRow.vue";

</script>