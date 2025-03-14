<!-- SearchProducts.vue -->
<template>
  <form @submit.prevent="buscarProductos" class="d-flex w-100 justify-content-center align-items-center">
    <input
        type="text"
        :class="[
                isHome ? 'buscadorProductos' : 'buscadorProductos2'              ]"
        :id="id"
        v-model="searchTerm"
        :placeholder="placeholder"
    />
    <button type="submit"
            :class="[
                isHome ? 'secondary-button-2' : 'primary-button',
                'no-borders-left'
              ]"
            >
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
  }
});

// Emits para notificar al componente padre
const emit = defineEmits(['search']);

const router = useRouter();
const searchTerm = ref('');

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

// Si llamamos a SearchBar desde home, cambiaremos algunas cosas
const isHome = computed(() => route.name === 'home');

</script>

<style scoped>
/* Falta la etiqueta de apertura <style> */
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
</style>

