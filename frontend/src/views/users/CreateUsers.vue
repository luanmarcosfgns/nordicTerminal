<template>

    <div class="row">
      <div class="col-12 ps-4 pt-3 ">
        <div class="float-start">
          <h5>Adicionar Usuário</h5>
        </div>
      </div>

    </div>
    <div class="row">
      <FormUsers></FormUsers>
      <div class="col-4">
        <button class="btn btn-primary mt-4" type="button" @click="sendForm">Salvar</button>
      </div>
    </div>

</template>
<script>
import FormUsers from "@/views/users/FormUsers.vue";
import userService from "@/services/user.service";
import toastr from "toastr/build/toastr.min";

export default {
  name: "CreateUsers",
  components: {FormUsers},
  methods: {
    async sendForm() {

      let dataForm = {
        nome: document.getElementById('nome').value,
        email: document.getElementById('email').value,
        password: document.getElementById('password').value,

        ativo: document.getElementById('ativo').value,


      }
      let unidade_id =  document.getElementById('unidade_id');
      if(unidade_id!==null){
        dataForm.unidade_id = unidade_id.value;
      }
      let usersService = new userService();
      let response = await usersService.store(dataForm);

      if (response.data?.success) {
        location.href = '/users/index';
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
