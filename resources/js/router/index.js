// src/router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/home.vue'; // Importa los componentes que usas en las rutas
import Register from '../views/registro.vue';
import Login from '../views/login.vue';
import TaskList from '../views/taskList.vue';
import TaskCreate from '../views/createTask.vue';

// Definir las rutas
const routes = [
  { path: '/', component: Home },  // Ruta para la página principal
  { path: '/login', component: Login },  // Ruta para la página de login
  { path: '/register', component: Register },
  {
    path: '/tasks',
    component: TaskList,
    beforeEnter: (to, from, next) => {
      const isAuthenticated = localStorage.getItem('token');
      if (!isAuthenticated) {
        next('/login');
      } else {
        next();
      }
    }
  },  // Ruta para la lista de tareas
  {
    path: '/taskCreate',
    component: TaskCreate,
    beforeEnter: (to, from, next) => {
      const isAuthenticated = localStorage.getItem('token');
      if (!isAuthenticated) {
        next('/login');
      } else {
        next();
      }
    }
  },
  {
    path: '/tasks/edit/:taskId',
    name: 'EditTask',
    component: TaskCreate,
    props: true,
  },
];

// Crear el router
const router = createRouter({
  history: createWebHistory(), // Usa el historial del navegador
  routes,  // Pasa las rutas al router
});


export default router;