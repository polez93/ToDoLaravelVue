<template>
    <div class="d-flex justify-content-center aling-item-center">
        <div class="card card body">
          <h1>Aplicación de Tareas (ToDo)</h1>
          <p>
            Aplicación desarrollada en PhP con el framework de Laravel V 10, y Vue 3.2. con libreria adicionales como Sactum 3.3 lara la validacion de Token, bootstrap 5.3.3 para estilos de diseño, ademas de el uso de Iconos de Fontawesome.
          </p>
          <p>Diseñada por Carlos Eduardo Lopez en prueba de conocimieto</p>
        </div>
    </div>
    <div>
      <div class="content">
        <router-view></router-view>  
      </div>
    </div>
</template>
  
  <script>
  export default {
    name: 'Home',
  };
  </script>