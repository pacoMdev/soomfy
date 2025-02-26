<template>
  <div class="separacion-general">
    <div class="d-none d-md-block">
      <div class="separacion-general-web">
        <div class="fondoBienvenida d-flex justify-content-center">
          <div class="d-flex flex-column align-items-center justify-content-center contenidoBienvenida text-center">
            <h1 class="tamañoH1">¡Compra y vende artículos de segunda mano sin salir de casa!</h1>
            <h2 class="pb-6 m-0">¡Todo a solo un clic de distancia!</h2>
            <form @submit.prevent="buscarProducto" class="d-flex w-100 justify-content-center">
              <!-- Input de búsqueda -->
              <input
                  type="text"
                  class="buscadorProductos"
                  id="searchTerm"
                  v-model="searchTerm"
                  placeholder="Busca productos..." />
              <!-- Botón de búsqueda -->
              <button type="submit" class="secondary-button-2 no-borders-left">
                <i class="fas fa-search"></i> Search
              </button>
            </form>
          </div>
        </div>
        <div class="centrar-categories">
          <div class="categories">
            <div v-for="category in categories" :key="category.id">
              <div class="d-flex flex-column text-center gap-2" @click="toggleCategory(category.id)">
                <img :src="category.category_image" :alt="category.name">
                <p>{{ category.name }}</p>
              </div>

              <!-- Panel de subcategorías -->
              <div v-if="selectedCategory === category.id" class="subcategories-panel">
                <div v-for="subcategory in category.subcategories"
                     :key="subcategory.id"
                     class="d-flex flex-column text-center gap-2">
                  <img src="images/home/categories/telefono.webp" alt="">
                  <p>{{ subcategory.name }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>


        <div class="centrar-productos">
          <div class="productos">
            <Producto :productos="productos" :actualizarFavoritos="obtenerProductos"/>
          </div>
        </div>

        <div class="d-flex justify-content-center">
          <button class="secondary-button-2">¡Ver mas!</button>
        </div>

        <div class="apartado-mensaje">
          <h1>Dale una nueva vida a lo que ya no usas!</h1>
          <h2 class="m-0">¡¡Compra, vende y haz la diferencia!</h2>

          <img src="images/cascos-decoracion.webp" alt="Cascos"class="cascos">
          <img src="images/pelota-decoracion.webp" alt="Pelota" class="pelota">
          <img src="images/reloj-decoracion.webp" alt="Reloj" class="reloj">
          <img src="images/zapato-decoracion.webp" alt="Zapatilla" class="zapato">
        </div>
      </div>
    </div>
  </div>
  <div class="d-block d-md-none">
    <div>
      <div class="contenedor-informativo">

      </div>
      <div class="">
        <!-- falta ajustar el responsive -->
        <div class="productos">
          <Producto />
        </div>
      </div>
      <div class="d-flex justify-content-center">
        <button class="secondary-button-2">¡Ver mas!</button>
      </div>
    </div>
  </div>
</template>



<script setup>
import '../../../css/home/home.css';
import { onMounted, ref } from 'vue';
import axios from 'axios';
import Producto from '@/components/ProductoNew.vue';

const productos = ref([]);
const categories = ref([]);
const selectedCategory = ref(null); // Nuevo

onMounted(() => {
  obtenerProductos();
  loadCategories();
});

const obtenerProductos = async () => {
  try {
    const respuesta = await axios.get('/api/get-products');
    productos.value = respuesta.data.data;
  } catch (error) {
    console.error("Error al obtener products:", error);
  }
};

const loadCategories = async () => {
  try {
    const response = await axios.post('/api/categories')
    categories.value = response.data
  } catch (err) {
    console.error('Error cargando categorías:', err)
  }
}

// Nueva función para manejar la selección de categorías
const toggleCategory = (categoryId) => {
  selectedCategory.value = selectedCategory.value === categoryId ? null : categoryId;
}
</script>


<style scoped>
.subcategories-panel {
  position: absolute;
  background: white;
  padding: 15px;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  z-index: 10;
  margin-top: 10px;
}

</style>