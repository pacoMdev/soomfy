<template>
    <div>
      <h1>Productos Favoritos</h1>
  
      <!-- Mostrar si no hay favoritos -->
      <div v-if="favoritePosts.length === 0">
        <p>No tienes productos favoritos.</p>
      </div>
  
      <!-- Mostrar la lista de productos favoritos -->
      <div v-else>
        <ul>
          <li v-for="post in favoritePosts" :key="post.id">
            <div>
              <h3>{{ post.title }}</h3>
              <p>{{ post.description }}</p>
              <img :src="post.image_url" alt="Imagen del producto" />
            </div>
          </li>
        </ul>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        favoritePosts: [], // Array para almacenar los productos favoritos
      };
    },
    mounted() {
      this.getFavoritePosts(); // Cuando el componente se monta, obtenemos los productos favoritos
    },
    methods: {
      // Método para obtener los productos favoritos desde la API
      getFavoritePosts() {
        axios
          .get('/api/get-favorite-posts')  // Ajusta la URL a la ruta que devuelve los favoritos
          .then((response) => {
            this.favoritePosts = response.data;  // Guardamos los productos favoritos en el estado
          })
          .catch((error) => {
            console.error('Error al obtener los favoritos:', error);
          });
      },
    },
  };
  </script>
  
  <style scoped>
  /* Aquí puedes agregar tus estilos personalizados */
  </style>
  