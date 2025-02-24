<template>
  <div class="contenedor-favoritos">
    <h2>Mis Favoritos</h2>
    <ProductoNew :productos="favoritos" :actualizarFavoritos="obtenerFavoritos" />
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import axios from 'axios';
import ProductoNew from '@/components/ProductoNew.vue';

const favoritos = ref([]);

onMounted(() => {
  obtenerFavoritos();
});

const obtenerFavoritos = async () => {
  try {
    const respuesta = await axios.get('/api/get-favorite-products');
    favoritos.value = respuesta.data;
  } catch (error) {
    console.error("Error al obtener favoritos:", error);
  }
};
</script>
