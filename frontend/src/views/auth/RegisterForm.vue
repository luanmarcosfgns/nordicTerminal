<script>
import UsersService from "@/services/user.service";
import toastr from "toastr/build/toastr.min";

export default {
  name: "RegisterForm",
  methods: {
    async salvar() {
      let payload = {};
      payload.email = document.getElementById('email').value;
      payload.password = document.getElementById('password').value;
      payload.nome = document.getElementById('nome').value;

      let userService = new UsersService();
      let response = await userService.store(payload);
      console.log(response)
      if(response?.data?.success){
        toastr.success('salvo com sucesso');
        localStorage.setItem('HASH', response.data.token);
        this.$router.replace('/connections/index');
        return;
      }
      toastr.error(response.response.data.message);
    }
  }
}
</script>

<template>
  <div class="h-100 background-gradient">
    <div class="row">
      <div class="col-12">
        <div class="row d-flex justify-content-center align-items-center h-100">
          <div class=" col-sm-10 col-md-4 col-lg-4 col-12 pt-4">
            <div class="card">
              <div class="card-header pt-4 ps-4 pb-4">
                <div class="row">
                  <div class="col-12 ps-5">
                    <img class="justify-content-center align-items-center" width="200" src="@/assets/logo.png">
                  </div>

                </div>


              </div>
              <div class="card-body card-body-login">
                <div class="mb-4 mt-2 ">
                  <label>Email</label>
                  <input type="text" class="form-control input-login" id="email" placeholder="Digite seu Usuário"
                  >
                </div>
                <div class="mb-4 ">
                  <label>Senha</label>
                  <input type="password" class="form-control input-login" placeholder="Digite seu Senha"
                         id="password">
                </div>
                <div class="mb-4 mt-2 ">
                  <label>Nome</label>
                  <input type="text" class="form-control input-login" id="nome" placeholder="Digite seu Nome"
                  >
                </div>


                <div class="row">
                  <div class="col-12 d-grid gap-2 ps-4 pe-4 pt-4">
                    <button @click="salvar" class="btn btn-lg btn-system btn-loading">
                      Salvar
                    </button>
                  </div>


                </div>
              </div>

            </div>

          </div>
        </div>

      </div>

    </div>

  </div>
</template>

<style scoped>
.background-gradient {
  width: 100% !important;
  height: 100% !important;
  position: fixed !important;
  background: linear-gradient(0deg, rgba(34, 193, 195, 1) 0%, rgba(253, 187, 45, 1) 100%) no-repeat !important;

}
</style>