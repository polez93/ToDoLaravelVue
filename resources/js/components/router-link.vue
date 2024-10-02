<template>
    <div>
      <router-link to="/">Home</router-link>
      <router-link to="/login">Login</router-link>
      <router-link to="/tasks">Tasks</router-link>
      
    </div>
    <div class="content">
      <router-view></router-view>  <!-- Aquí se renderizan las vistas correspondientes a cada ruta -->
    </div>
  </template>