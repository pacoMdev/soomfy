<template>
    <main>
        <div class="">
            <!-- imagen de perfil -->
            <div class="">
                <div class="upper">
                    <img src="http://localhost:8000/storage/defaultPhotoProfile/bannerIcons.svg" style="height: 200px; width:100%; object-fit:cover;">
                </div>
                <div class="user text-center">
                    <div class="profile">
                        <img src="http://localhost:8000/storage/defaultPhotoProfile/githubProfile.svg" class="rounded-circle" style="width: 80px; background-color: aqua;">
                    </div>
                </div>
            </div>
            <div class="d-flex allign-items-center justify-content-center py-5">
                <div class="container-info-profile">
                    <p>Nombre de usuario</p>
                    <div class="d-flex gap-2 container-rating">
                        <Rating v-model="value" readonly />
                        <p>(8)</p>
                    </div>
                    <p>Aspirante a gran vendedor</p>
                </div>
                <div class="d-flex flex-wrap gap-3 w-100 container container-contador">
                    <button class="secondary-button-2"><i class="pi pi-box" style="font-size: 1.5rem"></i>Compras</button>
                    <button class="secondary-button-2"><i class="pi pi-dollar" style="font-size: 1.5rem"></i>Ventas</button>
                    <button class="secondary-button-2"><i class="pi pi-comment" style="font-size: 1.5rem"></i>Valoaraciones</button>
                </div>
            </div>
            <div>
                <div class="d-flex gap-3 w-100 justify-content-center">
                    <button class="secondary-button-2"><i class="pi pi-box" style="font-size: 1.5rem"></i>Compras</button>
                    <button class="secondary-button-2"><i class="pi pi-dollar" style="font-size: 1.5rem"></i>Ventas</button>
                    <button class="secondary-button-2"><i class="pi pi-comment" style="font-size: 1.5rem"></i>Valoaraciones</button>
                </div>
                <div class="container w-100 d-flex gap-5">
                    <ProductoNew :productos="productos" />
                </div>
            </div>
        </div>
    </main>
</template>


<script setup>
    import { ref, onMounted } from 'vue';
    import axios from 'axios';

    import 'primeicons/primeicons.css';
    import Rating from 'primevue/rating';
    import ProductoNew from '../../../components/ProductoNew.vue';

    const value = ref(4);
    const products = ref([]);

    
    onMounted(()=>{
        console.log('products -->', products);
        obtenerProductos();
    })
    
    // llamada a api
    const obtenerProductos = async () => {
    const respuesta = await axios.get('/api/get-posts'); // Asegúrate de que esta URL sea la correcta
    products.value = respuesta.data.data; // Guardamos los datos en productos
    console.log(respuesta);
};

</script>

<style scoped>
    .container-info-profile{
        width: auto;
        max-height: 350px;
    }
    .container-info-profile fieldset{
        width: 100%;
        max-height: 350px;
        object-fit: contain;
        position: fixed;
        top: 200px;
    }
    .container-info-profile fieldset img{
        width: 50% !important;
        max-height: 350px;
        object-fit: contain;
    }
    .container-info-profile legend{
        height: 50px;
        width: 50px;
        border: solid 1px black;
        top: 10%;
        right: 25%;

    }
    .profile{
        top: 200px;
    }
</style>