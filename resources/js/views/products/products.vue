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
                    class="secondary-button-2 h-100 mt-1" 
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
import { ref, onMounted, watch, nextTick } from 'vue';
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

// Banderas de control para el proceso de filtrado
const isInitialLoad = ref(true);      // Controla si estamos en la carga inicial de la página
const disableWatchers = ref(true);    // Deshabilita los watchers durante la configuración inicial
const processingFilters = ref(false); // Evita múltiples procesos de filtrado simultáneos
const isProcessing = ref(false);      // Evita múltiples operaciones de obtención de productos a la vez

/**
 * Variables reactivas principales para almacenar datos de los productos y categorías
 */
const route = useRoute();
const { products, getProducts, estadoList, getEstadoList } = useProducts();
const { categoryList, getCategoryList } = useCategories();

/**
 * Importación de funciones del composable de Mapas
 * 
 * - latitude/longitude: Coordenadas actuales
 * - partialAddress: Dirección ingresada por el usuario
 * - searchRadius: Radio de búsqueda actual
 * - initMap: Inicializa el mapa en un elemento HTML
 * - getGeoPartialAddress: Convierte dirección en coordenadas
 * - drawCircle: Dibuja círculo en el mapa
 * - updateMapPosition: Actualiza posición del mapa y marcador
 * - removeCircle: Elimina círculos del mapa
 * - recreateMap: Recrea el mapa completamente (solución radical)
 * - updateCircleOnly: Actualiza solo el círculo sin recrear mapa
 */
const { 
  latitude, 
  longitude, 
  partialAddress,
  searchRadius,
  initMap, 
  getGeoPartialAddress,
  drawCircle,
  updateMapPosition,
  removeCircle,
  recreateMap,
  updateCircleOnly
} = useMaps();

/**
 * Coordenadas por defecto (Barcelona)
 * Utilizadas cuando no se puede obtener la ubicación del usuario
 */
const BARCELONA_LATITUDE = 41.3874;
const BARCELONA_LONGITUDE = 2.1686;

/**
 * Obtiene productos del servidor según los parámetros de filtrado
 * 
 * Esta función es la responsable de leer los parámetros de la URL
 * y solicitar los productos filtrados al backend.
 */
const fetchProducts = async () => {
  // Evitamos llamadas múltiples si ya hay un proceso en curso
  if (isProcessing.value) return;
  
  try {
    isProcessing.value = true; // Marcamos que estamos procesando
    console.log('Obteniendo productos con parámetros URL:', route.query);
    
    // Obtenemos la categoría de la URL
    const categoryFromUrl = route.query.search_category || '';
    
    // Si hay una categoría en la URL y tenemos la lista de categorías cargada,
    // actualizamos la selección en la UI para mantener sincronizados URL y UI
    if (categoryFromUrl && categoryList.value) {
      setSelectedCategory(categoryFromUrl);
    }

    // Llamamos a la API para obtener productos filtrados usando los parámetros de la URL
    await getProducts(
      1, // Página inicial
      categoryFromUrl, // Usamos la categoría directamente de la URL
      route.query.search_id || '',
      route.query.search_title || '',
      route.query.min_price || '',
      route.query.max_price || '',
      route.query.search_estado || '',
      route.query.search_location || '',
      route.query.search_content || '',
      route.query.search_global || '',
      route.query.order_column || 'created_at',
      route.query.order_direction || 'desc',
      route.query.order_price || '',
      100
    );
  } catch (error) {
    console.error('Error al obtener productos:', error);
  } finally {
    isProcessing.value = false; // Marcamos que hemos terminado
  }
};

/**
 * Establece la categoría seleccionada en la UI basada en el nombre
 * 
 * Esta función busca el objeto de categoría que coincide con el nombre
 * proporcionado y lo establece como seleccionado en el componente MultiSelect.
 * 
 * @param {string} categoryName - Nombre de la categoría a seleccionar
 */
