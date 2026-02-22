import { createRouter, createWebHistory } from 'vue-router'
import Test1 from '../views/Test1.vue'
import Test2 from '../views/Test2.vue'

const routes = [
  { path: '/', component: Test1 },
  { path: '/test2', component: Test2 }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
