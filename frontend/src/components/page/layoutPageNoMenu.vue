<template>
  <main id="mainContent" class="px-4">
    <menu-row></menu-row>
    <slot></slot>
  </main>

</template>

<script>


import UserService from "@/services/user.service";
import MenuRow from "@/components/page/menuRow.vue";


export default {
  name: "layoutPageNoMenu",
  components: {MenuRow},
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
