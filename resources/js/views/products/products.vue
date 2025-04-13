<template>
  <div class="container my-8">
    <div class="row g-5">
      <div class="col-md-3 contenedor-filtro">
        <form @submit.prevent="aplicarFiltro">
          <!-- Categoría -->
          <label for="categoria" class="h1-p">Categoría</label>
          <MultiSelect
            v-model="categoriaSeleccionada"
            :options="categoryList"
            label="name"
            track-by="id"
            optionLabel="name"
            placeholder="Selecciona categorías"
            :close-on-select="false"
            :clear-on-select="false"
            :hide-selected="true"
          />

          <!-- Título -->
          <label for="titulo" class="h1-p">Título</label>
          <input v-model="buscarTitulo" type="text" id="titulo" name="titulo" placeholder="Buscar por título...">

          <!-- Fecha -->
          <label for="fecha" class="h1-p">Fecha</label>
          <select v-model="ordenarFecha" id="ordenarFecha" name="ordenarFecha">
            <option value="">Todas</option>
            <option value="asc">Asc</option>
            <option value="desc">Desc</option>
          </select>

          <!-- Filtro de Estados -->
          <label for="estado" class="h1-p">Estado</label>
          <MultiSelect
            v-model="buscarEstado"
            :options="estadoList"
            label="name"
            track-by="id"
            optionLabel="name"
            placeholder="Selecciona estados"
            :close-on-select="false"
            :clear-on-select="false"
            :hide-selected="true"
          />

          <!-- Precio -->
          <!-- Precio MIN / MAX-->
          <label for="precio" class="h1-p">Precio minimo / maximo</label>
          <div class="d-flex justify-content-between">
            <input v-model="buscarPrecioMin" type="number" id="precioMin" name="precioMin" placeholder="€" class="price-input">
            <input v-model="buscarPrecioMax" type="number" id="precioMax" name="precioMax" placeholder="€" class="price-input">
          </div>

          <!--Ordenar-->
          <label for="ordenarPrecio" class="h1-p">Precio</label>
          <select v-model="ordenarPrecio" id="ordenarPrecio" name="ordenarPrecio">
            <option value="">Ninguno</option>
            <option value="desc">Mayor</option>
            <option value="asc">Menor</option>
          </select>

          <!-- Ubicación -->
          <label for="ordenarUbicacion" class="h1-p">Ubicacion</label>
          <div id="map" class="google-map"></div>

          <label for="radio" class="h1-p">Radio de búsqueda</label>
          <select v-model="buscarRadio" id="radio" name="radio">
            <option value="0">Ubicación exacta</option>
            <option value="1000">A 1 km</option>
            <option value="5000">A 5 km</option>
            <option value="10000">A 10 km</option>
            <option value="20000">A 20 km</option>
          </select>

          <!-- Coordenadas seleccionadas -->
          <div>
            <p>Coordenadas actuales:</p>
            <p><strong>Latitud: </strong>{{ latitude }}</p>
            <p><strong>Longitud: </strong>{{ longitude }}</p>
          </div>

          <!-- Botón de filtrar -->
          <button @click="limpiarFiltros" type="submit">Limpiar Filtros</button>
        </form>
      </div>

      <div class="col-md-9">
        <div v-if="products && products.data && products.data.length > 0">
          <div class="productos">
            <ProductoNew
                :productos="products.data"
                :actualizarProductos="fetchProducts"
            />
          </div>
        </div>
        <div v-else>
          <p>No se encontraron productos.</p>
        </div>
      </div>


    </div>
  </div>
</template>


<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRoute } from "vue-router";
import ProductoNew from "@/components/ProductoNew.vue";
import useProducts from '@/composables/products.js';
import router from "@/routes/index.js";
import useCategories from "@/composables/categories.js";
import { MultiSelect } from 'primevue'; // Import the MultiSelect component
import useMap from '@/composables/useMap';
import './products.css';

const route = useRoute();

const { products, getProducts, estadoList, getEstadoList } = useProducts();

const {categoryList, getCategoryList} = useCategories();

const { latitude, longitude, initMap } = useMap();

