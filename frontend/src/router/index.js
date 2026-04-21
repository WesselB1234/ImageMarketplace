import { createRouter, createWebHistory } from 'vue-router'

import AuthLayout from '../components/layout/AuthLayout.vue'

import Login from '../views/authentication/Login.vue'
import Register from '../views/authentication/Register.vue'

const routes = [
    {
        path: '/auth',
        component: AuthLayout,
        children: [
            { 
                path: 'login', 
                component: Login, 
                name: 'Login' 
            },
            { 
                path: 'register', 
                component: Register,
                name: 'Register' 
            }
        ]
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
