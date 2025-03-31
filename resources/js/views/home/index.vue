<template>
  <div class="separacion-general">
    <div class="d-none d-md-block">
      <div class="separacion-general-web">
        <div class="fondoBienvenida d-flex justify-content-center">
          <div class="d-flex flex-column align-items-center justify-content-center contenidoBienvenida text-center">
            <h1 class="tamañoH1">¡Compra y vende artículos de segunda mano sin salir de casa!</h1>
            <h2 class="pb-6 m-0">¡Todo a solo un clic de distancia!</h2>
            <SearchBar />
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
                <img :src="category.original_image" :alt="category.original_image">
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

          <img src="images/cascos-decoracion.webp" alt="Cascos"class="cascos">
          <img src="images/pelota-decoracion.webp" alt="Pelota" class="pelota">
          <img src="images/reloj-decoracion.webp" alt="Reloj" class="reloj">
          <img src="images/zapato-decoracion.webp" alt="Zapatilla" class="zapato">
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
import axios from 'axios';
import ProductoNew from '@/components/ProductoNew.vue';
import { useRouter } from 'vue-router';
import router from "@/routes/index.js";
import SearchBar from "@/components/SearchBar.vue";


const productos = ref([]);
const categories = ref([]);
const selectedCategory = ref(null);
const paginaActual = ref(1);
const cargando = ref(false);

onMounted(() => {
  obtenerProductos(1); // Carga la primera pagina
  loadCategories();
});

const obtenerProductos = async (page = 1) => {
  cargando.value = true;
  try {
    const respuesta = await axios.get(`/api/products?page=${page}`);

    // Si es la primera pagina lo almacenamos en la varaible
    if (page === 1) {
      productos.value = respuesta.data.data;
    } else { // Si pasamos de pagina, agregamos los siguientes 8 productos
      productos.value = [...productos.value, ...respuesta.data.data];
    }

    return respuesta.data;
  } catch (error) {
    console.error("Error al obtener products:", error);
    return null;
  } finally {
    cargando.value = false;
  }
};

const cargarMasProductos = async () => {
  if (cargando.value) return;

  paginaActual.value++; // Incrementa la página actual
  await obtenerProductos(paginaActual.value); // Carga la siguiente página
};

const loadCategories = async () => {
  try {
    const response = await axios.get('/api/categories')
    categories.value = response.data.data
  } catch (err) {
    console.error('Error cargando categorías:', err)
  }
}

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

const responsiveOptions = ref([
  {
    breakpoint: '991px',
    numVisible: 4
  },
  {
    breakpoint: '767px',
    numVisible: 3
  },
  {
    breakpoint: '575px',
    numVisible: 1
  }
]);

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