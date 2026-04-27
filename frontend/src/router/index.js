import { createRouter, createWebHistory } from 'vue-router'

import AuthLayout from '../components/layout/AuthLayout.vue'

import Login from '../views/authentication/Login.vue'
import Register from '../views/authentication/Register.vue'
import AdminAuthorizationTest from '@/views/authentication/AdminAuthorizationTest.vue'
import UserAuthorizationTest from '@/views/authentication/UserAuthorizationTest.vue'

const routes = [
    {
        path: '/auth',
        component: AuthLayout,
        children: [
            { 
                path: 'login', 
                component: Login, 
                meta: { title: "Login" }
            },
            { 
                path: 'register', 
                component: Register,
                meta: { title: "Register" }
            },
            { 
                path: 'admin-test', 
                component: AdminAuthorizationTest,
                meta: { title: "AdminTest" }
            },
            { 
                path: 'user-test', 
                component: UserAuthorizationTest,
                meta: { title: "UserTest" }
            }
        ]
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.afterEach((to) => {
    if (to.meta.title) {
        document.title = to.meta.title
    }
})

export default router
