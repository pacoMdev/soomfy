<template>
  <div class="row gap-5 justify-items-left justify-content-center">
    <router-link
        :to="'/products/detalle/' + producto.id"
        v-for="producto in productosLocales"
        :key="producto.id"
        class="producto col-6 col-md-4 col-lg-3"
    >
      <div class="contenido-producto">
        <div class="d-flex justify-content-end w-100">
          <i
              v-if="!producto.esFavorito"
              @click.stop.prevent="handleFavoriteClick(producto)"
              class="fa-regular fa-heart pb-1"
              style="color: #e66565;"
          ></i>
          <i
              v-else
              @click.stop.prevent="handleFavoriteClick(producto)"
              class="fa-solid fa-heart pb-1"
              style="color: #e66565;"
          ></i>
        </div>
        <Galleria
            :value="getImages(producto.resized_image)"
            :responsiveOptions="responsiveOptions"
            :numVisible="5"
            :circular="true"
            containerStyle="height: 150px; width: 100%; border-radius: 10px;"
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
        <p class="h1-p">{{ formatPrice(producto.price) }}€</p>
        <p class="h4-p">{{ producto.title }}</p>
        <p class="">{{ producto.content }}</p>
        <p class="tamaño-estadoProducto" v-if="producto.estado && producto.estado.name">{{ producto.estado.name }}</p>
      </div>
    </router-link>
  </div>
</template>

<script setup>
import { defineProps, inject, onMounted, ref, watch } from 'vue';
import useProductoNew from '@/composables/ProductoNew';

const props = defineProps({
  productos: Array, // Recibe la lista de products
  actualizarFavoritos: Function, // Recibe función para actualizar favoritos en la vista padre
  esVistaFavoritos: Boolean,
  actualizarProductos: Function,
});

// Creamos una copia local de los productos para manipularla sin afectar a los props originales
const productosLocales = ref([]);

// Observamos los cambios en los productos del prop
watch(() => props.productos, (newProductos) => {
  if (newProductos) {
    productosLocales.value = JSON.parse(JSON.stringify(newProductos));
  }
}, { immediate: true, deep: true });

const {
  loading,
  error,
  favoritos,
  getFavoritos,
  gestorFavoritos,
  getImages,
  formatPrice,
  formatDate
} = useProductoNew();

const responsiveOptions = ref([
  { breakpoint: '991px', numVisible: 4 },
  { breakpoint: '767px', numVisible: 3 },
  { breakpoint: '575px', numVisible: 1 }
]);

const swal = inject('$swal');

// Sincronizamos la lista de favoritos al montar el componente
onMounted(async () => {
  if (props.actualizarFavoritos) {
    try {
      await props.actualizarFavoritos();
    } catch (error) {
      console.error('Error al cargar favoritos:', error);
    }
  }
});

// Función para manejar clics en el botón de favoritos
const handleFavoriteClick = async(producto) => {
  try {
    // Actualizamos inmediatamente la UI invirtiendo el estado actual
    const productoIndex = productosLocales.value.findIndex(p => p.id === producto.id);
    if (productoIndex !== -1) {
      // Invertimos el estado actual (si era favorito lo quitamos, si no lo era lo añadimos)
      productosLocales.value[productoIndex].esFavorito = !productosLocales.value[productoIndex].esFavorito;
    }
    
    // Luego hacemos la llamada al API para guardar el cambio en el servidor
    await gestorFavoritos(producto.id);
    
    // El resto del código permanece igual
    if (props.esVistaFavoritos && props.actualizarFavoritos) {
      await props.actualizarFavoritos();
    } else if (props.actualizarProductos) {
      // No actualizamos productosLocales directamente para evitar parpadeos
      await props.actualizarProductos();
    }
  } catch (error) {
    // Si hay error, revertimos el cambio visual
    const productoIndex = productosLocales.value.findIndex(p => p.id === producto.id);
    if (productoIndex !== -1) {
      // Volvemos al estado original en caso de error
      productosLocales.value[productoIndex].esFavorito = !productosLocales.value[productoIndex].esFavorito;
    }
    console.error('Error al gestionar favoritos:', error);
  }
}
</script>

<style scoped>
.productos {
  width: 70% !important;
}

.producto {
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: auto;
  width: 250px;
  border-radius: 15px;
  border: 1px solid rgba(195, 195, 195, 0.5);
  background-color: #fff;
  padding: 15px;
  transition: all 0.2s ease-in-out;
  cursor: pointer;
  overflow-wrap: break-word;
  word-wrap: break-word;
  word-break: break-word;
  hyphens: auto;
}

/* Asegurarse de que el contenido no sobresalga */
.contenido-producto {
  width: 100%;
  overflow: hidden;
}

/* Limitar altura del texto y añadir elipsis */
.contenido-producto p {
  width: 100%;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  margin-bottom: 0.5rem;
}

/* Para la descripción, permitir múltiples líneas con elipsis */
.contenido-producto p:nth-child(4) {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  white-space: normal;
  height: 2.8em;
  line-height: 1.4;
}

/* Media query para pantallas pequeñas */
@media (max-width: 576px) {
  .producto {
    width: 100%;
  }
}

.producto:hover {
  transform: scale(1.02);
  box-shadow: rgba(0, 0, 0, 0.15) 0px 2px 8px;
}
</style>
