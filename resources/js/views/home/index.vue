<template>
  <div class="separacion-general">
    <div class="d-none d-md-block">
      <div class="separacion-general-web">
        <div class="fondoBienvenida d-flex justify-content-center">
          <div class="d-flex flex-column align-items-center justify-content-center contenidoBienvenida text-center">
            <h1 class="tamañoH1">¡Compra y vende artículos de segunda mano sin salir de casa!</h1>
            <h2 class="pb-6 m-0">¡Todo a solo un clic de distancia!</h2>
            <SearchBar class="search-bar-home" />
          </div>
        </div>
        <div class="centrar-categories">
          <div class="categories">
            <div class="d-flex flex-column text-center gap-2" @click="redirectAll()">
              <img src="/images/others.webp" alt="Todas">
              <p>Todas</p>
            </div>
            <div v-for="category in categories" :key="category.id">
              <div class="d-flex flex-column text-center gap-2" @click="redirectCategory(category)">
                <img :src="category.original_image" :alt="category.original_image" />
                <p>{{ category.name }}</p>
              </div>
            </div>
          </div>
        </div>

        <div class="centrar-productos">
          <div class="productos">
            <ProductoNew :productos="productos" :actualizarProductos="obtenerProductos"/>
          </div>
        </div>
        <div class="d-flex justify-content-center">
          <button
              class="secondary-button-2"
              @click="cargarMasProductos"
              :disabled="cargando"
          >
            {{ cargando ? 'Cargando...' : '¡Ver mas!' }}
          </button>
        </div>

        <div class="apartado-mensaje">
          <h1>Dale una nueva vida a lo que ya no usas!</h1>
          <h2 class="m-0">¡¡Compra, vende y haz la diferencia!</h2>

          <img src="images/cascos-decoracion.webp" alt="Cascos" class="cascos" />
          <img src="images/pelota-decoracion.webp" alt="Pelota" class="pelota" />
          <img src="images/reloj-decoracion.webp" alt="Reloj" class="reloj" />
          <img src="images/zapato-decoracion.webp" alt="Zapatilla" class="zapato" />
        </div>
      </div>
    </div>
  </div>
  <div class="d-flex d-md-none">
    <div class="my-5">
      <div class="contenedor-informativo">
      </div>
      <div class="d-flex justify-content-center my-6">
        <!-- falta ajustar el responsive -->
        <div class="productos">
          <ProductoNew :productos="productos" :actualizarProductos="obtenerProductos"/>
        </div>
      </div>
      <div class="d-flex justify-content-center">
        <button
            class="secondary-button-2"
            @click="cargarMasProductos"
            :disabled="cargando"
        >
          {{ cargando ? 'Cargando...' : '¡Ver mas!' }}
        </button>
      </div>
    </div>
  </div>
</template>



<script setup>
import '../../../css/home/home.css';
import { onMounted, ref } from 'vue';
import ProductoNew from '@/components/ProductoNew.vue';
import router from "@/routes/index.js";
import SearchBar from "@/components/SearchBar.vue";

import useProducts from '@/composables/products.js';
const { products, getProducts } = useProducts();
import useCategories from '@/composables/categories.js';
const { categories, getCategories } = useCategories();


const productos = ref([]);
const paginaActual = ref(1);
const cargando = ref(false);

onMounted(async () => {
  await obtenerProductos(1); // Carga la primera pagina
  getCategories(); // Carga las categorias
  console.log("Productos después de cargar la primera página:", productos.value); // Depuración

});

const obtenerProductos = async (page = 1) => {
  cargando.value = true;
  try {
    // Llamar a getProducts con los parámetros necesarios
    await getProducts(
      page, // Página actual
      '', // search_category
      '', // search_id
      '', // search_title
      '', // min_price
      '', // max_price
      '', // search_estado
      '', // search_location
      '', // search_content
      '', // search_global
      'created_at', // order_column
      'desc', // order_direction
      '', // order_price
      '', // search_latitude
      '', // search_longitude
      '', // search_radius
      8 // paginate
    );

    // Verifica si products.value.data existe y es un array
    const productosCargados = products.value.data;

    if (!Array.isArray(productosCargados)) {
      throw new TypeError("La respuesta de productos no es un array.");
    }

    // Si es la primera página, almacenamos los productos
    if (page === 1) {
      productos.value = productosCargados;
    } else {
      // Si pasamos de página, agregamos los siguientes productos
      productos.value = [...productos.value, ...productosCargados];
    }

    console.log("Productos cargados:", productos.value); // Depuración

  } catch (error) {
    console.error("Error al obtener productos:", error);
  } finally {
    cargando.value = false;
  }
};

const cargarMasProductos = async () => {
  if (cargando.value) return;

  paginaActual.value++; // Incrementa la página actual
  await obtenerProductos(paginaActual.value); // Carga la siguiente página
};

// Cada vez que hagas clic a una categoria, lo que hara es llamar a esta funcion pasandole la categoria
const redirectCategory = (category) => {
  // Y te redirigira al apartado de categorias, filtrado por categoria
  router.push({
    name: 'public.products',
    // Y agregara searc_category en la url
    query: {
      search_category: category.name
    }
  });
};

const redirectAll = () => {
  // Y te redirigira al apartado de categorias, filtrado por categoria
  router.push({
    name: 'public.products',
  });
};

// Removed unused responsiveOptions variable

</script>

<style scoped>
.buscadorProductos {
  height: 40px;
  border-radius: 25px;
  border: 1px solid var(--primary-color);
  padding-left: 25px;
  width: 75%;
}
</style>