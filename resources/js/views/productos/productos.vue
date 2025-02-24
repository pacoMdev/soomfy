<template>
  <div>
    <h1>Productos</h1>
    <div v-if="products.length">
      <div v-for="product in products" :key="product.id">
        <p>{{ product.title }}</p>
        <p>{{ product.content }}</p>
      </div>
    </div>
    <div v-else>
      <p>No se encontraron productos.</p>
    </div>
  </div>
</template>


<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';

export default {
  props: ['searchTerm'], // Recibe el searchTerm desde la URL

  setup(props) {
    const products = ref([]);  // Definimos un ref que va a almacenar los productos

    // Función para obtener los productos
    const fetchProducts = async () => {
      // Realiza una solicitud GET a la API para obtener los productos
      const response = await axios.get(`/api/productos/${props.searchTerm || ''}`);
      // Asignamos los datos de los productos a la variable 'products'
      products.value = response.data.data;
    };

    // Cuando el componente se monte, se ejecuta la función fetchProducts
    onMounted(() => {
      fetchProducts();
    });

    // Retornamos la referencia de 'products' para que esté disponible en el template
    return {
      products
    };
  }
};
</script>

