<script>

import axios from "axios";
export default {
  name: "GoogleLogin",
  async mounted() {
    const urlParams = new URLSearchParams(window.location.search);
    const code = urlParams.get('code');

    if (code) {
      try {
        const { data } = await axios.post(process.env.VUE_APP_API_HOST_NAME +'/api/auth/google/token', { code });
        console.log(data.data)
        localStorage.setItem('HASH', data.data.token);
        location.href = '/connections/index'

      } catch (error) {
        console.error('Erro durante a autenticação:', error);
      }
    }
  },
}
</script>

<template>
  <div>
    <p>Autenticando...</p>
  </div>
</template>

<style scoped>

</style>