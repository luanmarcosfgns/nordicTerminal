<template>
  <div v-if="type==='hidden'">
    <input type="hidden" :name="name" :id="name" :class="name" class="form-control" :value="value">
  </div>
  <div v-if="type==='blob' || type==='longblob' || type==='tinyblob' || type==='mediumblob'" :class="classList">
    <div class="row">
      <div class="col-12">
        <label class="p-2" for="nome">{{ label }}</label>
      </div>
      <div class="col-12 p-4">
        <img  alt="Quando Não há imagem" :id="'img-'+name" src="@/assets/no-image.png" width="300">
      </div>
      <div class="col-12">
        <input :placeholder="placeholder" type="file" @change="setImageAndValue" data-value="" :name="'display-'+name"
               :id="'display-'+name" :class="'display-'+name" class="form-control">
        <input type="hidden" :name="name" :id="name" :class="name" class="form-control" :value="value">
      </div>

    </div>
  </div>
  <div v-if="type==='string'" :class="classList">
    <label class="p-2" for="nome">{{ label }}</label>
    <input :placeholder="placeholder" type="text" :name="name" :id="name" :class="name" class="form-control"
           v-model="valueInput">
  </div>
  <div v-if="type==='mask'" :class="classList">
    <label class="p-2" for="nome">{{ label }}</label>
    <input :placeholder="placeholder" type="text" v-mask="mask" :name="name" :id="name" :class="name" class="form-control"
           v-model="valueInput">
  </div>
  <div v-if="type==='decimal' ||type === 'double'" :class="classList">
    <label class="p-2" for="nome">{{ label }}</label>
    <input :placeholder="placeholder" type="number" :name="name" :id="name" :class="name"
           class="form-control decimal" v-model="valueInput">
  </div>
  <div v-if="type==='date'" :class="classList">
    <label class="p-2" for="nome">{{ label }}</label>
    <input :placeholder="placeholder" type="date" :name="name" :id="name" :class="name" class="form-control"
           v-model="valueInput">
  </div>
  <div v-if="type==='time'" :class="classList">
    <label class="p-2" for="nome">{{ label }}</label>
    <input :placeholder="placeholder" type="time" :name="name" :id="name" :class="name" class="form-control"
           v-model="valueInput">
  </div>
  <div v-if="type==='datetime'" :class="classList">
    <label class="p-2" for="nome">{{ label }}</label>
    <input :placeholder="placeholder" type="datetime-local" :name="name" :id="name" :class="name" class="form-control"
           v-model="valueInput">
  </div>
  <div v-if="type==='tinyint'" :class="classList">
    <label class="p-2" for="nome">{{ label }}</label>
    <select :name="name" :id="name" :class="name" class="form-control" v-model="valueInput">
      <option value="1">Sim</option>
      <option value="0">Não</option>
    </select>
  </div>
  <div v-if="type==='text'" :class="classList">
    <label class="p-2" for="nome">{{ label }}</label>
    <textarea :placeholder="placeholder" :name="name" :id="name" :class="name" class="form-control"
              v-model="valueInput">

        </textarea>
  </div>
  <div v-if="type==='int' ||type === 'bigint'" :class="classList">
    <label class="p-2" for="nome">{{ label }}</label>
    <input step="0.00" :placeholder="placeholder" @input="validateInt" @keyup="validateInt" @keydown="validateInt"
           type="number" :name="name" :id="name" :class="name"
           class="form-control decimal" v-model="valueInput">
  </div>
  <div v-if="type==='json'" :class="classList">
    <label class="p-2" for="nome">{{ label }}</label>
    <div class="row">
      <div class="col-10">
        <input :placeholder="placeholder" type="text" :id="'search'+name" :class="'search'+name"
               class="form-control">
        <input type="hidden" :name="name" :id="name" :class="name" v-model="valueInput">
      </div>
      <div class="col-2">
        <button @click="addRowSelectJson" class="btn btn-primary">+</button>
      </div>
      <div class="col-12">
        <ol>
          <li class="p-2" v-for="(row,i) in rows" v-bind:key="row">

            <div class="btn-group ">
              <button class="btn btn-danger btn-xsm">
                <input :id="this.name+'_options_'+i" :value="row" @change="editRowJson(i)">
              </button>
              <button class="btn btn-danger btn-xsm" @click="deleteRowJson(i)">
                <i class="bi bi-trash2-fill"></i>
              </button>
            </div>

          </li>
        </ol>

      </div>
    </div>

  </div>
  <div v-if="type==='select'" :class="classList" class="form-group">
    <label class="p-2" for="nome">{{ label }}</label>
    <select :name="name" :id="name" :class="name" class="form-control" v-model="valueInput">
      <option v-if="placeholder!=udefined">
        {{ this.placeholder }}
      </option>
      <template v-for="item in items" :key="item.id">
        <option :value="item.id">
          {{ item.message }}
        </option>
      </template>

    </select>
  </div>
  <div v-if="type==='select2'" :class="classList" class="form-group">

    <div class="row">
      <div class="col-12">
        <label class="p-2" :for="name">{{ label }}</label>
      </div>
      <div class="col-12">
        <input placeholder="Digite o código" type="hidden" :name="name" :id="name"
               :class="name"
               tabindex="0"
               spellcheck="false"
               role="textbox"
               autocomplete="off"
               autocorrect="off"
               autocapitalize="off"
               class="form-control decimal" v-model="valueInput">
        <input @input="readClickSelect2" class="form-control dropdown-toggle" type="search"
               placeholder="Ou digite a pesquisa"
               tabindex="0"
               autocomplete="off"
               autocorrect="off"
               autocapitalize="off"
               spellcheck="false"
               role="textbox"
               :name="'search-'+name"
               :id="'search-'+name">
        <div class="row">
          <div class="col-12">
            <ul class="dropdown-item-select2" :id="'dropdown-'+name">
              <li v-for="row in rows" :key="row" @click="setLabelSelect2(row.code)">
                {{ row.label }}
              </li>
            </ul>
          </div>
        </div>


      </div>
    </div>
  </div>
