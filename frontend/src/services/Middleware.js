import Helpers from "@/services/Helpers";
import UserService from "@/services/user.service";

export default class Middleware {

    validateHash(to) {
        let helper = new Helpers();
        if (to.meta?.auth && helper.empty(localStorage.getItem("HASH"))) {
            location.href = "/login";
        }

        if (to.name == "LoginForm" && !helper.empty(localStorage.getItem("HASH"))) {
            location.href = '/painel'
        }
    }

    routeExists(to) {
        if (to.matched.length == 0) {
            location.href = '/404'
        }
    }

    logout(to) {
        if (to.href == '/logout') {
            localStorage.setItem('HASH', undefined);
            location.href = '/login'
            return true;
        }
        return false;

    }

    finishLoading() {
        setTimeout(function () {
            document.getElementsByClassName('loading-page')[0].classList.add('d-none');
        }, 1000)
    }
    async hideRevendedor() {
        let userService = await new UserService();
        let response = await userService.permissions();
        let permissions = response.data;

        if(permissions?.tipo==='gestor') return false;
        setTimeout(function () {

            let revendedorNoneElements = document.getElementsByClassName('revendedor_none');
            for (const revendedorNoneElement of revendedorNoneElements) {
                revendedorNoneElement.classList.add('d-none');
            }
        }, 1000)
    }
    async hideAdmin() {
        let userService = await new UserService();
        let response = await userService.permissions();
        let permissions = response.data;

        if(permissions?.tipo==='gestor') return false;
        setTimeout(function () {

            let revendedorNoneElements = document.getElementsByClassName('admin_none');
            for (const revendedorNoneElement of revendedorNoneElements) {
                revendedorNoneElement.classList.add('d-none');
            }
        }, 1000)
    }


    setRegisterLastRouteBeforeLogin() {
        if (location.pathname !== "/login" && location.pathname !== "/logout" &&  location.pathname !== "/register") {
            localStorage.setItem('href', location.href);
            console.log(localStorage.getItem('href'))
        }
    }

    getRegisterLastRouteBeforeLogin() {
        return localStorage.getItem('href');
    }

     userPermissions(to) {
       if(!to.meta.auth){
           return false;
       }
       return true;


    }
}