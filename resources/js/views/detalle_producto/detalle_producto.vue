<template lang="">
    <div class="container py-5">
        <div class="w-100">
            <div class="col-md-6 col-lg-8 col-xl-8 w-100 container-product">
                <!-- INFORMACION DEL PRODUCTO   --------------------------------------------- -->
                <div class="row w-100">
                    <div class="col-md-7 col-lg-7 col-xl-8 container-left">
                        <div class="container-carrusel">
                            <!-- Estaria bien que con hover cambia de imagen -->
                            <Galleria :value="getImage(post.media)" :responsiveOptions="responsiveOptions" :numVisible="6" :indicatorsPosition="position" containerStyle=" max-height: 450px;">
                                <template #item="slotProps" class="height: 100%;">
                                    <img :src="slotProps.item.original_url" :alt="slotProps.item.name" style="width: 45%;" />
                                </template>
                                <template #thumbnail="slotProps">
                                    <img :src="slotProps.item.original_url" :alt="slotProps.item.name" style="width: 10%;" />
                                </template>
                            </Galleria>
                        </div>
                    </div>
                    <div class="col-md-5 col-lg-5 col-xl-4 d-flex flex-column gap-4 container-right">
                        <div v-if="post" class="container-info-prod p-5 d-flex flex-column gap-3">
                            <h3 class="m-0">{{ post.price }} €</h3>
                            <p class="m-0">{{ post.title }}</p>
                            <p class="m-0">{{ post.content }}</p>
                            <p class="m-0">{{ post.state }}</p>
                            <div class="container-categories d-flex gap-2 flex-wrap">
                                <p v-for="category in post.categories" class="cont-category">{{category.name}}</p>
                            </div>
                            <div class="button d-flex gap-3">
                                <Button label="Comprar" variant="outlined" class="w-50" />
                                <Button label="Chat" raised class="w-50" />
                            </div>
                        </div>
                        <div v-else class="container-info-prod p-5">
                            <h3>Cargando precio</h3>
                            <h4>Cargando title</h4>
                            <h4>Cargando description </h4>
                            <h5>Cargando state </h5>
                            <div class="container-categories d-flex gap-2">
                                <p class="cont-category">Cargando categoria</p>
                            </div>
                            <div class="button d-flex gap-3">
                                <Button label="Comprar" variant="outlined" class="w-50" />
                                <Button label="Chat" raised class="w-50" />
                            </div>
                        </div>
                        <div class="d-flex gap-4 justify-content-center align-items-center p-3 container-security-info">
                            <!-- imagen de informacion se seguridad -->
                            <img src="../../../../public/images/trust-icon.png" alt="trust icon">
                            <p class="font-xs">Para vender de segunda mano con éxito: usa fotos claras, describe bien el producto, fija un precio justo, responde rápido y acuerda una entrega segura. ¡Vende fácil y seguro!</p>
                        </div>
                        <div class="d-flex gap-3 align-items-center p-4 info-profile-post">
                            <img src="../../../../public/images/Github.svg" alt="">
                            <div>
                                <h4>Ramon Gallardo</h4>
                                <Rating v-model="value" readonly />

                                <div>
                                    <img src="" alt="">
                                    <p>Molins de Rei, 08750, Barcelona</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                

                <!-- ARTICULOS DEL USUARIO   --------------------------------------------- -->
                <div class="container-user-products">
                    <h2>Articulos del usuario</h2>
                    <div class="card">
                        <Carousel :value="relatedPost.data" :numVisible="5" :numScroll="1" :responsiveOptions="responsiveOptions2" circular :autoplayInterval="3000">
                            <template #item="slotProps">
                                <router-link :to="'/seed_products/' + slotProps.data.id" :key="slotProps.data.id" class="producto col-6 col-md-4 col-lg-3">
                                    <div class="contenido-producto">
                                        <div class="d-flex justify-content-end w-100">
                                            <i class="fa-regular fa-heart justify-content-rigth"></i>
                                        </div>
                                        <Galleria :value="getImage(slotProps.data.resized_image)" :responsiveOptions="responsiveOptions" :numVisible="5" :circular="true" containerStyle="height: 150px; width: 100%; border-radius: 10px;"
                                        :showItemNavigators="true" :showThumbnails="false">
                                            <template #item="slotProps">
                                                <img :src="slotProps.item.original_url" :alt="slotProps.item.title" style="width: auto; height: 150px; display: block; object-fit: cover;" />
                                            </template>
                                        </Galleria>
                                        <p class="h1-p">{{ slotProps.data.price }}€</p>
                                        <p class="h4-p">{{ slotProps.data.title }}</p>
                                        <p class="">{{ slotProps.data.content }}</p>
                                        <p class="tamaño-estadoProducto">{{ slotProps.data.estado }}</p>
                                    </div>
                                </router-link>
                            </template>
                        </Carousel>
                    </div>
                </div>
                
                <!-- ARTICULOS RELACIONADOS   --------------------------------------------- -->
                <div class="container-related-products">
                    <h2>Articulos relacionados</h2>
                    <div class="card">
                        <Carousel :value="relatedPost.data" :numVisible="5" :numScroll="1" :responsiveOptions="responsiveOptions2" circular :autoplayInterval="3000">
                            <template #item="slotProps">
                                <router-link :to="'/seed_products/' + slotProps.data.id" :key="slotProps.data.id" class="producto col-6 col-md-4 col-lg-3">
                                    <div class="contenido-producto">
                                        <div class="d-flex justify-content-end w-100">
                                            <i class="fa-regular fa-heart justify-content-rigth"></i>
                                        </div>
                                        <Galleria :value="getImage(slotProps.data.resized_image)" :responsiveOptions="responsiveOptions" :numVisible="5" :circular="true" containerStyle="height: 150px; width: 200px; border-radius: 10px;"
                                        :showItemNavigators="true" :showThumbnails="false">
                                            <template #item="slotProps">
                                                <img :src="slotProps.item.original_url" :alt="slotProps.item.title" style="width: auto; height: 150px; display: block; object-fit: cover;" />
                                            </template>
                                        </Galleria>
                                        <p class="h1-p">{{ slotProps.data.price }}€</p>
                                        <p class="h4-p">{{ slotProps.data.title }}</p>
                                        <p class="">{{ slotProps.data.content }}</p>
                                        <p class="tamaño-estadoProducto">{{ slotProps.data.estado }}</p>
                                    </div>
                                </router-link>
                            </template>
                        </Carousel>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>

    import { ref, onMounted } from 'vue';
    import Carousel from 'primevue/carousel';
    import Rating from 'primevue/rating';


    import '../../../../resources/css/theme.css'




    onMounted(() => {
        
        getPost();
        getRelatedPosts();
        console.log(relatedPost
        );
        // getUserPosts();
    })


    const path = window.location.pathname; // obtiene url
    const segments = path.split('/');
    const id = segments.pop();  // obtiene ultimo elemento (id)

    const post = ref([]);
    const relatedPost = ref([]);
    const relatedUserPost = ref([]);

    // Valoracion media de los usuarios
    const value = ref(3);


    // Peticiones de API
    const getPost = async () => {
        const respuesta = await axios.get('/api/get-post/'+id); // Asegúrate de que esta URL sea la correcta
        post.value = respuesta.data; // Guardamos los datos en seed_products
        console.log('getPost', respuesta.data);
    };
    const getRelatedPosts = async () => {
        const respuesta = await axios.get('/api/get-posts');
        relatedPost.value = respuesta.data;
        console.log('getRelatedPosts', respuesta.data);
    };
    const getUserPosts = async () => {
        const respuesta = await axios.get('/api/get-post/');
        post.value = respuesta.data;
        console.log('getUserPosts', respuesta.data);
    };

    // Funciones transaccionales
    
    function getImage(resized_image){
        return Object.values(resized_image || {});
    }

    const images = ref();
    const position = ref('left');
    
    const responsiveOptions = ref([
        {
            breakpoint: '1300px',
            numVisible: 4
        },
        {
            breakpoint: '575px',
            numVisible: 1
        }
    ]);
    const products = ref();
