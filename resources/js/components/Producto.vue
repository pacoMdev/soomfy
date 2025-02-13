<template>
    
    <router-link :to="'/productos/' + producto.id" 
    v-for="producto in productos" :key="producto.id" class="producto">
        <div class="contenido-producto">
            <div class="d-flex justify-content-end w-100">
                <i class="fa-regular fa-heart justify-content-rigth"></i>
            </div>
            <img src="images/home/productos/producto.webp" alt="">
            <p class="h1-p">{{ producto.price }}€</p>
            <p class="h4-p">{{ producto.description }}</p>
            <p class="tamaño-estadoProducto">{{ producto.estado }}</p>
        </div>
    </router-link>

</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

// Estado reactivo
const productos = ref([]);

// Función para obtener productos desde la API
const obtenerProductos = async () => {
    const respuesta = await axios.get('/api/posts'); // Asegúrate de que esta URL sea la correcta
    productos.value = respuesta.data.data; // Guardamos los datos en productos
};

// Ejecutar la función cuando el componente se monte
onMounted(obtenerProductos);
</script>

<style scoped>

.producto {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 20%;
    height: auto; /* Que el tamaño se ajuste al contenido */
    border-radius: 15px;
    border: 1px solid rgba(195, 195, 195, 0.5);
    background-color: #fff; /* Fondo blanco */
    padding: 15px;
    transition: all .2s ease-in-out;
    cursor: pointer;
}

.producto:hover {
    transform: scale(1.02);
    box-shadow: rgba(0, 0, 0, 0.15) 0px 2px 8px;
}

.producto img {
    width: 100%;
    height: auto;
    border-radius: 10px;
    object-fit: cover;
}

.producto i {
    font-size: 20px !important;
    padding: 5px 0px;
}

.producto .contenido-producto {
    width: 100%;
    padding: 10px; /* Espaciado interno */
}
</style>