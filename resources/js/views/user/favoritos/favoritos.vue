<template>
  <div class="contenedor-favoritos w-100 py-7">
    <div class="centrar-favoritos flex-column">
      <h2 class="text-center">Mis Favoritos</h2>
      <ProductoNew
          :esVistaFavoritos="true"
          :productos="favoritos"
          :actualizarFavoritos="obtenerFavoritos" />
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import ProductoNew from '@/components/ProductoNew.vue';
import useUsers from '../../../composables/users';
const {
  favoritos,
  obtenerFavoritos,
} = useUsers();

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


