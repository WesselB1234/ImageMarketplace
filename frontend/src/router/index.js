import { createRouter, createWebHistory } from 'vue-router'

import AuthLayout from '../components/layout/AuthLayout.vue'

import Login from '../views/authentication/Login.vue'
import Register from '../views/authentication/Register.vue'
import AdminAuthorizationTest from '@/views/authentication/AdminAuthorizationTest.vue'
import UserAuthorizationTest from '@/views/authentication/UserAuthorizationTest.vue'
import { useAuthStore } from "@/stores/auth.js"

const routes = [
    {
        path: '/auth',
        component: AuthLayout,
        children: [
            { 
                path: 'login', 
                component: Login, 
                meta: { 
                    title: 'Login'
                }
            },
            { 
                path: 'register', 
                component: Register,
                meta: { 
                    title: 'Register'
                }
            },
            { 
                path: 'admin-test', 
                component: AdminAuthorizationTest,
                meta: { 
                    title: 'AdminTest',
                    isAuthenticated: true,
                    roles: ['admin']
                }
            },
            { 
                path: 'user-test', 
                component: UserAuthorizationTest,
                meta: { 
                    title: 'UserTest',
                    isAuthenticated: true
                }
            }
        ]
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to) => {

    const authStore = useAuthStore()

    if (to.meta.title) {
        document.title = to.meta.title
    }

    if (to.meta.isAuthenticated) {

        const decodedAuthToken = authStore.decodedAuthToken

        if (decodedAuthToken === null) {
            return '/auth/login'
        }

        if (to.meta.roles) {

            let isAuthorized = false

            for (const role of to.meta.roles) {
                if (decodedAuthToken.role === role){
                    isAuthorized = true
                    break
                }
            }

            if (isAuthorized === false) {
                return '/auth/login'
            }
        }   
    }
})

export default router