</template>

<script>

import Helpers from "@/services/Helpers";
import RequestHelper from "@/services/RequestHelper";
import MaskTollBox from "@/ToolBox/mask.toll.box.js";

export default {
  name: "inputForm",
  props: {
    mask:String,
    name: String,
    url: String,
    placeholder: String,
    type: String,
    value: String,
    label: String,
    classList: String,
    items: Object
  },
  data() {
    return {
      valueInput: null,
      rows: null
    }
  },
  created() {
    this.onReadComponent();


  },
  methods: {
    onReadComponent() {
      let helpers = new Helpers();
      helpers.empty()

      if (!helpers.empty(this.value)) {
        this.valueInput = this.value;
      }

    },
    setImageAndValue() {
      let fileInput = document.getElementById('display-'+this.name);

      let extention = fileInput.files[0].type;
      let imageDisplay = document.querySelector('#img-' + this.name);
      let selectedFile = fileInput.files[0];
      let fileReader = new FileReader();
      imageDisplay.src = require('@/assets/documento.png');


      fileReader.onload = (event) => {
        if (extention.includes('image')) {
          imageDisplay.src = event.target.result;
        }
        let fileInputHidden = document.getElementById(this.name);
        fileInput.dataset.value = event.target.result;
        fileInputHidden.value = event.target.result;
        console.log(selectedFile)
        let fileList = selectedFile.name.split('.');

        fileInput.dataset.name =fileList[0];
        fileInputHidden.dataset.name =fileList[0];
        fileInput.dataset.ext =fileList[1]
        fileInputHidden.dataset.ext =fileList[1]
      }
      fileReader.readAsDataURL(selectedFile);


    },
    addRowSelectJson() {
      let row = document.getElementById('search' + this.name).value;
      if (this.rows == null) {
        this.rows = [];
      }
      this.rows.push(row)
      this.valueInput = JSON.stringify(this.rows)
      this.valueInput = this.clearStringJson(this.valueInput);
      document.getElementById('search' + this.name).value = '';
    },
    async readRowSelectJson() {

      setTimeout(() => {
        let value = document.getElementById(this.name).value;
        let helper = new Helpers();
        if (!helper.empty(value)) {
          this.valueInput = value;
          this.valueInput = this.clearStringJson(this.valueInput);
          this.listRowJson(this.valueInput);
        }

      }, 1000)

    },
    listRowJson(list) {
      this.rows = this.clearStringJson(list).split(',');
    },
    deleteRowJson(i) {
      this.rows.splice(i, 1);
      this.valueInput = JSON.stringify(this.rows)
      this.valueInput = this.clearStringJson(this.valueInput);

    },
    editRowJson(i) {
      this.rows[i] = document.getElementById(this.name + '_options_' + i).value;
      this.valueInput = JSON.stringify(this.rows)
      this.valueInput = this.clearStringJson(this.valueInput);
      console.log(document.getElementById(this.name).value)
    },
    clearStringJson(value) {
      let helper = new Helpers();
      value = helper.decodeUnicode(value);
      return value
          .replaceAll('[', '')
          .replaceAll(']', '')
          .replaceAll('"', '')
          .replaceAll('\\', '')
          .replaceAll("'", '');
    },
    async readClickSelect2() {
      if (this.type === 'select2') {
        document.getElementById('dropdown-' + this.name).classList.add('d-none');
        let request = new RequestHelper();

        var searchTimeout;
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(async () => {
          let search = document.getElementById('search-' + this.name).value;
          let payload = {
            search: search,
          };
          if (search != undefined) {
            payload = {
              search: search
            }
          }

          let response = await request.getAuth(process.env.VUE_APP_API_HOST_NAME + this.url, payload);

          if (response.data.length == 1) {
            document.getElementById('search-' + this.name).value = response.data[0].label
            document.getElementById(this.name).value = response.data[0].code
            document.getElementById('dropdown-' + this.name).classList.add('d-none');
          } else if (response.data.length > 1) {
            this.rows = response.data;
            document.getElementById('dropdown-' + this.name).classList.remove('d-none');
          } else {
            document.getElementById('dropdown-' + this.name).classList.add('d-none');
          }
        });

      }
    },
    async setIdSelect2() {
      let id = this.valueInput;
      document.getElementById('dropdown-' + this.name).classList.add('d-none');
      let payload = {
        id: id,
      }
      let request = new RequestHelper();
      let response = await request.getAuth(process.env.VUE_APP_API_HOST_NAME + this.url, payload);

      if (!new Helpers().empty(response?.data?.code)) {
        document.getElementById('search-' + this.name).value = response.data.label;
        document.getElementById(this.name).value = response.data.code;
      }

      return true;
    },
    async setLabelSelect2(id) {
      let payload = {
        id: id,
      }
      let request = new RequestHelper();
      let response = await request.getAuth(process.env.VUE_APP_API_HOST_NAME + this.url, payload);

      if (!new Helpers().empty(response?.data?.code)) {
        document.getElementById('search-' + this.name).value = response.data.label;
        document.getElementById(this.name).value = response.data.code;
      }
      document.getElementById('dropdown-' + this.name).classList.add('d-none');

      return true;
    },
    mountSelect2()  {
      if (this.type === 'select2') {

        setTimeout(() => {
          let id = document?.getElementById(this.name)?.value;
          let helper = new Helpers();
          if (!helper.empty(id)) {
            this.setLabelSelect2(id);
          }

        }, 2000)

      }


    },
    validateInt() {
      this.valueInput = new String(this.valueInput).replaceAll(',', '').replaceAll('.', '')
    },
    setImageAndValueDisplay() {
      setTimeout(()=>{
        let file = document.getElementById(this.name).value;
        let headerBase64 = file.substring(0,30);
        if(headerBase64.includes('png') || headerBase64.includes('jpg')|| headerBase64.includes('jpeg')|| headerBase64.includes('gif')|| headerBase64.includes('webp')|| headerBase64.includes('bmp') || headerBase64.includes('apng') || headerBase64.includes('svg')){
          console.log(file)
          document.getElementById('img-' +this.name).src = file;
        }else{
          document.getElementById('img-' +this.name).src = require('@/assets/documento.png');
          document.getElementById('img-' +this.name).addEventListener('click',()=>{
             let base64Data = document.getElementById(this.name).value;
            // Tenta detectar o tipo MIME e a extensão do arquivo a partir do Base64
            const base64Pattern = /^data:([^;]+);base64,(.+)$/;
            const match = base64Data.match(base64Pattern);

            if (!match) {
              console.error('Formato de Base64 inválido.');
              return;
            }

            const mimeType = match[1];
            const data = match[2];

            // Mapeia tipos MIME para extensões de arquivo
            const mimeTypeMap = {
              'image/png': 'png',
              'image/jpeg': 'jpg',
              'application/pdf': 'pdf',
              'text/plain': 'txt',
              'application/json': 'json',
              // Adicione mais tipos MIME e extensões conforme necessário
            };

            // Obtem a extensão do arquivo com base no tipo MIME
            const extension = mimeTypeMap[mimeType] || 'txt'; // Padrão para 'txt' se o tipo não estiver no mapa

            // Gera um nome de arquivo com base no tipo MIME
            const fileName = `downloaded_file.${extension}`;

            // Cria um link temporário
            const link = document.createElement('a');

            // Define o href com os dados em Base64
            link.href = `data:${mimeType};base64,${data}`;

            // Define o nome do arquivo para download
            link.download = fileName;

            // Adiciona o link ao corpo do documento
            document.body.appendChild(link);

            // Simula o clique no link para iniciar o download
            link.click();

            // Remove o link do documento
            document.body.removeChild(link);
          })
        }

      },2000)

      }

  },
  mounted() {
    this.mountSelect2()
    if (this.type == 'json') {
      this.readRowSelectJson();

    }
    if (this.type == 'int') {
      this.validateInt();

    }

    if (this.type==='blob' || this.type==='longblob' || this.type==='tinyblob' || this.type==='mediumblob') {
      this.setImageAndValueDisplay();

    }
  },
  directives: {
    mask: {
      mounted(el, binding) {
        setTimeout(()=>{
          el.value = new MaskTollBox(binding.value, el.value).applyMask();
        },2000)

        el.addEventListener('keyup', () => {
          el.value = new MaskTollBox(binding.value, el.value).applyMask();
        })




      },
    }
  },



}
</script>

<style scoped>
.dropdown-item-select2 {
  list-style: none;

  position: absolute;


}

.dropdown-item-select2 li {
  padding: 8px;
  border-bottom: 0.1px solid #dee2e6;
  border-left: 0.1px solid #dee2e6;
  border-right: 0.1px solid #dee2e6;
  overflow-wrap: break-word;
  width: 210px;
  background-color: white;


}

.dropdown-item-select2 li:hover {

  background-color: rgba(166, 177, 197, 0.98);
  cursor: pointer;


}

.form-control {
  border-top-color: white !important;
  border-left-color: white !important;
  border-right-color: white !important;
  border-radius: 0px;
}

.btn-xsm {
  --bs-btn-padding-y: 1px;
  --bs-btn-padding-x: 15px;
  --bs-btn-font-size: 15px
}
</style>