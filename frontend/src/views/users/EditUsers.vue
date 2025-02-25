<template>

    <div class="row">
      <div class="col-12 ps-4 pt-3 ">
        <div class="float-start">
          <h5>Editar Usuário</h5>
        </div>
        <div v-if="perfil" class="float-end">
          <button-widget cor="azul" href="../index" tamanho="M">
            Voltar
          </button-widget>
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
import ButtonWidget from "@/components/widget/buttonWidget.vue";

import toastr from "toastr/build/toastr.min";
import userService from "@/services/user.service";

export default {
  name: "EditUsers",
  components: { ButtonWidget, FormUsers},
  props:{
    idFormModal:String
  },
  data() {
    return {
      permissions: null,
      perfil: 0
    }
  },
  methods: {
    async edit(id) {
      let usersService = new userService();
      let response = await usersService.view(id);
      document.getElementById('nome').value = response.data.nome;
      document.getElementById('email').value = response.data.email;
      document.getElementById('ativo').value = response.data.ativo;

    },
    async sendForm() {
      let dataForm = {
        nome: document.getElementById('nome').value,
        email: document.getElementById('email').value,
        password: document.getElementById('password').value,
        ativo: document.getElementById('ativo').value,


        _method: 'PUT'

      }
      if (!dataForm.parent_id) {
        delete dataForm.parent_id
      }
      let id = this.idFormModal;
      let usersService = new userService();
      let response = await usersService.update(dataForm, id);
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
  created() {
    this.edit(this.idFormModal);
    if (this.$route.query.perfil !== undefined) {
      this.perfil = this.$route.query.perfil
    }

  }
}
</script>

<style scoped>

</style>
