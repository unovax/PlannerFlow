import { createRouter, createWebHistory } from 'vue-router';
import Orders from './pages/Orders.vue';
import Clients from './pages/Catalogs/Clients.vue';
import Currencies from './pages/Catalogs/Currencies.vue';
import Login from './pages/Auth/Login.vue';
import Register from './pages/Auth/Register.vue';
import Warehouses from './pages/Catalogs/Warehouses.vue';
import Roles from './pages/Catalogs/Roles.vue';

const routes = [
  {
    path: '/',
    redirect: '/pedidos',
  },
  {
    path: '/pedidos',
    component: Orders,
    name: 'Pedidos',
    meta: { requiresAuth: true },
  },
  {
    path: '/clientes',
    component: Clients,
    name: 'Clientes',
    meta: { requiresAuth: true },
  },
  {
    path: '/monedas',
    component: Currencies,
    name: 'Monedas',
    meta: { requiresAuth: true },
  },
  {
    path: '/almacenes',
    component: Warehouses,
    name: 'Almacenes',
    meta: { requiresAuth: true },
  },
  {
    path: '/roles',
    component: Roles,
    name: 'Roles',
    meta: { requiresAuth: true },
  },
  {
    path: '/login',
    component: Login,
    name: 'Iniciar Sesion',
    meta: { requiresGuest: true },
  },
  {
    path: '/register',
    component: Register,
    name: 'Registro',
    meta: { requiresGuest: true }, 
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, _from, next) => {
  const user = JSON.parse(localStorage.getItem('user') || '{}');
  const isAuthenticated = user.token != null;

  if (to.meta.requiresAuth && !isAuthenticated) {
    next('/login');
  } else if (to.meta.requiresGuest && isAuthenticated) {
    next('/clientes');
  } else {
    next();
  }
});

export default router;
