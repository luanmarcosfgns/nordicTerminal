<template>

  <div class="row">
    <div class="col-12 ps-4 pt-3 ">
      <div class="float-start">
        <h5>Editar Conexões</h5>
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

import toastr from "toastr/build/toastr.min";
import ConnectionService from "@/services/connections.service";

export default {
  name: "EditConnections",
  components: { FormConnections},
  props: {
    idFormModal: String
  },
  methods: {
    async edit(id) {
      let tableService = new ConnectionService();
      let response = await tableService.view(id);
      document.getElementById('nome').value = response.data.nome;
      document.getElementById('host').value = response.data.host;
      document.getElementById('port').value = response.data.port;
      document.getElementById('username').value = response.data.username;


    },
    async sendForm() {
      let dataForm = {
        nome: document.getElementById('nome').value,
        host: document.getElementById('host').value,
        port: document.getElementById('port').value,
        username: document.getElementById('username').value,
        password: document.getElementById('password').value,
        _method: 'PUT'

      }
      if (!dataForm.parent_id) {
        delete dataForm.parent_id
      }
      let id = this.idFormModal;
      let tableService = new ConnectionService();
      let response = await tableService.update(dataForm, id);
      if (response.data?.id) {
        toastr.success('Salvo com sucesso')
      } else {
        if (response.response.data?.message) {
          toastr.error(response.response.data?.message);
        } else {
          toastr.error('Houve um problema ao inserir');
        }

      }
    }
  },
  mounted() {
    this.edit(this.idFormModal)
  }
}
</script>

<style scoped>

</style>
