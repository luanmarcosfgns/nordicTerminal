<template>
  <div class="row">
    <div class="col-12 ps-4 pt-3 ">
      <div class="float-start">
        <h5>Adicionar Conexões</h5>
      </div>
    </div>

  </div>
  <div class="row">
    <FormConnections></FormConnections>
    <div class="col-12">
      <button class="btn btn-primary mt-4" type="button" @click="sendForm">Salvar</button>
    </div>
  </div>

</template>
<script>

import FormConnections from "@/views/connections/FormConnections.vue";
import ConnectionService from "@/services/connections.service";
import toastr from "toastr/build/toastr.min";

export default {
  name: "CreateConnections",
  components: {FormConnections},
  methods: {
    async sendForm() {
      let dataForm = {
        nome: document.getElementById('nome').value,
        host: document.getElementById('host').value,
        port: document.getElementById('port').value,
        username: document.getElementById('username').value,
        password: document.getElementById('password').value,


      }
      let tableService = new ConnectionService();
      let response = await tableService.store(dataForm);

      if (response.data?.id) {
        location.href = '/connections/index';
      } else {
        if (response.response.data?.message) {
          toastr.error(response.response.data?.message);
        } else {
          toastr.error('Houve um problema ao inserir');
        }

      }
    }
  }
}
</script>
<style scoped>
</style>