const setSelectedCategory = (categoryName) => {
  // Buscamos el objeto de categoría cuyo nombre coincide con el parámetro
  const category = categoryList.value.find(cat => 
    cat.name.toLowerCase() === categoryName.toLowerCase()
  );
  
  // Si encontramos la categoría, la establecemos como seleccionada
  if (category) {
    console.log('Estableciendo categoría seleccionada:', category.name);
    categoriaSeleccionada.value = [category]; // MultiSelect espera un array
  }
};

/**
 * Variables reactivas para los filtros de búsqueda
 */
const categoriaSeleccionada = ref([]);  // Categorías seleccionadas (multiselect)
const buscarEstado = ref([]);           // Estados seleccionados (multiselect)
const buscarTitulo = ref('');           // Texto para buscar en título
const buscarPrecioMin = ref(null);      // Precio mínimo
const buscarPrecioMax = ref(null);      // Precio máximo
const buscarRadio = ref(20000);             // Radio de búsqueda en metros
const ordenarPrecio = ref('');          // Ordenamiento por precio (asc/desc)
const ordenarFecha = ref('');           // Ordenamiento por fecha (asc/desc)
const searchAddress = ref('');          // Dirección de búsqueda
const isSearching = ref(false);         // Estado de búsqueda en progreso

/**
 * Obtiene la ubicación del usuario autenticado
 * 
 * Si el usuario está autenticado y tiene coordenadas guardadas,
 * las utiliza. De lo contrario, usa Barcelona como ubicación predeterminada.
 * 
 * @returns {Promise<boolean>} True si se obtuvo coordenadas del usuario, False en caso contrario
 */
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
    // Si la autenticacion falla o no hay coordenadas, usamos las de Barcelona
    latitude.value = BARCELONA_LATITUDE;
    longitude.value = BARCELONA_LONGITUDE;
    return false;
  } catch (error) {
    console.error('Error al obtener la ubicación del usuario:', error);
    // Si hay un error, usamos las coordenadas de Barcelona
    latitude.value = BARCELONA_LATITUDE;
    longitude.value = BARCELONA_LONGITUDE;
    return false;
  }
};

/**
 * Fuerza la actualización del círculo cuando cambia el radio
 * 
 * SOLUCIÓN DEFINITIVA: Recrea completamente el mapa cada vez que
 * se cambia el radio, evitando problemas de círculos persistentes.
 * 
 * @param {number|string} radiusValue - Valor del radio en metros
 */
const forceCircleRefresh = async (radiusValue) => {
  try {
    const radius = parseInt(radiusValue) || 0;
    console.log(`🔄 Cambiando radio a: ${radius}m`);
    
    // Actualizamos el valor del modelo
    buscarRadio.value = radius;
    
    // SOLUCIÓN DEFINITIVA: Recrear SIEMPRE el mapa completo en cada cambio
    // Esto garantiza que no queden restos de círculos antiguos
    console.log("🔥 Recreando mapa completo para asegurar limpieza total");
    await recreateMap("map", radius);
    
    console.log(`✅ Mapa recreado exitosamente con radio: ${radius}m`);
    
    // Aplicamos filtro después de recrear el mapa
    setTimeout(() => {
      aplicarFiltro();
    }, 200);
    
  } catch (error) {
    console.error("Error en forceCircleRefresh:", error);
  }
};

/**
 * Maneja el cambio de selección en el dropdown de radio
 * 
 * Se activa cuando el usuario selecciona un nuevo valor de radio,
 * actualiza el modelo y aplica el cambio en el mapa.
 * 
 * @param {Event} event - Evento de cambio del elemento select
 */
const handleRadioChange = async (event) => {
  try {
    const newValue = event.target.value;
    console.log("📏 Radio cambiado a:", newValue);
    
    // Actualizamos el valor
    buscarRadio.value = newValue;
    
    // Usamos la nueva función más eficiente
    await forceCircleRefresh(newValue);
    
  } catch (error) {
    console.error("Error en handleRadioChange:", error);
  }
};

/**
 * Busca una ubicación basada en el texto ingresado
 * 
 * Geocodifica la dirección ingresada por el usuario y actualiza
 * el mapa con las nuevas coordenadas, manteniendo el radio seleccionado.
 */
