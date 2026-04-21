import { createRouter, createWebHistory } from 'vue-router'
import Login from '../views/authentication/login.vue'
import Register from '../views/authentication/register.vue'

const routes = [
    { path: '/login', component: Login },
    { path: '/', component: Login },
    { path: '/register', component: Register }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
