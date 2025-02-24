<template>
    <h1>Productos Favoritoss</h1>

    <!-- Mostrar la lista de productos favoritos -->
    <!-- <div>
      <ul>
        <li v-for="favorite in favorites" :key="id">
          <div>
            <h3>{{ favorite.title }}</h3>
            <p>{{ favorite.price }}</p>
            <p>{{ favorite.content }}</p>
            <p>{{ favorite.estado }}</p>

          </div>
        </li>
      </ul>
    </div> -->
    <Producto :productos="favorites" :actualizarFavoritos="getFavorites"/>
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