const responsiveOptions2 = ref([
    {
        breakpoint: '1400px',
        numVisible: 2,
        numScroll: 1
    },
    {
        breakpoint: '1199px',
        numVisible: 3,
        numScroll: 1
    },
    {
        breakpoint: '767px',
        numVisible: 2,
        numScroll: 1
    },
    {
        breakpoint: '575px',
        numVisible: 1,
        numScroll: 1
    }
]);

const getSeverity = (status) => {
    switch (status) {
        case 'INSTOCK':
            return 'success';

        case 'LOWSTOCK':
            return 'warn';

        case 'OUTOFSTOCK':
            return 'danger';

        default:
            return null;
    }
};

</script>
<style scoped>
    .info-profile-post {
        border: solid 1px grey;
        border-radius: 14px;
    }
    .info-profile-post img{
        background-color: #042A2D;
        border-radius: 100px;
        height: 50px;
    }
    .container-info-prod{
        border: solid 1px grey;
        border-radius: 14px;
        font-size: smaller;
    }

    .cont-category{
        width: auto;
        padding: 5px 25px;
        margin: 0px;
        border-radius: 10px;
        align-items: center;
        background-color: #042A2D;
        color: #C1F9F4;
    }
    /* .container-carrusel {
        height: 500px;
    } */
     .contenido-producto {
        width: 200px;
        height: 350px;
     }
     .container-user-products {
        margin-top: 50px;
     }
     .container-related-products {
        margin-top: 50px;
     }

     .container-security-info {
        border: solid 1px grey;
        border-radius: 14px;

     }
     .container-security-info img {
        height: 50px;
        width: 50px;
        background-color: #C1F9F4;
        border-radius: 100px;
        padding: 5px;
     }
</style>