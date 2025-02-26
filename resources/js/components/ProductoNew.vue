<template>
  <div class="row">
    <router-link
        :to="'/seed_products/detalle/' + producto.id"
        v-for="producto in productos"
        :key="producto.id"
        class="producto col-6 col-md-4 col-lg-3"
    >
      <div class="contenido-producto">
        <div class="d-flex justify-content-end w-100">
          <i @click.stop.prevent="gestorFavoritos(producto.id)" class="fa-regular fa-heart"></i>
        </div>
        <Galleria
            :value="getImages(producto.resized_image)"
            :responsiveOptions="responsiveOptions"
            :numVisible="5"
            :circular="true"
            containerStyle="height: 150px; width: 200px; border-radius: 10px;"
            :showItemNavigators="true"
            :showThumbnails="false"
        >
          <template #item="slotProps">
            <img
                :src="slotProps.item.original_url"
                :alt="producto.title"
                style="width: auto; height: 150px; display: block; object-fit: cover;"
            />
          </template>
        </Galleria>
        <p class="h1-p">{{ producto.price }}€</p>
        <p class="h4-p">{{ producto.title }}</p>
        <p class="">{{ producto.content }}</p>
        <p class="tamaño-estadoProducto">{{ producto.estado }}</p>
      </div>
    </router-link>
  </div>
</template>

<script setup>
import { defineProps, ref } from 'vue';
import axios from 'axios';

const props = defineProps({
  productos: Array, // Recibe la lista de seed_products
  actualizarFavoritos: Function, // Recibe función para actualizar favoritos en la vista padre
});

const gestorFavoritos = async (productId) => {
  try {
    await axios.post(`/api/gestor-favoritos/${productId}`);
    await props.actualizarFavoritos(); // Llamamos a la función padre para actualizar la lista
  } catch (error) {
    console.error("Error al gestionar favoritos:", error);
  }
};

const responsiveOptions = ref([
  { breakpoint: '991px', numVisible: 4 },
  { breakpoint: '767px', numVisible: 3 },
  { breakpoint: '575px', numVisible: 1 }
]);

const getImages = (resized_image) => Object.values(resized_image || {});
</script>

<style scoped>
.producto {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 250px;
  height: auto;
  border-radius: 15px;
  border: 1px solid rgba(195, 195, 195, 0.5);
  background-color: #fff;
  padding: 15px;
  transition: all 0.2s ease-in-out;
  cursor: pointer;
}

.producto:hover {
  transform: scale(1.02);
  box-shadow: rgba(0, 0, 0, 0.15) 0px 2px 8px;
}

.icono-favorito {
  font-size: 20px !important;
  padding: 5px 0px;
  cursor: pointer;
}
</style>