const searchLocation = async () => {
  if (!searchAddress.value.trim()) return;
  
  isSearching.value = true;
  
  try {
    partialAddress.value = searchAddress.value;
    await getGeoPartialAddress();
    
    // Usamos updateCircleOnly para mantener consistencia
    await updateCircleOnly(buscarRadio.value);
    
    // Aplicamos el filtro después de actualizar la ubicación
    aplicarFiltro();
  } catch (error) {
    console.error('Error al buscar ubicación:', error);
  } finally {
    isSearching.value = false;
  }
};

/**
 * Inicializa el mapa en el elemento con id "map"
 */
const setupMap = () => {
  initMap("map", latitude.value, longitude.value);
};

/**
 * Aplica todos los filtros seleccionados a la búsqueda de productos
 * 
 * Recopila todos los filtros de la UI y los aplica actualizando la URL
 * y solicitando nuevos productos filtrados al backend.
 */
const aplicarFiltro = async () => {
  // No procedemos si estamos en carga inicial, procesando filtros o watchers deshabilitados
  if (isInitialLoad.value || processingFilters.value || disableWatchers.value) {
    console.log('⏳ Omitiendo aplicación de filtros:', 
      isInitialLoad.value ? 'carga inicial en progreso' : 
      processingFilters.value ? 'ya hay filtros procesándose' : 
      'watchers deshabilitados');
    return;
  }
  
  try {
    processingFilters.value = true; // Marcamos que estamos procesando filtros
    console.log('🔍 Aplicando filtros con categorías:', categoriaSeleccionada.value);
    
    // Convertimos las categorías y estados seleccionados (objetos) a nombres (strings)
    const selectedCategoryNames = Array.isArray(categoriaSeleccionada.value)
      ? categoriaSeleccionada.value.map(category => category.name)
      : [];

    const selectedEstadoNames = Array.isArray(buscarEstado.value)
      ? buscarEstado.value.map(estado => estado.name)
      : [];

    // Construimos el objeto de filtros a aplicar
    const filtros = {
      search_category: selectedCategoryNames.join(','),
      search_estado: selectedEstadoNames.join(','),
      search_title: buscarTitulo.value || '',
      search_global: route.query.search_global || '',
      order_column: 'created_at',
      order_direction: ordenarFecha.value || 'desc',
      order_price: ordenarPrecio.value || '',
      min_price: buscarPrecioMin.value || '',
      max_price: buscarPrecioMax.value || '',
      // Añadimos timestamp para evitar caché
      _t: Date.now()
    };

    // Incluimos datos de ubicación si tenemos coordenadas válidas
    if (latitude.value && longitude.value) {
      filtros.search_latitude = latitude.value;
      filtros.search_longitude = longitude.value;
      filtros.search_radius = buscarRadio.value;
    }

    // Limpiamos los filtros eliminando cualquier valor vacío o nulo
    const filtrosLimpios = Object.fromEntries(
      Object.entries(filtros).filter(([_, value]) => value !== '' && value !== null && value !== undefined)
    );

    console.log("Filtros limpios:", filtrosLimpios);
    console.log('📤 Aplicando filtros a URL:', filtrosLimpios);
    
    // Primero actualizamos la URL con los nuevos parámetros de filtrado
    await router.push({ query: filtrosLimpios });
    
    // Luego obtenemos productos con esos parámetros
    await getProducts(
      1, // Página inicial
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
    console.error('❌ Error al aplicar filtros:', error);
  } finally {
    processingFilters.value = false; // Marcamos que terminamos de procesar filtros
  }
};

/**
 * Limpia todos los filtros y restablece el mapa
 * 
 * Restablece todos los valores de filtro a sus valores predeterminados,
 * actualiza el mapa y carga productos sin filtrar.
 */
const limpiarFiltros = async () => {
  // Limpiamos todos los valores de filtros
  categoriaSeleccionada.value = '';
  buscarTitulo.value = '';
  buscarEstado.value = '';
  buscarTitulo.value = '';
  ordenarFecha.value = '';
  ordenarPrecio.value = '';
  buscarPrecioMin.value = '';
  buscarPrecioMax.value = '';
  buscarRadio.value = 0;
  
  // Establecemos ubicación por defecto
  latitude.value = BARCELONA_LATITUDE;
  longitude.value = BARCELONA_LONGITUDE;
  
  // Esperamos a que se actualicen los valores
  await nextTick();
  
  // Intentamos obtener la ubicación del usuario
  await getUserLocation();
  
  // Actualizamos el mapa sin recrearlo
  await updateMapPosition(latitude.value, longitude.value);
  await updateCircleOnly(0); // Establecemos ubicación exacta
  
  // Aplicamos el filtro con los valores restablecidos
  aplicarFiltro();
};

/**
 * Observadores (watchers) para detectar cambios en los filtros
 * 
 * Este watcher observa cambios en todos los filtros de la UI
 * y llama a aplicarFiltro() cuando detecta cambios, pero solo
 * si no estamos en carga inicial, procesando filtros o con watchers deshabilitados.
 */
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
  () => {
    // Solo aplicamos filtros si los watchers están habilitados,
    // no estamos en carga inicial y no hay filtros procesándose
    if (!disableWatchers.value && !isInitialLoad.value && !processingFilters.value) {
      console.log('👀 Valores de filtros cambiados, aplicando filtros');
      aplicarFiltro();
    } else {
      console.log('👀 Cambio de filtro detectado pero ignorado');
    }
  }
);

