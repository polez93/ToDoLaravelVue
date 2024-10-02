/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import axios from 'axios';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router'; // Importa el router que acabas de configurar
import ExampleComponent from './components/ExampleComponent.vue';


/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */
axios.defaults.baseURL = 'http://localhost:8000'; // La URL de tu servidor Laravel

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Configurar Axios para incluir el token en cada solicitud
axios.interceptors.request.use(function (config) {
    const tokenin = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${tokenin}`;
    }
    return config;
  }, function (error) {
    return Promise.reject(error);
  });

let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}
const app = createApp(App);


app.component('example-component', ExampleComponent);
app.component('App', App);

app.use(router); // Agrega el router a la aplicación Vue
app.mount('#app'); // Monta la app en el div con id 'app'
