import { createApp } from 'vue'
import App from './App.vue'
import router from "@/route/web";
import "bootstrap/dist/css/bootstrap.min.css"
import "bootstrap"
createApp(App)
    .use(router)
    .mount('#app')


import 'sweetalert2/dist/sweetalert2.min.css';
