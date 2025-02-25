<template>
  <div class="row">
    <div class="col-12 ps-4 pt-3 ">
      <div class="float-start">
        <h5>Novo comando</h5>
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
import ComandService from "@/services/comands.service";
import toastr from "toastr/build/toastr.min";

export default {
  name: "CreateComands",
  components: {FormComands},
  data() {
    return {
      connection_id: null
    }
  },
  methods: {
    async sendForm() {
      let dataForm = {
        connection_id: this.connection_id,
        nome: document.getElementById('nome').value,
        comand: document.getElementById('comand').value,


      }
      let tableService = new ComandService();
      let response = await tableService.store(dataForm);

      if (response.data?.id) {
        this.$parent.$parent.list();
        this.$parent.$parent.addFormModal = false;
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
    let hash = this.$route.params.hash;
    this.connection_id = atob(hash);
  }
}
</script>
<style scoped>
</style>
