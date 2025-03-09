<template>
  <div class="container my-8">
    <div class="row g-5">
      <div class="col-md-3 contenedor-filtro">
        <form @submit.prevent="aplicarFiltro">
          <!-- Categoría -->
          <label for="categoria" class="h1-p">Categoría</label>
          <select v-model="categoriaSeleccionada" id="categoria" name="categoria">
            <option value="">Todas</option>
            <option v-for="categoria in categoryList" :key="categoria.id" :value="categoria.name">
              {{ categoria.name }}
            </option>
          </select>

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
          <select v-model="buscarEstado" id="estado" name="estado">
            <option value="">Todos</option>
            <option v-for="estado in estadoList" :key="estado.id" :value="estado.name">
              {{ estado.name }}
            </option>
          </select>

          <!-- Precio -->
          <!-- Precio MIN -->
          <label for="precio" class="h1-p">Precio minimo</label>
          <input v-model="buscarPrecioMin" type="number" id="precioMin" name="precioMin" placeholder="€">

          <!-- Precio MAX -->
          <label for="precio" class="h1-p">Precio máximo</label>
          <input v-model="buscarPrecioMax" type="number" id="precioMax" name="precioMax" placeholder="€">

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
          <button type="submit">Aplicar Filtros</button>
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
import axios from 'axios';
import { useRoute } from "vue-router";
import ProductoNew from "@/components/ProductoNew.vue";
import useProducts from '@/composables/products.js';
import router from "@/routes/index.js";
import useCategories from "@/composables/categories.js";


const route = useRoute();
const { products, getProducts, estadoList, getEstadoList } = useProducts();
const {categoryList, getCategoryList} = useCategories()

const fetchProducts = async () => {
  try {
    console.log('Parámetros de consulta actuales:', route.query);

    await getProducts(
        1, // Página inicial
        route.query.search_category || '', // Categoría
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
        route.query.order_price || '' // Precio para ordenar
    );

  } catch (error) {
    console.error('Error al obtener productos:', error);
  }
};


const latitude = ref(41.38740000);
const longitude = ref(2.16860000);

const categoriaSeleccionada = ref('');
const buscarTitulo = ref('');
const buscarEstado = ref('');
const buscarPrecioMin = ref();
const buscarPrecioMax = ref();

// Ubicacion
const buscarRadio = ref(0);

// Ordenar
const ordenarPrecio = ref('');
const ordenarFecha = ref('');



const aplicarFiltro = async () => {
  try {
    // Crear objeto con los filtros usando el prefijo search_
    const filtros = {
      search_category: categoriaSeleccionada.value || '',
      search_title: buscarTitulo.value || '',
      search_estado: buscarEstado.value || '',
      order_column: 'created_at',
      order_direction: ordenarFecha.value || 'desc',
      order_price: ordenarPrecio.value || '',
      min_price: buscarPrecioMin.value || '',
      max_price: buscarPrecioMax.value || '',
    };

    // Solo agregamos los parámetros de ubicación si hay un radio seleccionado
    if (buscarRadio.value) {
      filtros.search_latitude = latitude.value;
      filtros.search_longitude = longitude.value;
      filtros.search_radius = buscarRadio.value;
    }

    console.log("Filtros a enviar:", filtros);

    // Eliminar los filtros vacíos (solo claves con valores no vacíos)
    const filtrosLimpios = Object.fromEntries(
        Object.entries(filtros).filter(([_, value]) =>
            value !== '' && value !== null && value !== undefined
        )
    );

    console.log("Filtros limpios:", filtrosLimpios);

    // Actualizar la URL con los filtros limpios
    await router.push({
      query: filtrosLimpios,
    });

    // Llamar a getProducts con los parámetros ordenados manualmente
    await getProducts(
        1, // Page
        filtrosLimpios.search_category || '', // Categoría
        '', // search_id vacío (no lo estás usando actualmente, pero se requiere por posición)
        filtrosLimpios.search_title || '', // Título
        filtrosLimpios.min_price || '', // Precio mínimo
        filtrosLimpios.max_price || '', // Precio máximo
        filtrosLimpios.search_estado || '', // Estado
        filtrosLimpios.search_location || '', // Ubicación
        '', // search_content vacío (no lo estás usando actualmente)
        '', // search_global vacío (no lo estás usando actualmente)
        'created_at', // Columna para ordenar
        filtrosLimpios.order_direction || '',
        filtrosLimpios.order_price || '' ,// Precio ordenado
        filtrosLimpios.search_latitude || '', // Pasar la latitud
        filtrosLimpios.search_longitude || '', // Pasar la longitud
        filtrosLimpios.search_radius || ''

  );

  } catch (error) {
    console.error('Error al aplicar filtro:', error);
  }
};


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
  // Creamos el mapa en el div que tiene como id "map"
  const map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: latitude.value, lng: longitude.value },
    zoom: 13,
  });

  // Crear un marcador en el mapa
  const marker = new google.maps.Marker({
    // Coordenadas iniciales donde se colocara el marcador
    position: { lat: latitude.value, lng: longitude.value },
    map, // Indica que mapa
    draggable: true, // Permitimos que se pueda arrastrar por e l mapa
  });

  // Evento: al arrastrar el marcador
  marker.addListener("dragend", (event) => {
    const { lat, lng } = event.latLng.toJSON();
    latitude.value = lat;
    longitude.value = lng;
    console.log("Nueva posición:", latitude.value, longitude.value);
  });

  // Evento: clic en el mapa
  map.addListener("click", (event) => {
    const { lat, lng } = event.latLng.toJSON();
    latitude.value = lat;
    longitude.value = lng;
    marker.setPosition({ lat, lng });
    console.log("Mapa clickeado en:", latitude.value, longitude.value);
  });


  fetchProducts();
  getCategoryList();
  getEstadoList();

});
</script>

<style scoped>

.google-map {
  height: 300px; /* Define la altura del mapa */
  width: 100%; /* Define el ancho del mapa */
  border: 1px solid #ccc; /* Opcional: bordes del mapa */
  border-radius: 8px; /* Opcional: bordes redondeados */
}

.contenedor-filtro {
  background-color: white;
  border: 1px solid var(--primary-color);
  border-radius: 10px;
  padding: 20px;
  box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
}

.contenedor-filtro h2 {
  margin-bottom: 15px;
}

.contenedor-filtro form {
  display: flex;
  flex-direction: column;
}

.contenedor-filtro label {
  margin-top: 10px;
}

.contenedor-filtro input,
.contenedor-filtro select {
  padding: 8px;
  margin-top: 5px;
  border: 1px solid var(--primary-color);
  border-radius: 5px;
  font-size: 1rem;
}

.contenedor-filtro button {
  margin-top: 15px;
  padding: 10px;
  background-color: var(--primary-color);
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 1rem;
}

.contenedor-filtro button:hover {
  background-color: var(--primary-color);
}


</style>




