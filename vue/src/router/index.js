import { createRouter, createWebHistory } from "vue-router";

import Dashboard from '../views/Dashboard.vue';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import Surveys from '../views/Surveys.vue';
import SurveyView from '../views/SurveyView.vue';
import DefaultLayout from '../components/DefaultLayout.vue';
import AuthLayout from '../components/AuthLayout.vue';

import store from "../store";

const routes = [
    {
        path: '/',
        redirect: '/dashboard',
        name: 'Dashboard',
        meta: {requiresAuth: true,},
        component: DefaultLayout,
        children: [
           { 
            path: '/dashboard',
            name: 'Dashboard',
            component: Dashboard,
            },
            {
                path: '/polls',
                name: 'Polls',
                component: Surveys
            },
            {
                path: '/polls/create',
                name: 'SurveyCreate',
                component: SurveyView
            },
            {
                path: '/polls/:id',
                name: 'SurveyView',
                component: SurveyView
            },
        ]

    },

    {
        path: '/auth',
        redirect: '/login',
        name: 'Auth',
        component: AuthLayout,
        meta: {isGuest: true,},
        children: [
            {
                path: '/login',
                name: 'Login',
                component: Login
            },
            {
                path: '/register',
                name: 'Register',
                component: Register
            },
        ]

    },
];

const router = createRouter({
    history : createWebHistory(),
    routes
});


router.beforeEach((to,from,next) => {
    if(to.meta.requiresAuth && !store.state.user.token ){
        next({name: 'Login'});
    }else if(store.state.user.token && to.meta.isGuest){
        next({name: 'Dashboard'});
    }else{
        next();
    }
});

export default router;