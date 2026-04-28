import { createRouter, createWebHistory } from 'vue-router'

import AuthLayout from '../components/layout/AuthLayout.vue'

import Login from '../views/authentication/Login.vue'
import Register from '../views/authentication/Register.vue'
import AdminAuthorizationTest from '@/views/authentication/AdminAuthorizationTest.vue'
import UserAuthorizationTest from '@/views/authentication/UserAuthorizationTest.vue'
import Logout from '@/views/authentication/logout.vue'

import { useAuthStore } from "@/stores/authStore.js"
import { useErrorHandlingStore } from "@/stores/errorHandlingStore"

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
                path: 'logout', 
                component: Logout,
                meta: { 
                    isAuthenticated: true,
                }
            },
            { 
                path: 'admin-test', 
                component: AdminAuthorizationTest,
                meta: { 
                    title: 'AdminTest',
                    isAuthenticated: true,
                    roles: ['Admin']
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
    const errorHandlingStore = useErrorHandlingStore()

    if (to.meta.isAuthenticated) {

        const decodedAuthToken = authStore.decodedAuthToken

        if (decodedAuthToken === null) {
            errorHandlingStore.setErrorMessage('You need to be logged in to perform this action.')
            return '/auth/login'
        }

        if (to.meta.roles) {

            let isAuthorized = false

            for (const role of to.meta.roles) {
                if (decodedAuthToken.data.role === role){
                    isAuthorized = true
                    break
                }
            }

            if (isAuthorized === false) {
                errorHandlingStore.setErrorMessage(`Your account doesn't have the right role to perform this action.`)
                return '/auth/login'
            }
        }
    }
    
    if (to.meta.title) {
        document.title = to.meta.title
    }
})

export default router
