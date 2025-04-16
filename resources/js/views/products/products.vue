<template>
  <div class="container my-8">
    <div class="row g-5">
      <div class="col-md-3">
        <div class="contenedor-filtro">
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
            <div class="form-group">
              <div class="map-container">
                <div class="input-group mb-3">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Buscar ubicación"
                    v-model="searchAddress"
                    @keyup.enter="searchLocation"
                  />
                  <button 
                    class="btn btn-outline-secondary" 
                    type="button"
                    @click="searchLocation"
                    :disabled="isSearching"
                  >
                    <span v-if="isSearching" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span v-else>Buscar</span>
                  </button>
                </div>
                
                <div id="map" class="google-map"></div>
                
                <label for="radio" class="h1-p">Radio de búsqueda</label>
                <select 
                  v-model="buscarRadio" 
                  id="radio" 
                  name="radio" 
                  @change="handleRadioChange($event)"
                >
                  <option value="0">Ubicación exacta</option>
                  <option value="1000">A 1 km</option>
                  <option value="5000">A 5 km</option>
                  <option value="10000">A 10 km</option>
                  <option value="20000">A 20 km</option>
                </select>
              </div>
            </div>

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
import useMaps from "@/composables/Maps";
import { authStore } from "@/store/auth"; // Import auth store
import axios from 'axios';
import './products.css';

const route = useRoute();

const { products, getProducts, estadoList, getEstadoList } = useProducts();
const {categoryList, getCategoryList} = useCategories();

// Use the enhanced Maps composable
const { 
  latitude, 
  longitude, 
  partialAddress,
  searchRadius,
  initMap, 
  getGeoPartialAddress,
  drawCircle,
  updateMapPosition,
  removeCircle
} = useMaps();

// Set Barcelona coordinates as the default location
const BARCELONA_LATITUDE = 41.3874;
const BARCELONA_LONGITUDE = 2.1686;

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
const searchAddress = ref('');
const isSearching = ref(false);

let googleMap = null;
let mapMarker = null;
let mapCircle = null;

// Get authenticated user's location
const getUserLocation = async () => {
  try {
    // If the user is logged in
    if (authStore().isAuthenticated) {
      const response = await axios.get('/api/user');
      if (response.data && response.data.latitude && response.data.longitude) {
        latitude.value = parseFloat(response.data.latitude);
        longitude.value = parseFloat(response.data.longitude);
        
        return true;
      }
    }
    // If authentication fails or no coordinates, use Barcelona's coordinates
    latitude.value = BARCELONA_LATITUDE;
    longitude.value = BARCELONA_LONGITUDE;
    return false;
  } catch (error) {
    console.error('Error al obtener la ubicación del usuario:', error);
    // On error, also use Barcelona's coordinates
    latitude.value = BARCELONA_LATITUDE;
    longitude.value = BARCELONA_LONGITUDE;
    return false;
  }
};

// Update searchLocation function
const searchLocation = async () => {
  if (!searchAddress.value.trim()) return;
  
  isSearching.value = true;
  
  try {
    partialAddress.value = searchAddress.value;
    await getGeoPartialAddress();
    
    // Draw the circle if radius is set
    if (buscarRadio.value > 0) {
      drawCircle(buscarRadio.value);
    }
    
    aplicarFiltro();
  } catch (error) {
    console.error('Error al buscar ubicación:', error);
  } finally {
    isSearching.value = false;
  }
};

// Setup the map
const setupMap = () => {
  initMap("map", latitude.value, longitude.value);
};

// Force complete circle refresh when radius changes
const forceCircleRefresh = (radiusValue) => {
  // Ensure we have a clean integer value
  const radius = parseInt(radiusValue) || 0;
  
  // Primero eliminamos cualquier círculo existente
  removeCircle();
  
  // Añadimos un pequeño retardo antes de dibujar el nuevo círculo
  setTimeout(() => {
    drawCircle(radius);
    
    // Apply filter to update results after drawing the circle
    setTimeout(() => {
      aplicarFiltro();
    }, 100);
  }, 50);
};

// Listen for direct change events on the select element
const handleRadioChange = (event) => {
  const newValue = event.target.value;
  buscarRadio.value = newValue;
  forceCircleRefresh(newValue);
};

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

    if (latitude.value && longitude.value && buscarRadio.value) {
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
  buscarPrecioMin.value = '';
  buscarPrecioMax.value = '';
  buscarRadio.value = 0;
  
  // Set to Barcelona's coordinates by default
  latitude.value = BARCELONA_LATITUDE;
  longitude.value = BARCELONA_LONGITUDE;
  
  // Try to get authenticated user location (will overwrite Barcelona if found)
  await getUserLocation();
  
  // Update map with the new position
  updateMapPosition(latitude.value, longitude.value);
  
  // Remove circle from map when setting radius to 0
  drawCircle(0);
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


onMounted(async () => {
  // First try to get the authenticated user's location, or use Barcelona's coordinates
  await getUserLocation();
  
  fetchProducts();
  getCategoryList();
  getEstadoList();
  
  // Initialize the map after a short delay to ensure the DOM is ready
  setTimeout(() => {
    setupMap();
    
    // If a radius is set in the URL, apply it
    if (route.query.search_radius) {
      buscarRadio.value = route.query.search_radius;
      forceCircleRefresh(parseInt(buscarRadio.value));
    }
  }, 500);
});
</script>

<style scoped>
.contenedor-filtro {
  position: sticky;
  top: 20px; /* Adjust this value as needed to give some spacing from the top */
  max-height: calc(100vh - 40px); /* Viewport height minus top and bottom spacing */
  overflow-y: auto; /* Enable vertical scrolling */
  padding: 15px;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

/* Add smooth scrolling for better user experience */
.contenedor-filtro form {
  scrollbar-width: thin; /* For Firefox */
  scrollbar-color: #cccccc #f5f5f5; /* For Firefox */
}

/* For Webkit browsers like Chrome/Safari */
.contenedor-filtro form::-webkit-scrollbar {
  width: 6px;
}

.contenedor-filtro form::-webkit-scrollbar-track {
  background: #f5f5f5; 
}

.contenedor-filtro form::-webkit-scrollbar-thumb {
  background-color: #cccccc;
  border-radius: 6px;
}

/* You may need to ensure the container height is appropriate */
.container.my-8 {
  min-height: 100vh;
}

/* Make .productos always 100% width in this view */
.productos {
  width: 100% !important;
}

.google-map {
  width: 100%;
  height: 300px;
  margin-bottom: 15px;
  border-radius: 8px;
  border: 1px solid #ccc;
}

.map-hidden {
  display: none;
}

.map-container {
  width: 100%;
  margin-bottom: 15px;
}

.map-controls {
  margin-top: 10px;
}

.spinner-border-sm {
  width: 1rem;
  height: 1rem;
}
</style>




