<template>
  <input-form class-list="col-12" type="string" label="Nome " value="" name="nome"/>
  <div class="col-12 mt-2">
    <label for="comand">Comando</label>
    <textarea ref="comand" name="comand" id="comand"  class="text-area-code"></textarea>
  </div>


</template>

<script>
import InputForm from "@/components/form/inputForm.vue";



export default {
  name: "FormComands",
  components: {InputForm},
  data() {
    return {}
  },
  methods: {},

  mounted() {
  }
}
</script>

<style scoped>
@import "toastr/build/toastr.css";
.text-area-code{
  width: 100%;
  height: 300px;
  background: #1a232f;
  border: #57575e;
  border-radius: 5px;
  color: #FFFFFF;
  font-weight: bold;
  padding: 10px;
  margin-top: 10px;
}
</style>
