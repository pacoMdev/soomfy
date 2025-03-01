<template>
  <div class="row">
    <router-link
        :to="'/products/detalle/' + producto.id"
        v-for="producto in productos"
        :key="producto.id"
        class="producto col-6 col-md-4 col-lg-3"
    >
      <div class="contenido-producto">
        <div class="d-flex justify-content-end w-100">
          <i
              v-if="!producto.esFavorito"
              @click.stop.prevent="gestorFavoritos(producto.id)"
              class="fa-regular fa-heart"
              style="color: #e66565;"
          ></i>
          <i
              v-else
              @click.stop.prevent="gestorFavoritos(producto.id)"
              class="fa-solid fa-heart"
              style="color: #e66565;"
          ></i>
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
import {defineProps, inject, onMounted, ref} from 'vue';
import axios from 'axios';


const props = defineProps({
  productos: Array, // Recibe la lista de products
  actualizarFavoritos: Function, // Recibe función para actualizar favoritos en la vista padre
  esVistaFavoritos: Boolean,
  actualizarProductos: Function,
});


const responsiveOptions = ref([
  { breakpoint: '991px', numVisible: 4 },
  { breakpoint: '767px', numVisible: 3 },
  { breakpoint: '575px', numVisible: 1 }
]);

const favoritos = ref([
]);

// Sincronizamos la lista de favoritos al montar el componente
onMounted(async () => {
  if (props.actualizarFavoritos) {
    try {
      const favoritosData = await props.actualizarFavoritos();
      // Asegúrate de que favoritosData sea un array
      favoritos.value = Array.isArray(favoritosData) ? favoritosData : [];
    } catch (error) {
      console.error('Error al cargar favoritos:', error);
      favoritos.value = [];
    }
  }
});


const swal = inject('$swal')


const gestorFavoritos = async(productId) => {
  console.log("Este es el id del producto que has clicado: " + productId);
  const respuesta = await axios.post(`/api/gestor-favoritos/${productId}`);
  if (respuesta.data.productoAgregado) {
    // swal({
//       icon: 'success',
//       title: 'Producto agregado a favoritos',
//       showConfirmButton: false,
//       timer: 1500,
//     });
  } 
  if (props.esVistaFavoritos){
      await props.actualizarFavoritos();
  } else {
      await props.actualizarProductos();
  }
}
// Función para obtener products desde la API
function getImages(resized_image) {
    return Object.values(resized_image || {}); // retorna el objeto de la imagen sin UUID
}
</script>

<style scoped>
.producto {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
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
</style>
