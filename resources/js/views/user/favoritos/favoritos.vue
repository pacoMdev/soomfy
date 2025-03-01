<template>
  <div class="contenedor-favoritos w-100 py-7">
    <div class="centrar-favoritos">
      <h2>Mis Favoritos</h2>
      <ProductoNew
          :esVistaFavoritos="true"
          :productos="favoritos"
          :actualizarFavoritos="obtenerFavoritos" />
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import axios from 'axios';
import ProductoNew from '@/components/ProductoNew.vue';

const favoritos = ref([]);

const obtenerFavoritos = async () => {
  try {
    const respuesta = await axios.get('/api/get-favorite-products');
    favoritos.value = respuesta.data.data;
  } catch (error) {
    console.error("Error al obtener favoritos:", error);
  }
};

onMounted(() => {
  obtenerFavoritos();
});

</script>

<style scoped>
.contenedor-favoritos {
  display: flex;
  justify-content: center;
}

.centrar-favoritos {
  width: 70%;
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  gap: 45px;
}
</style>


