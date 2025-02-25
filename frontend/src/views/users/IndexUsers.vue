<template>
  <layout-page>
    <div class="row">
      <div class="col-12">
        <div class="row">
          <div class="col-12 ps-4 pt-3 ">
            <div class="float-start">
              <h5> Usuário</h5>
            </div>
            <div class="w-50">
              <input id="search" class="form-control" @change="list()" placeholder="Digite sua pesquisa"
                     type="text" v-model="search">
            </div>

            <div class="float-end">
              <button class="btn btn-primary" @click="openAddFormModal">Adicionar</button>
            </div>
          </div>

        </div>
        <table class="table">
          <thead>
          <tr>
            <th>Ações</th>
            <th></th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="row in rows" :key="row.id">
            <td>
              <div class="dropdown">
                <button class="btn btn-system btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                  Ações
                </button>
                <ul class="dropdown-menu">
                  <li>
                      <span  class="dropdown-item cursor-pointer" @click="openEditModal(row.id)">
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
                </ul>
              </div>


            </td>

            <td>
              <div class="col-12"><strong>Nome : </strong>{{ row.nome }}</div>
              <div class="col-12"><strong>E-mail : </strong>{{ row.email }}</div>
            </td>
            <td>


              <div class="col-12"><strong>Ativo : </strong>{{ row.ativo ? 'Sim' : 'Não' }}</div>

            </td>

          </tr>
          <tr v-if="rows==null ">
            <td colspan="2">
              <div class="row">
                <div class="col-12 d-flex justify-content-center">
                  <div class="loader"></div>
                </div>
              </div>
            </td>
          </tr>
          <tr v-if="rows===false ">
            <td colspan="2" class="text-center"> Não há dados</td>
          </tr>
          </tbody>
        </table>

      </div>
    </div>
    <modal-widget-vue v-if="addFormModal">
      <div class="row">
        <div class="col-12 ">
          <i class="bi bi-x-circle float-end icon" @click="closeAddFormModal"></i>
        </div>
      </div>
      <create-users></create-users>
    </modal-widget-vue>
    <modal-widget-vue v-if="editFormModal">
      <div class="row">
        <div class="col-12 ">
          <i class="bi bi-x-circle float-end icon" @click="closeEditModal"></i>
        </div>
      </div>
      <edit-users :idFormModal="idFormModal" ></edit-users>
    </modal-widget-vue>


  </layout-page>
</template>
<script>
import LayoutPage from "@/components/page/layoutPage.vue";

import toastr from "toastr/build/toastr.min";
import Helpers from "@/services/Helpers";
import userService from "@/services/user.service";
import ModalWidgetVue from "@/components/widget/modalWidgetVue.vue";
import CreateUsers from "@/views/users/CreateUsers.vue";
import EditUsers from "@/views/users/EditUsers.vue";

export default {
  name: "IndexUsers",
  components: {EditUsers, CreateUsers, ModalWidgetVue, LayoutPage},
  data() {
    return {
      rows: null,
      search: null,
      addFormModal: false,
      editFormModal: false,
      idFormModal:null
    }
  },
  methods: {
    async list() {

      let usersService = new userService();
      let dataRow = await usersService.list(this.search);
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
      let usersService = new userService();
      let dataRow = await usersService.delete(id);
      if (dataRow.data.success) {
        this.list();
        toastr.success('Apagado com sucesso');
      } else {
        toastr.error('Houve um problema ao apagar');
      }
    },
    openAddFormModal() {
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
    }

  },
  created() {
    this.list();

  }
}

</script>

<style scoped>
@import "toastr/build/toastr.css";
@import "bootstrap-icons/font/bootstrap-icons.min.css";

.icon {
  font-size: 25px !important;
  cursor: pointer !important;
  height: 30px !important;
  width: 30px !important;

}
</style>
