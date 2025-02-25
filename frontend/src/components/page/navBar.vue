<template>
  <nav class="navbar navbar-dark navbar-theme-primary bg-success px-4 col-12 d-lg-none no-print">
    <div class="d-flex align-items-center no-print">
      <button class="navbar-toggler d-lg-none collapsed" type="button" data-bs-toggle="collapse"
              data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
              aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
  </nav>

  <nav id="sidebarMenu" class="sidebar d-lg-block bg-success  text-white collapse no-print" data-simplebar>
    <div class="sidebar-inner px-4 pt-3 no-print">
      <div
          class="user-card d-flex d-md-none align-items-center justify-content-between justify-content-md-center pb-4 no-print">

        <div class="collapse-close d-md-none no-print">
          <a href="#sidebarMenu" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu"
             aria-expanded="true" aria-label="Toggle navigation">
            <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                    clip-rule="evenodd"></path>
            </svg>
          </a>
        </div>
      </div>
      <ul class="nav flex-column pt-3 pt-md-0">

        <li  class="nav-item">
          <div class="row">
            <div class="col-12">
              <div class="input-group">
                <input class="form-control" @change="pesquisaComands()" placeholder="pesquisar comandos">
                <button class="btn btn-primary">
                  <i class="bi bi-plus bi-white"></i>
                </button>
              </div>

            </div>
          </div>
        </li>

        <li @click="menu('dashboard')" :class="navItem === 'dashboard' ? 'active' : ''" class="nav-item">
          <a href="/painel" class="nav-link">
            <span class="sidebar-icon">
              <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
              </svg>
            </span>
            <span class="sidebar-text">Dashboard</span>
          </a>
        </li>

      </ul>
    </div>
  </nav>
</template>

<script>


export default {
  name: "navBar",
  data() {
    return {
      navItem: null
    }

  },
  methods: {
    menu(item) {
      this.navItem = item;
    }
  },
  mounted() {

  }
}
</script>

<style scoped>
body {
  padding: 2px;
}
.bi-white::before {
  color: white;
  font-weight: 800 !important;
}
</style>