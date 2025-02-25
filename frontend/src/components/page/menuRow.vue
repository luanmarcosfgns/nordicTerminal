<script>
import UserService from "@/services/user.service";

export default {
  name: "menuRow",
  data() {
    return {
      user: null,
      userName: null,
      userId: null,
      closeConection:true
    }
  },
  methods: {
    async me() {
      let userService = await new UserService();
      this.user = await userService.me();
      this.userName = await this.user.data.name;
      this.userId = await this.user.data.id;

    },
    async voltarPage() {
      this.$router.replace('/connections/index')
    }
  },
  created() {
    this.me();
    if(location.pathname==='/connections/index'){
      this.closeConection = false;
    }
  }
}
</script>

<template>
  <nav class="navbar no-print navbar-top navbar-expand navbar-dashboard navbar-dark ps-0 pe-2 pb-0">
    <div class="container-fluid px-0 position-relative">
      <div class="d-flex justify-content-between w-100" id="navbarSupportedContent">
        <div class="d-flex align-items-center">

          <button v-if="closeConection" class="btn btn-light btn-sm " @click="voltarPage()" type="button">
            Fechar conexão
          </button>
        </div>
        <!-- Navbar links -->
        <ul class="navbar-nav align-items-center">

          <li class="nav-item dropdown ">
            <a class="nav-link dropdown-toggle pt-4 px-0" href="#" role="button" data-bs-toggle="dropdown"
               aria-expanded="false">
              <div class="media d-flex align-items-center">
                <i class="bi bi-three-dots-vertical"></i>

              </div>
            </a>
            <div class="dropdown-menu dashboard-dropdown dropdown-menu-end mt-2 py-1">
              <a class="dropdown-item d-flex align-items-center" :href="'/users/edit/' + userId +'?peril=1'">
                <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20"
                     xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z"
                        clip-rule="evenodd"></path>
                </svg>
                Meu Perfil
              </a>

              <div role="separator" class="dropdown-divider my-1"></div>
              <router-link class="dropdown-item d-flex align-items-center" to="/logout">
                <svg class="dropdown-icon text-danger me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                  </path>
                </svg>
                Sair
              </router-link>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<style scoped>
.dropdown-menu {
  position: fixed !important;
  margin-right: 30px !important;
  height: 100px !important;
}
</style>