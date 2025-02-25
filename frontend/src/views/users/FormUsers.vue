<template>
  <input-form class-list="col-12" type="string" label="Nome " value="" name="nome"/>
  <input-form class-list="col-12" type="string" label="E-mail " value="" name="email"/>
  <input-form class-list="col-12" type="string" label="Senha  " value="" name="password"/>
  <input-form class-list="col-12" type="tinyint" label="Ativo " value="" name="ativo"/>
</template>

<script>
import InputForm from "@/components/form/inputForm.vue";


export default {
  name: "FormUsers",
  components: {InputForm},
  data() {
    return {
      displayInputUnidadeId: false
    }
  },
  methods: {},

  created() {
      if(location.pathname ==='/users/create'){
        this.displayInputUnidadeId = true
      }
  }
}
</script>

<style scoped>
@import "toastr/build/toastr.css";
</style>
