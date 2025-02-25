<template>
  <nav-bar></nav-bar>
  <main id="mainContent" class="content">
    <menu-row></menu-row>
    <slot></slot>
  </main>

</template>

<script>


import NavBar from "@/components/page/navBar.vue";
import UserService from "@/services/user.service";
import MenuRow from "@/components/page/menuRow.vue";


export default {
  name: "layoutPage",
  components: {MenuRow, NavBar},
  data() {
    return {
      countNotification: 0,
      notifications: 0,
      user: null,
      userName: ''
    }
  },
  methods: {
    async me() {
      let userService = await new UserService();
      this.user = await userService.me();
      this.userName = await this.user.data.name;

    },
  },
  mounted() {
    this.me()

  }
}
</script>

<style scoped>
body {
  padding: 2px;
}

nav {
  height: 100px;
}
</style>
