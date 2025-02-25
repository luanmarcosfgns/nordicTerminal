<template>
  <div class="row border border-1">
    <div class="col-12 ">
      <div class="row p-2">
        <div class="col-10">
          <input id="search" class="form-control" @change="list()" placeholder="Digite sua pesquisa"
                 type="text" v-model="search">
        </div>
        <div class="col-2">
          <button class="btn btn-primary" @click="openAddFormModal">
            <i class="bi bi-plus bi-white"></i>
          </button>
        </div>
      </div>
    </div>

    <div class="col-12 overflow-y-scroll lista-comands">

      <div class="row border border-1 " v-for="row in rows" :key="row.id">
        <div class="col-6 p-2">
          <button class="btn btn-light btn-sm" @click="openEditModal(row.id)" type="button">
            <i class="bi bi-pencil-fill"></i>
          </button>
          <button class="btn btn-light btn-sm" @click="deleteRow(row.id)" type="button">
            <i class="bi bi-trash2-fill"></i>
          </button>
          <button class="btn btn-light btn-sm" @click="execComands(row.comand)" type="button">
            <i class="bi bi-arrow-left-square-fill"></i>
          </button>
          <button class="btn btn-light btn-sm" @click="copyText(row.comand)" type="button">
            <i class="bi bi-clipboard-fill"></i>
          </button>
        </div>
        <div class="col-6 p-2">
          <strong>Nome : </strong>{{ row.nome }}
          <br>
          <strong>comando : </strong>{{ row.comand }}
        </div>


      </div>


    </div>

  </div>

  <modal-widget-vue v-if="addFormModal">
    <div class="row">
      <div class="col-12 ">
        <i class="bi bi-x float-end icon" @click="closeAddFormModal"></i>
      </div>
    </div>
    <create-comands></create-comands>
  </modal-widget-vue>
  <modal-widget-vue v-if="editFormModal">
    <div class="row">
      <div class="col-12 ">
        <i class="bi bi-x float-end icon" @click="closeEditModal"></i>
      </div>
    </div>
    <edit-comands :idFormModal="idFormModal"></edit-comands>
  </modal-widget-vue>
</template>
<script>
import toastr from "toastr/build/toastr.min";
import Helpers from "@/services/Helpers";
import ComandService from "@/services/comands.service";
import ModalWidgetVue from "@/components/widget/modalWidgetVue.vue";
import CreateComands from "@/views/comands/CreateComands.vue";
import EditComands from "@/views/comands/EditComands.vue";

export default {
  name: "IndexComands",
  components: {EditComands, CreateComands, ModalWidgetVue},
  data() {
    return {
      rows: null,
      search: null,
      addFormModal: false,
      editFormModal: false,
      idFormModal: null
    }
  },
  methods: {
    async list() {

      let tableService = new ComandService();
      let dataRow = await tableService.list(this.search);
      let helpers = new Helpers();

      if (dataRow.data.data.length > 0) {
        this.rows = dataRow.data.data;

      } else if (!helpers.empty(dataRow.response?.data)) {
        toastr.error('Houve um problema');
      } else {
        this.rows = false;
      }


    },
    async deleteRow(id) {
      let tableService = new ComandService();
      let dataRow = await tableService.delete(id);
      if (dataRow.data.success) {
        this.list();
        toastr.success('Apagado com sucesso');
      } else {
        toastr.error('Houve um problema ao apagar');
      }
    }, openAddFormModal() {
      this.addFormModal = true;
    },
    closeAddFormModal() {
      this.addFormModal = false;
    },
    openEditModal(id) {
      this.idFormModal = id;
      this.editFormModal = true;
    },
    closeEditModal() {
      this.editFormModal = false;
      this.list();
    },
    execComands(comand){
      let elementTerminalInput =  document.getElementById('terminalInput');
      elementTerminalInput.value = comand;
      elementTerminalInput.focus();
    },
    copyText(texto){
      navigator.clipboard.writeText(texto)
          .then(() => {
            console.log("Texto copiado com sucesso!");
          })
          .catch(err => {
            console.error("Erro ao copiar texto: ", err);
          });

    },

  },
  mounted() {
    this.list();
  }
}

</script>

<style scoped>
@import "toastr/build/toastr.css";
@import "bootstrap-icons/font/bootstrap-icons.min.css";

.bi-white::before {
  color: white;
  font-weight: 800 !important;
}
.lista-comands{
  height: 480px !important;
}
.icon::before {
  font-size: 35px;
  cursor: pointer;
}
</style>
