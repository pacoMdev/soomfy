<!-- SearchProducts.vue -->
<template>
  <form @submit.prevent="buscarProductos" class="d-flex w-100 justify-content-center align-items-center">
    <input
        type="text"
        :class="handleStyle"
        :id="id"
        v-model="searchTerm"
        :placeholder="placeholder"
    />
    <button type="submit" :class="handleButtonStyle">
      <i class="fas fa-search"></i> {{ buttonText }}
    </button>
  </form>
</template>

<script setup>
import {computed, ref} from 'vue';
import {useRoute, useRouter} from 'vue-router';
const route = useRoute();

// Props para hacer el componente más flexible
const props = defineProps({
  id: {
    type: String,
    default: 'searchTerm'
  },
  placeholder: {
    type: String,
    default: 'Buscar productos...'
  },
  buttonText: {
    type: String,
    default: 'Search'
  },
  smallScreen: {
    type: Boolean,
    default: false
  }
});

// Emits para notificar al componente padre
const emit = defineEmits(['search']);

const router = useRouter();
const searchTerm = ref('');

// Si llamamos a SearchBar desde home, cambiaremos algunas cosas
const isHome = computed(() => route.name === 'home');

const handleStyle = computed(() => {
  if (isHome.value) {
    return props.smallScreen ? 'buscadorProductos2' : 'buscadorProductos';
  } else {
    return 'buscadorProductos2';
  }
});

const handleButtonStyle = computed(() => {
  if (isHome.value) {
    return props.smallScreen ? 'primary-button no-borders-left' : 'secondary-button-2';
  } else {
    return 'primary-button no-borders-left';
  }
});

const buscarProductos = () => {
  if (searchTerm.value.trim()) {
    // Emitimos el término de búsqueda
    emit('search', searchTerm.value);

    // Navegamos a la ruta de productos con el parámetro de búsqueda
    router.push({
      name: 'public.products',
      query: {
        search_global: searchTerm.value
      }
    });
  }
};

</script>

<style scoped>
.buscadorProductos {
  border-radius: 25px 0px 0px 25px;
  border: 1px solid var(--primary-color);
  padding-left: 25px;
  width: 75%;
}

.buscadorProductos2 {
  height: 40px;
  border-radius: 25px 0px 0px 25px;
  border: 1px solid var(--primary-color);
  padding-left: 25px;
  width: 45%;
}

@media (max-width: 1125px) {
    .buscadorProductos2 {
        width: 100%;
        max-width: 600px;
    }
}
</style>

