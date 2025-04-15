<template>
  <div class="d-none d-md-block py-8">
    <div class="separacion-general-web">
      <div class="fondoBienvenida d-flex justify-content-center">
        <div class="d-flex flex-column align-items-center justify-content-center contenidoBienvenida text-center">
          <h1 class="tamañoH1">¡Compra y vende artículos de segunda mano sin salir de casa!</h1>
          <h2 class="pb-6 m-0">¡Todo a solo un clic de distancia!</h2>
          <SearchBar class="search-bar-home" />
        </div>
      </div>
      <div class="centrar-categories">
        <Carousel 
          :value="[{name: 'Todas', img: '/images/others.webp'}, ...categories]" 
          :numVisible="7" 
          :numScroll="7" 
          :responsiveOptions="responsiveOptions"
          class="categories-carousel"
        >
          <template #item="slotProps">
            <div class="categories d-flex flex-column text-center gap-2"
            @click="slotProps.data.name === 'Todas' ? redirectAll() : redirectCategory(slotProps.data)"
            >
                <img 
                  :src="slotProps.data.name === 'Todas' ? slotProps.data.img : slotProps.data.original_image" 
                  :alt="slotProps.data.name"
                />
                <p>{{ slotProps.data.name }}</p>
            </div>
          </template>
        </Carousel>
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
  <div class="d-flex d-md-none">
    <div class="my-5">
      <div class="contenedor-informativo">
        <Carousel 
          :value="productos" 
          :numVisible="1" 
          :numScroll="1" 
          class="productos-carousel"
        >
          <template #item="slotProps">
            <ProductoNew 
              :productos="[slotProps.data]" 
              :actualizarProductos="obtenerProductos" 
            />
          </template>
        </Carousel>
      </div>
      <div class="d-flex justify-content-center my-6">
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
import Carousel from 'primevue/carousel';

/**
 * Opciones de responsive para el carousel de categorías
 * Define cómo se comportará el carousel en diferentes tamaños de pantalla
 */
const responsiveOptions = ref([
  {
    breakpoint: '1412px',
    numVisible: 7,
    numScroll: 7
  },
  {
    breakpoint: '1375px',
    numVisible: 6,
    numScroll: 6
  },
  {
    breakpoint: '1100px',
    numVisible: 5,
    numScroll: 5
  },
  {
    breakpoint: '977px',
    numVisible: 4,
    numScroll: 4
  }
]);

import useProducts from '@/composables/products.js';
const { products, getProducts } = useProducts();
import useCategories from '@/composables/categories.js';
const { categories, getCategories } = useCategories();

/**
 * Referencias reactivas para el manejo de productos
 * productos: Array de productos a mostrar
 * paginaActual: Número de página actual para la paginación
 * cargando: Estado de carga para mostrar/ocultar indicadores
 */
const productos = ref([]);
const paginaActual = ref(1);
const cargando = ref(false);

/**
 * Inicializa la página cargando productos y categorías
 */
onMounted(async () => {
  await obtenerProductos(1); // Carga la primera pagina
  getCategories(); // Carga las categorias
  console.log("Productos después de cargar la primera página:", productos.value); // Depuración
});

/**
 * Obtiene los productos de la API según la página especificada
 * @param {number} page - Número de página a cargar
 */
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

/**
 * Carga la siguiente página de productos cuando el usuario solicita ver más
 */
const cargarMasProductos = async () => {
  if (cargando.value) return;

  paginaActual.value++; // Incrementa la página actual
  await obtenerProductos(paginaActual.value); // Carga la siguiente página
};

/**
 * Redirige al usuario a la vista de productos filtrada por categoría
 * @param {Object} category - Objeto categoría seleccionada
 */
const redirectCategory = (category) => {
  // Y te redirigira al apartado de categorias, filtrado por categoria
  router.push({
    name: 'public.products',
    // Y agregara search_category en la url
    query: {
      search_category: category.name === 'Todas' ? '' : category.name // If "Todas", clear the category filter
    }
  });
};

/**
 * Redirige al usuario a la vista de productos sin filtros de categoría
 */
const redirectAll = () => {
  // Y te redirigira al apartado de categorias, filtrado por categoria
  router.push({
    name: 'public.products',
    query: {
      search_category: '' // Clear the category filter to show all products
    }
  });
};

</script>
