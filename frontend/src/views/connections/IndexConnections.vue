<template>
  <layout-page-no-menu>
    <div class="row">
      <div class="col-12">
        <div class="row">
          <div class="col-12 py-4  pt-1 ">
            <div class="float-start">
              <h5> Conexões</h5>
            </div>


          </div>
          <div class="row text-center">
            <div class="col-10">
              <input id="search" class="form-control" @change="list()" placeholder="Digite sua pesquisa"
                     type="text" v-model="search">
            </div>

            <div class="col-2">
              <button class="btn  btn-primary" @click="openAddFormModal"><i class="bi bi-plus bi-white"></i>
              </button>
            </div>
          </div>

        </div>

        <div class="row mt-4" v-for="row in rows" :key="row.id">
          <div class="col-4">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-12"><strong>Nome : </strong>{{ row.nome }}</div>
                  <div class="col-12"><strong>Endereço : </strong>{{ row.host }}</div>
                  <div class="col-12"><strong>Porta : </strong>{{ row.port }}</div>
                  <div class="col-12"><strong>Usuário : </strong>{{ row.username }}</div>
                  <div class="col-12">
                    <div class="dropdown">
                      <button class="btn btn-system btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown"
                              aria-expanded="false">
                        Ações
                      </button>
                      <ul class="dropdown-menu">
                        <li>
                       <span class="dropdown-item cursor-pointer" @click="openEditModal(row.id)">
                         <i class="bi bi-pencil-square"></i>
                           Editar
                         </span>
                        </li>
                        <li>
                     <span class="dropdown-item cursor-pointer" @click="deleteRow(row.id)">
                        <i class="bi bi-trash2-fill"></i>
                          Apagar
                     </span>
                        </li>
                        <li>
                     <span class="dropdown-item cursor-pointer" @click="openTerminal(row.id)">
                       <i class="bi bi-terminal-fill"></i>
                          Conectar
                     </span>
                        </li>
                      </ul>
                    </div>


                  </div>
                </div>
              </div>

            </div>
          </div>


        </div>
        <div class="row" v-if="rows==null ">
          <div class="col-12">
            <div class="row">
              <div class="col-12 d-flex justify-content-center">
                <div class="loader"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="row" v-if="rows===false ">
          <div class="col-12 text-center"> Não há dados</div>
        </div>


      </div>
    </div>
    <modal-widget-vue v-if="addFormModal">
      <div class="row">
        <div class="col-12 ">
          <i class="bi bi-x-circle float-end icon" @click="closeAddFormModal"></i>
        </div>
      </div>
      <create-connections></create-connections>
    </modal-widget-vue>
    <modal-widget-vue v-if="editFormModal">
      <div class="row">
        <div class="col-12 ">
          <i class="bi bi-x-circle float-end icon" @click="closeEditModal"></i>
        </div>
      </div>
      <edit-connections :idFormModal="idFormModal"></edit-connections>
    </modal-widget-vue>


  </layout-page-no-menu>
</template>
<script>

import toastr from "toastr/build/toastr.min";
import Helpers from "@/services/Helpers";
import ConnectionService from "@/services/connections.service";
import ModalWidgetVue from "@/components/widget/modalWidgetVue.vue";
import CreateConnections from "@/views/connections/CreateConnections.vue";
import EditConnections from "@/views/connections/EditConnections.vue";
import LayoutPageNoMenu from "@/components/page/layoutPageNoMenu.vue";

export default {
  name: "IndexConnections",
  components: {LayoutPageNoMenu, EditConnections, CreateConnections, ModalWidgetVue},
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

      let tableService = new ConnectionService();
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
      let tableService = new ConnectionService();
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
    },
    openTerminal(id){
      let hash = btoa(id);
      this.$router.replace('/connections/comandus/'+hash);
    }

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
</style>
