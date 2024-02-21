import { createRouter, createWebHistory } from 'vue-router'
import Orders from './pages/Orders.vue'
import Clients from './pages/Clients.vue'

export default createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/pedidos',
      component: Orders,
    },
    {
      path: '/clientes',
      component: Clients,
    },
  ],
})
