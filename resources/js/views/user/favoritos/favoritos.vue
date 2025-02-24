<template>
  <div class="d-flex align-items-center">
    <div class="centrar-favoritos">
      <Producto :productos="favorites" :actualizarFavoritos="getFavorites"/>
    </div>
  </div>
</template>

<script setup>
import axios from 'axios';
import Producto from '../../../components/ProductoNew.vue';
import { onMounted, ref } from 'vue';

const favorites = ref([]);

onMounted(()=>{
  console.log("sadfsgdh");
    getFavorites()
  }
);



console.log("sadfsgdh");

const getFavorites = async () => {
  try {
      const response = await axios.get('/api/get-favorite-products');
      favorites.value = response.data;
      
  } catch (error) {
      console.error("Error carregant autors:", error);

  }
};

</script>
