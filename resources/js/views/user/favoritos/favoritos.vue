<template>
  <div>
    <h1>Productos Favoritoss</h1>

    <!-- Mostrar la lista de productos favoritos -->
    <div>
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
    </div>
  </div>
</template>

<script setup>
import axios from 'axios';
import { onMounted, ref } from 'vue';

const favorites = ref([]);

onMounted(()=>{
  console.log("sadfsgdh");
    getFavorites()
  }
);


console.log("sadfsgdh");

const getFavorites = async () => {
  console.log("jjjjjjjjjjjjjjjjjjjjjjjjjjj")
  try {
      const response = await axios.get('/api/get-favorite-posts');
      favorites.value = response.data;
      
  } catch (error) {
      console.error("Error carregant autors:", error);
      // toast.add({
      //       severity: "error",
      //       summary: "Error",
      //       detail: error.response?.data?.message || "Hi ha hagut un error al guardar l'autor",
      //       life: 3000,
      //   });

  }
};




</script>
