<template>
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <a class="navbar-brand display-4" href="#">ToDo</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav row w-100">
          <div class="col-10">
            <li>
              <router-link class="btn text-primary" to="/"><i class="fa-solid fa-house"></i> Home</router-link>
            </li>
            <li v-if="isAuthenticated">
              <a class="btn text-primary" href="#" @click="toggleDropdown"><i class="fa-solid fa-list-check"></i> Tareas</a>
              <ul v-if="dropdownVisible" class="dropdown-menu">
                <li><router-link class="btn text-primary" to="/tasks"><i class="fa-solid fa-list-ul"></i> Listar Tareas</router-link></li>
                <li><router-link class="btn text-primary" to="/taskCreate"><i class="fa-solid fa-plus"></i> Crear Tarea</router-link></li>
              </ul>
            </li>
          </div>
          <div v-if="!isAuthenticated" class="col-2">
            <li><router-link class="btn text-primary" to="/register"><i class="fa-solid fa-user-plus"></i> Register</router-link></li>
            <li><router-link class="btn text-primary" to="/login"><i class="fa-solid fa-right-to-bracket"></i> Login</router-link></li>
          </div>
          <div v-if="isAuthenticated" class="col-2">
            <li class="d-flex">
              <span><i class="fa-solid fa-user"></i> {{ userName }}!</span>
              <a class="btn btn-secondary ms-2" @click="logout"><i class="fa-solid fa-arrow-right-from-bracket"></i>Salir</a>
            </li>

          </div>



        </ul>
      </div>
    </div>
  </nav>


  <div id="app" class="pt-5">
    <router-view></router-view> <!-- Aquí se renderizarán las rutas -->
  </div>
</template>


<script>
export default {
  name: 'App',
  data() {
    return {
      dropdownVisible: false,
      isAuthenticated: false,
      userName: '',
    };
  },
  created() {
    // Verificamos si el usuario está autenticado
    this.checkAuth();
  },
  mounted() {
    this.checkAuth();
  },
  watch: {
    '$route'(to, from) {
      this.checkAuth(); // Vuelve a verificar si el usuario está autenticado cada vez que cambia la ruta
    }
  },
  methods: {
    checkAuth() {
      const token = localStorage.getItem('token'); // Obtener token de localStorage
      const user = JSON.parse(localStorage.getItem('user')); // Obtener usuario
      if (token && user) {
        this.isAuthenticated = true;
        this.userName = user; // Obtener el nombre del usuario
      } 
    },
    toggleDropdown() {
      this.dropdownVisible = !this.dropdownVisible;
    },
    logout() {
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      this.isAuthenticated = false;
      this.$router.push('/login'); // Redirigir a la página de login
    }
  }
};
</script>

<style scoped>
/* Estilos para el menú */
ul {
  list-style: none;
  padding: 0;
  text-decoration: none;
}

li {
  display: inline-block;
  margin-right: 20px;
}

.dropdown {
  position: relative;
}

.dropdown-menu {
  display: block;
  position: absolute;
  list-style: none;
  padding: 10px;
  background-color: #f0f0f0;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}
</style>