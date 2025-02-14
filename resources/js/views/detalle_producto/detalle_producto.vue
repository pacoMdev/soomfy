<template lang="">
    <div>
        <h1>Detalle poducto</h1>
        <div class="container-left">
            <img src="" alt="">
        </div>
        <div class="container-right">
            <div class="container-info-prod">
                <h2>{{ post['data'] }}</h2>
                <h3>Title product</h3>
                <h3>Description product for all </h3>
                <h3>State Product</h3>
                <div class="container-categories">
                    <p>category 1</p>
                    <p>category 2</p>
                    <p>category 3</p>
                </div>
            </div>
            <div>
                <!-- imagen de informacion se seguridad -->
                <img src="" alt="">
                <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Nostrum earum cumque voluptatibus beatae provident culpa eum, unde laborum pariatur, dicta adipisci exercitationem error dolores explicabo alias totam. Dolorem, dolore atque.
                </p>
            </div>
            <div class="info-profile-post">
                <img src="" alt="">
                <div>
                    <h3>Username</h3>
                    <Rating v-model="value" :stars="5" :value="3"/>
                    <div>
                        <img src="" alt="">
                        <p>Calle falsa 123</p>
                    </div>
                    

                </div>
            </div>

        </div>
    </div>
</template>
<script setup>

import Rating from 'primevue/rating';
import Carousel from 'primevue/carousel';

import { ref, onMounted } from 'vue';

onMounted(() => {
    
    let postt = getPost();
})


const path = window.location.pathname; // obtiene url
const segments = path.split('/');
const id = segments.pop();  // obtiene ultimo elemento (id)

const post = ref([]);


const getPost = async () => {
    const respuesta = await axios.get('/api/get-post/'+id); // Asegúrate de que esta URL sea la correcta
    post.value = respuesta.data.data; // Guardamos los datos en productos
    console.log(respuesta);
};


</script>
<style lang="">
    
</style>