import { createRouter, createWebHistory } from "vue-router";
import LoginForm from "@/views/auth/LoginForm.vue";
import notFound from "@/views/errors/NotFound.vue";
import Middleware from "@/services/Middleware";
import createUsers from "@/views/users/CreateUsers.vue";
import editUsers from "@/views/users/EditUsers.vue";
import indexUsers from "@/views/users/IndexUsers.vue";
import DashBoardPage from "@/views/pages/DashBoardPage.vue";
import GoogleLogin from "@/views/auth/GoogleLogin.vue";
import RegisterForm from "@/views/auth/RegisterForm.vue";

import indexConnections from "@/views/connections/IndexConnections.vue";
import terminalConnections from "@/views/connections/terminalConnections.vue";
const routes = [
    //users
    {
        path: '/users/create',
        name: 'createUsers',
        component: createUsers,
        meta: {
            auth: true
        }
    },
    {
        path: '/users/index',
        name: 'indexUsers',
        component: indexUsers,
        meta: {
            auth: true
        }
    },
    {
        path: '/users/edit/:id',
        name: 'editUsers',
        component: editUsers,
        meta: {
            auth: true
        }
    },
    {
        path: '/register',
        name: 'RegisterForm',
        component: RegisterForm,
        meta: {
            auth: false
        }
    },

    //auth
    {
        path: '/404',
        component: notFound
    },
    {
        path: '/',
        redirect: '/login'
    },
    {
        path: '/painel',
        name: 'DashBoardPage',
        component: DashBoardPage,
        meta: {
            auth: true
        }
    },
    {
        path: '/login',
        name: 'LoginForm',
        component: LoginForm,
        meta: {
            auth: false
        }

    },
    {
        path: '/auth/google/callback',
        name: 'GoogleLogin',
        component: GoogleLogin,
        meta: {
            auth: false
        }

    },

    {
        path: '/connections/index',
        name: 'indexConnections',
        component: indexConnections,
        meta: {
            auth: true
        }
    },
    {
        path: '/connections/comandus/:hash',
        name: 'terminalConnections',
        component: terminalConnections,
        meta: {
            auth: true
        }
    },
        

        
];
const router = createRouter({ history: createWebHistory(), routes });
router.beforeEach((to) => {
    let middleware = new Middleware();
    if (!middleware.logout(to)) {
        middleware.routeExists(to);
        middleware.validateHash(to);

    }


})
router.afterEach((to) => {
    let middleware = new Middleware();
    middleware.setRegisterLastRouteBeforeLogin();
    middleware.userPermissions(to);
    middleware.finishLoading();

});
export default router;