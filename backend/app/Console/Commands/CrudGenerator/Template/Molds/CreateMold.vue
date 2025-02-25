<template>
        <div class="row">
            <div class="col-12 ps-4 pt-3 ">
                <div class="float-start">
                    <h5>Adicionar :tableUppercase</h5>
                </div>
                <div class="float-end">
                    <button-widget cor="azul" href="./index" tamanho="M">
                        Voltar
                    </button-widget>
                </div>
            </div>

        </div>
        <div class="row">
            <Form:tableUppercase></Form:tableUppercase>
            <div class="col-4">
                <button class="btn btn-primary mt-4" type="button" @click="sendForm">Salvar</button>
            </div>
        </div>

</template>
<script>
import ButtonWidget from "@/components/widget/buttonWidget.vue";

import Form:tableUppercase from "@/views/:table/Form:tableUppercase.vue";
import :tableSingularService from "@/services/:tablePonto.service";
import toastr from "toastr/build/toastr.min";

export default {
    name: "Create:tableUppercase",
    components: {Form:tableUppercase, ButtonWidget},
    methods:{
        async sendForm(){
            let dataForm = {
        :dataFormSend

        }
            let tableService = new :tableSingularService();
            let response = await tableService.store(dataForm);

            if(response.data?.id){
                location.href = '/:table/index';
            }else{
                if (response.response.data?.message){
                    toastr.error(response.response.data?.message);
                }else{
                    toastr.error('Houve um problema ao inserir');
                }

            }
        }
    }
}
</script>
<style scoped>
</style>