const fetchProducts = async () => {
  try {
    console.log('Parámetros de consulta actuales:', route.query);

    await getProducts(
        1, // Página inicial
        route.query.search_category || '', // If no category, show all products
        route.query.search_id || '', // ID (vacío por defecto)
        route.query.search_title || '', // Título de búsqueda
        route.query.min_price || '', // Precio mínimo
        route.query.max_price || '', // Precio máximo
        route.query.search_estado || '', // Estado (vacío por defecto)
        route.query.search_location || '', // Ubicación de búsqueda (vacío por defecto)
        route.query.search_content || '', // Contenido (vacío por defecto)
        route.query.search_global || '', // Global (vacío por defecto)
        route.query.order_column || 'created_at', // Columna de orden, por defecto "created_at"
        route.query.order_direction || 'desc', // Dirección de orden, por defecto "desc"
        route.query.order_price || '', // Precio para ordenar
        100
    );

  } catch (error) {
    console.error('Error al obtener productos:', error);
  }
};


const categoriaSeleccionada = ref([]); // Asegúrate de inicializarlo como un array vacío
const buscarEstado = ref([]); // También inicializa como un array vacío
const buscarTitulo = ref('');
const buscarPrecioMin = ref(null); // Usa null para valores numéricos iniciales
const buscarPrecioMax = ref(null);
const buscarRadio = ref(0);
const ordenarPrecio = ref('');
const ordenarFecha = ref('');


const aplicarFiltro = async () => {
  try {
    // Map selected categories and states to their names
    const selectedCategoryNames = Array.isArray(categoriaSeleccionada.value)
      ? categoriaSeleccionada.value.map(category => category.name)
      : [];

    const selectedEstadoNames = Array.isArray(buscarEstado.value)
      ? buscarEstado.value.map(estado => estado.name)
      : [];

    const filtros = {
      search_category: selectedCategoryNames.join(','), // Join categories with commas
      search_estado: selectedEstadoNames.join(','), // Join states with commas
      search_title: buscarTitulo.value || '',
      search_global: route.query.search_global || '', // Preserve searchGlobal
      order_column: 'created_at',
      order_direction: ordenarFecha.value || 'desc',
      order_price: ordenarPrecio.value || '',
      min_price: buscarPrecioMin.value || '',
      max_price: buscarPrecioMax.value || '',
    };

    if (buscarRadio.value) {
      filtros.search_latitude = latitude.value;
      filtros.search_longitude = longitude.value;
      filtros.search_radius = buscarRadio.value;
    }

    const filtrosLimpios = Object.fromEntries(
      Object.entries(filtros).filter(([_, value]) => value !== '' && value !== null && value !== undefined)
    );

    console.log("Filtros limpios:", filtrosLimpios);

    await router.push({ query: filtrosLimpios });

    await getProducts(
      1,
      filtrosLimpios.search_category || '',
      '',
      filtrosLimpios.search_title || '',
      filtrosLimpios.min_price || '',
      filtrosLimpios.max_price || '',
      filtrosLimpios.search_estado || '',
      filtrosLimpios.search_location || '',
      filtrosLimpios.search_global || '',
      '',
      'created_at',
      filtrosLimpios.order_direction || '',
      filtrosLimpios.order_price || '',
      filtrosLimpios.search_latitude || '',
      filtrosLimpios.search_longitude || '',
      filtrosLimpios.search_radius || '',
      ''
    );

  } catch (error) {
    console.error('Error al aplicar filtro:', error);
  }
};

watch(
  [
    categoriaSeleccionada,
    buscarTitulo,
    buscarEstado,
    ordenarFecha,
    ordenarPrecio,
    buscarPrecioMin,
    buscarPrecioMax,
    buscarRadio,
    latitude,
    longitude
  ],
  aplicarFiltro
);


const limpiarFiltros = async () => {
  categoriaSeleccionada.value = '';
  buscarTitulo.value = '';
  buscarEstado.value = '';
  buscarTitulo.value = '';
  ordenarFecha.value = '';
  ordenarPrecio.value = '';
  latitude.value = 41.38740000;
  longitude.value = 2.16860000;
  buscarPrecioMin.value = '';
  buscarPrecioMax.value = '';
  buscarRadio.value = 0;
};

watch(
    () => route.query,
    async (newQuery, oldQuery) => {
      if (JSON.stringify(newQuery) !== JSON.stringify(oldQuery)) {
        await fetchProducts();
      }
    },
    { deep: true }
);


onMounted(() => {
    initMap('map');
    fetchProducts();
    getCategoryList();
    getEstadoList();
});
</script>