/**
 * Observador para cambios en la URL
 * 
 * Este watcher observa cambios en los parámetros de la URL (route.query)
 * y recarga los productos cuando detecta cambios, pero solo
 * si los watchers están habilitados y no hay filtros procesándose.
 */
watch(
  () => route.query,
  async (newQuery, oldQuery) => {
    // Solo recargamos productos si los watchers están habilitados,
    // no hay filtros procesándose y la URL realmente cambió
    if (!disableWatchers.value && !processingFilters.value && 
        JSON.stringify(newQuery) !== JSON.stringify(oldQuery)) {
      console.log('🔄 Parámetros de URL cambiados, obteniendo productos');
      await fetchProducts();
    }
  },
  { deep: true } // Necesario para detectar cambios en objetos anidados
);

/**
 * Configuración inicial cuando se carga la página
 * 
 * Este hook se ejecuta cuando el componente se monta y realiza
 * todas las inicializaciones necesarias en el orden correcto.
 */
onMounted(async () => {
  console.log('🚀 Página de productos montada');
  
  // Mantenemos los watchers deshabilitados durante la configuración
  // para evitar actualizaciones no deseadas
  disableWatchers.value = true;
  isInitialLoad.value = true;
  
  try {
    // Primero cargamos categorías, estados y ubicación del usuario (en paralelo)
    await Promise.all([
      getCategoryList(), // Carga la lista de categorías
      getEstadoList(),   // Carga la lista de estados
      getUserLocation()  // Obtiene la ubicación del usuario
    ]);
    
    // Obtenemos productos basados en los parámetros de URL
    // (esto aplicará el filtro de categoría si está presente)
    await fetchProducts();
    
    // Configuramos el mapa (con un pequeño retraso para asegurar que el DOM esté listo)
    setTimeout(() => {
      setupMap();
      
      // Configuramos el radio del mapa si existe en la URL
      setTimeout(async () => {
        if (route.query.search_radius) {
          buscarRadio.value = route.query.search_radius;
          await updateCircleOnly(parseInt(buscarRadio.value));
        } else {
          buscarRadio.value = 0;
          await updateCircleOnly(0);
        }
      }, 300);
    }, 300);
    
  } catch (error) {
    console.error('❌ Error durante la configuración del componente:', error);
  } finally {
    // Marcamos la carga inicial como completada
    isInitialLoad.value = false;
    
    // Habilitamos los watchers después de un retraso para 
    // asegurar que todo esté estable
    setTimeout(() => {
      disableWatchers.value = false;
      console.log('✅ Watchers habilitados');
    }, 1000);
  }
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




