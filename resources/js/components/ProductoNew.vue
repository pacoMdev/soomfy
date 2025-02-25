<template>

  <router-link :to="'/products/' + producto.id"
    v-for="producto in productos" :key="producto.id" class="producto col-6 col-md-4 col-lg-3">
        <div class="contenido-producto">
            <div class="d-flex justify-content-end w-100">
                <i @click="gestorFavoritos(producto.id)" class="fa-regular fa-heart justify-content-rigth"></i>
            </div>
            <Galleria :value="getImages(producto.resized_image)" :responsiveOptions="responsiveOptions" :numVisible="5" :circular="true" containerStyle="height: 150px; width: 200px; border-radius: 10px;"
            :showItemNavigators="true" :showThumbnails="false">
                <template #item="slotProps">
                    <img :src="slotProps.item.original_url" :alt="producto.title" style="width: auto; height: 150px; display: block; object-fit: cover;" />
                </template>
            </Galleria>
            <p class="h1-p">{{ producto.price }}€</p>
            <p class="h4-p">{{ producto.title }}</p>
            <p class="">{{ producto.content }}</p>
            <p class="tamaño-estadoProducto">{{ producto.estado }}</p>
        </div>
    </router-link>

</template>

<script setup>
import { onMounted, ref, defineProps } from 'vue';

const props = defineProps({
    productos: Array,
    actualizarFavoritos: Function,
})


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

const gestorFavoritos = async(productId) => {
  console.log("Este es el id del producto que has clicado: " + productId);
  const respuesta = await axios.post(`/api/gestor-favoritos/${productId}`);
  console.log(respuesta.data);
  await props.actualizarFavoritos();

}
// Función para obtener products desde la API
function getImages(resized_image) {
    return Object.values(resized_image || {}); // retorna el objeto de la imagen sin UUID
}
</script>

<style scoped>

.producto {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 250px;
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

.producto i {
    font-size: 20px !important;
    padding: 5px 0px;
}

.producto .contenido-producto {
    /* width: 100%; */
    padding: 10px; /* Espaciado interno */
}
</style>