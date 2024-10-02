<template>
  <div class="row d-flex justify-content-center">
    <h1 class="text-center">Iniciar Sesión</h1>
    <form class="col-4 border rounded shadow p-4" @submit.prevent="login">
      <div>
        <label for="email">Correo Electrónico:</label>
        <input class="form-control" v-model="email" type="email" id="email" placeholder="Email" required />
      </div>
      <div>
        <label for="password">Contraseña:</label>
        <input class="form-control" v-model="password" type="password" id="password" placeholder="Password" required />
      </div>
      <button class="btn btn-success mt-5 ms-5 me-5" type="submit">Iniciar Sesión</button>
    </form>
    <div v-if="error" class="alert alert-warning text-center mt-3" role="alert">
      {{ error }}
    </div>
    
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Login',
  data() {
    return {
      email: '',
      password: '',
      error: null
    };
  },
  methods: {
    async login() {
      try {
        // Realizar una solicitud POST al backend para autenticar al usuario
        const response = await axios.post('/api/login', {
          email: this.email,
          password: this.password
        });

        const token = response.data.token;
        const user = response.data.user.name;

        localStorage.setItem('token', token); // Almacenar token
        localStorage.setItem('user', JSON.stringify(user)); // Almacenar datos del usuario

        

        // Redirigir al usuario a la página principal o de tareas
        this.$router.push('/tasks');


      } catch (error) {
        // Mostrar un mensaje de error si la autenticación falla
        this.error = 'Credenciales incorrectas.';
      }
    }
  }
};
</script>

<style scoped>
.error {
  color: red;
}
</style>