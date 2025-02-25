<template>

        <div class="row">
            <div class="col-12 ps-4 pt-3 ">
                <div class="float-start">
                    <h5>Editar :tableUppercase</h5>
                </div>
                <div class="float-end">
                    <button-widget cor="azul" href="../index" tamanho="M">
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
import Form:tableUppercase from "@/views/:table/Form:tableUppercase.vue";
import ButtonWidget from "@/components/widget/buttonWidget.vue";
import toastr from "toastr/build/toastr.min";
import :tableSingularService from "@/services/:tablePonto.service";

export default {
    name: "Edit:tableUppercase",
    components: { ButtonWidget, Form:tableUppercase},
    props:{
        idFormModal:String
    },
    methods:{
        async edit(id){
            let tableService = new :tableSingularService();
            let response = await tableService.view(id);
        :dataFormEdit
        },
        async sendForm(){
            let dataForm = {
        :dataFormSend
            _method:'PUT'

        }
            if(!dataForm.parent_id){
                delete dataForm.parent_id
            }
            let id = this.idFormModal;
            let tableService = new :tableSingularService();
            let response = await tableService.update(dataForm,id);
            if(response.data?.id){
                toastr.success('Salvo com sucesso')
            }else{
                if (response.response.data?.message){
                    toastr.error(response.response.data?.message);
                }else{
                    toastr.error('Houve um problema ao inserir');
                }

            }
        }
    },
    mounted() {
        this.edit(this.idFormModal)
    }
}
</script>

<style scoped>

</style>
