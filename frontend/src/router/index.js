import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from "@/stores/authStore.js"
import { useErrorHandlingStore } from "@/stores/errorHandlingStore"

import AuthLayout from '@/components/layout/AuthLayout.vue'
import DefaultLayout from '@/components/layout/DefaultLayout.vue'

import Login from '@/components/pages/authentication/Login.vue'
import Register from '@/components/pages/authentication/Register.vue'
import Logout from '@/components/pages/authentication/Logout.vue'
import AdminAuthorizationTest from '@/components/pages/authentication/AdminAuthorizationTest.vue'
import UserAuthorizationTest from '@/components/pages/authentication/UserAuthorizationTest.vue'

import UploadImage from '@/components/pages/images/UploadImage.vue'

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
            },
        ],
    },
    {
        path: '/',
        component: DefaultLayout,
        children: [
            { 
                path: '/images/upload', 
                component: UploadImage, 
                meta: { 
                    title: 'Upload image',
                    isAuthenticated: true
                }
            },
            { 
                path: '/images', 
                component: Upload, 
                meta: { 
                    title: 'Upload image',
                    isAuthenticated: true
                }
            },
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
