<template>

  <div class="row">
    <div class="col-12 ps-4 pt-3 ">
      <div class="float-start">
        <h5>Editar Comands</h5>
      </div>
    </div>

  </div>
  <div class="row">
    <FormComands></FormComands>
    <div class="col-4">
      <button class="btn btn-primary mt-4" type="button" @click="sendForm">Salvar</button>
    </div>
  </div>


</template>

<script>
import FormComands from "@/views/comands/FormComands.vue";

import toastr from "toastr/build/toastr.min";
import ComandService from "@/services/comands.service";

export default {
  name: "EditComands",
  components: { FormComands},
  props: {
    idFormModal: String
  },
  methods: {
    async edit(id) {
      let tableService = new ComandService();
      let response = await tableService.view(id);

      document.getElementById('nome').value = response.data.nome;
      document.getElementById('comand').value = response.data.comand;

    },
    async sendForm() {
      let dataForm = {

        nome: document.getElementById('nome').value,
        comand: document.getElementById('comand').value,

        _method: 'PUT'

      }
      if (!dataForm.parent_id) {
        delete dataForm.parent_id
      }
      let id = this.idFormModal;
      let tableService = new ComandService();
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
