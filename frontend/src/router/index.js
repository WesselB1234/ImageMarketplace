import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from "@/stores/authStore.js"
import { useErrorHandlingStore } from "@/stores/errorHandlingStore"

import AuthLayout from '@/components/layout/AuthLayout.vue'
import DefaultLayout from '@/components/layout/DefaultLayout.vue'

import Login from '@/components/pages/authentication/Login.vue'
import Register from '@/components/pages/authentication/Register.vue'
import UserAuthorizationTest from '@/components/pages/authentication/UserAuthorizationTest.vue'

import ImageDetails from '@/components/pages/images/ImageDetails.vue'
import SellImage from '@/components/pages/images/SellImage.vue'
import UploadImage from '@/components/pages/images/UploadImage.vue'
import ViewImages from '@/components/pages/images/ViewImages.vue'

import ViewPortfolio from '@/components/pages/portfolio/ViewPortfolio.vue'

import ViewSettings from '@/components/pages/settings/ViewSettings.vue'

import viewPrivacy from '@/components/pages/privacy/ViewPrivacy.vue'

import CreateUser from '@/components/pages/users/CreateUser.vue'
import UpdateUser from '@/components/pages/users/UpdateUser.vue'
import ViewUsers from '@/components/pages/users/ViewUsers.vue'

import NotFound from '@/components/pages/other/NotFound.vue'

const routes = [
    {
        path: '/',
        redirect: '/portfolio'
    },
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
                path: '/images/:id', 
                component: ImageDetails, 
                meta: { 
                    title: 'Image details',
                    isAuthenticated: true
                }
            },
            { 
                path: '/images/sell/:id', 
                component: SellImage, 
                meta: { 
                    title: 'Sell image',
                    isAuthenticated: true
                }
            },
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
                component: ViewImages, 
                meta: { 
                    title: 'Image',
                    isAuthenticated: true
                }
            },
            { 
                path: '/privacy', 
                component: viewPrivacy, 
                meta: { 
                    title: 'Privacy',
                    isAuthenticated: true
                }
            },
            { 
                path: '/portfolio', 
                component: ViewPortfolio, 
                meta: { 
                    title: 'Portfolio',
                    isAuthenticated: true
                }
            },
            { 
                path: '/settings', 
                component: ViewSettings, 
                meta: { 
                    title: 'Settings',
                    isAuthenticated: true
                }
            },
            { 
                path: '/users/create', 
                component: CreateUser, 
                meta: { 
                    title: 'Create user',
                    isAuthenticated: true,
                    roles: ['Admin']
                }
            },
            { 
                path: '/users/update/:id', 
                component: UpdateUser, 
                meta: { 
                    title: 'Update user',
                    isAuthenticated: true,
                    roles: ['Admin']
                }
            },
            { 
                path: '/users', 
                component: ViewUsers, 
                meta: { 
                    title: 'User',
                    isAuthenticated: true,
                    roles: ['Admin']
                }
            },
            {
                path: ':pathMatch(.*)*',
                component: NotFound,
                meta: { 
                    title: 'Not found',
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
            errorHandlingStore.errorMessage = 'You need to be logged in to perform this action.'
            return { 
                path: '/auth/login', 
                query: { 
                    _refresh: Date.now() 
                } 
            }
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
                errorHandlingStore.errorMessage = `Your account doesn't have the right role to perform this action.`
                return { 
                    path: '/auth/login', 
                    query: { 
                        _refresh: Date.now() 
                    } 
                }
            }
        }
    }
    
    if (to.meta.title) {
        document.title = to.meta.title
    }
})

export default router
