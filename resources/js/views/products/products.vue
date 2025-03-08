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

          <!-- Filtro de Estados -->
          <label for="estado" class="h1-p">Estado</label>
          <select v-model="buscarEstado" id="estado" name="estado">
            <option value="">Todos</option>
            <option v-for="estado in estadoList" :key="estado.id" :value="estado.nombre">
              {{ estado.nombre }}
            </option>
          </select>

          <!-- Fecha -->
          <label for="fecha" class="h1-p">Fecha</label>
          <input v-model="ordenarFecha" type="date" id="fecha" name="fecha">

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
          <label for="ubicacion" class="h1-p">Ubicación</label>
          <input v-model="buscarUbicacion" type="text" id="ubicacion" name="ubicacion" placeholder="Ciudad o código postal">


          <!-- Botón de filtrar -->
          <button type="submit">Aplicar Filtros</button>
          <button @click="limpiarFiltros" type="submit">Limpiar Filtros</button>
        </form>
      </div>

      <div class="col-md-9">
        <div v-if="products.data.length">
          <div class="productos">
            <ProductoNew :productos="products.data" :actualizarProductos="fetchProducts"/>
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
    await getProducts(
        1,
        route.query.search_category || '',
        route.query.search_title || '',
        route.query.search_date || '',
        route.query.search_price || '',
        route.query.order_price || '',
        route.query.order_date || 'created_at',
    );
  } catch (error) {
    console.error('Error al obtener productos:', error);
  }
};


const categoriaSeleccionada = ref('');
const buscarTitulo = ref('');
const buscarEstado = ref('');
const buscarUbicacion = ref('');
const buscarPrecioMin = ref(0);
const buscarPrecioMax = ref(10000);

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
      order_date: ordenarFecha.value || '',
      order_price: ordenarPrecio.value || '',
      search_location: buscarUbicacion.value || '',
      min_price: buscarPrecioMin.value || '',
      max_price: buscarPrecioMax.value || '',
    };

    // Eliminar los filtros vacíos
    const filtrosLimpios = Object.fromEntries(
        Object.entries(filtros).filter(([_, value]) => value !== '')
    );

    await router.push({
      query: filtrosLimpios
    });

    await getProducts(1,
        filtros.search_category,
        '',
        filtros.search_title,
        filtros.search_estado,
        filtros.order_date,
        filtros.order_price,
        '',
        filtros.search_location,
        filtros.min_price,
        filtros.max_price
    );


  } catch (error) {
    console.error('Error al aplicar filtro:', error);
  }
};

const limpiarFiltros = async () => {
  categoriaSeleccionada.value = '';
  buscarTitulo.value = '';
  buscarEstado.value = '';
  buscarUbicacion.value = '';
  buscarTitulo.value = '';
  ordenarFecha.value = '';
  ordenarPrecio.value = '';

  await router.push({ query: {} });
  await fetchProducts();
};

watch(
    () => route.query,
    () => {
      fetchProducts();
    },
    { immediate: true }
);

onMounted(() => {
  fetchProducts();
  getCategoryList();
  getEstadoList();

});
</script>

<style scoped>

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




