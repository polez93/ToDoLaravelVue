<template>
    <div class="row d-flex justify-content-center">
      <h2 class="text-center">Registro</h2>
      <form class="col-4 border rounded shadow p-4" @submit.prevent="registerUser">
        <div>
          <label for="name">Nombre:</label>
          <input class="form-control" type="text" v-model="name" id="name" required />
        </div>
        <div>
          <label for="email">Correo Electrónico:</label>
          <input class="form-control" type="email" v-model="email" id="email" required />
        </div>
        <div>
          <label for="password">Contraseña:</label>
          <input class="form-control" type="password" v-model="password" id="password" required />
        </div>
        <div>
          <label for="password_confirmation">Confirmar Contraseña:</label>
          <input class="form-control" type="password" v-model="password_confirmation" id="password_confirmation" required />
        </div>
        <button class="btn btn-success mt-3" type="submit">Registrarse</button>
      </form>
  
      <div v-if="error" class="error">{{ error }}</div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        error: ''
      };
    },
    methods: {
      async registerUser() {
        try {
          // Hacer una solicitud POST al backend de Laravel para registrar al usuario
          const response = await axios.post('/api/register', {
            name: this.name,
            email: this.email,
            password: this.password,
            password_confirmation: this.password_confirmation
          });
          
          // Procesar la respuesta exitosa
          console.log('Registro exitoso', response.data);
          this.$router.push('/login'); // Redirecciona al usuario después del registro
  
        } catch (error) {
          // Mostrar los errores de validación o del servidor
          if (error.response) {
            this.error = error.response.data.message || 'Error al registrar el usuario.';
          } else {
            this.error = 'Error desconocido';
          }
        }
      }
    }
  };
  </script>
  
  <style scoped>
  /* Estilos opcionales */
  .error {
    color: red;
    margin-top: 10px;
  }
  </style>
  