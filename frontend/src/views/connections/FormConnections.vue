<template>
  <input-form class-list="col-12 col-md-6" type="string" label="Nome " value="" name="nome"/>
  <input-form class-list="col-12 col-md-4" type="string" label="Host" value="" name="host"/>
  <input-form class-list="col-12 col-md-2" type="bigint" label="Porta " value="" name="port"/>
  <input-form class-list="col-12 col-md-6" type="string" label="Usuário" value="" name="username"/>
  <input-form class-list="col-12 col-md-6" type="string" label="Senha" value="" name="password"/>



</template>

<script>
import InputForm from "@/components/form/inputForm.vue";


export default {
  name: "FormConnections",
  components: {InputForm},
  data() {
    return {}
  },
  methods: {},

  created() {
  }
}
</script>

<style scoped>
@import "toastr/build/toastr.css";
</style>
