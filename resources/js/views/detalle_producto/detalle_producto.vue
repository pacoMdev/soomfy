<template lang="">
    <div class="container py-5">
        <div class="row">
            <div class="col-md-6 col-lg-8 col-xl-8 container-left">
                <div>
                    <h1>Carrusel imagenes</h1>
                    <div class="card">
                        <Galleria :value="getImage(post.resized_image)" :responsiveOptions="responsiveOptions" :numVisible="4" :thumbnailsPosition="position" containerStyle="max-width: 640px">
                            <template #item="slotProps">
                                <img :src="slotProps.item.original_url" :alt="post.title" style="width: 100%; display: block" />
                            </template>
                        </Galleria>
                    </div>
                </div>
                <div class="container-user-products">
                    <h2>Articulos del usuario</h2>
                    <div class="card">
                    <Carousel :value="products" :numVisible="3" :numScroll="1" :responsiveOptions="responsiveOptions" circular :autoplayInterval="3000">
                        <template #item="slotProps">
                            <div class="border border-surface-200 dark:border-surface-700 rounded m-2  p-4">
                                <div class="mb-4">
                                    <div class="relative mx-auto">
                                        <img :src="'https://primefaces.org/cdn/primevue/images/product/' + slotProps.data.image" :alt="slotProps.data.name" class="w-full rounded" />
                                        <Tag :value="slotProps.data.inventoryStatus" :severity="getSeverity(slotProps.data.inventoryStatus)" class="absolute" style="left:5px; top: 5px"/>
                                    </div>
                                </div>
                                <div class="mb-4 font-medium">{{ slotProps.data.name }}</div>
                                <div class="flex justify-between items-center">
                                    <div class="mt-0 font-semibold text-xl">${{ slotProps.data.price }}</div>
                                    <span>
                                        <Button icon="pi pi-heart" severity="secondary" outlined />
                                        <Button icon="pi pi-shopping-cart" class="ml-2"/>
                                    </span>
                                </div>
                            </div>
                        </template>
                    </Carousel>
                    </div>
                </div>
                <div class="container-related-products">
                    <h2>Articulos relacionados</h2>
                    <div class="card">
                    <Carousel :value="products" :numVisible="3" :numScroll="1" :responsiveOptions="responsiveOptions" circular :autoplayInterval="3000">
                        <template #item="slotProps">
                            <div class="border border-surface-200 dark:border-surface-700 rounded m-2  p-4">
                                <div class="mb-4">
                                    <div class="relative mx-auto">
                                        <img :src="'https://primefaces.org/cdn/primevue/images/product/' + slotProps.data.image" :alt="slotProps.data.name" class="w-full rounded" />
                                        <Tag :value="slotProps.data.inventoryStatus" :severity="getSeverity(slotProps.data.inventoryStatus)" class="absolute" style="left:5px; top: 5px"/>
                                    </div>
                                </div>
                                <div class="mb-4 font-medium">{{ slotProps.data.name }}</div>
                                <div class="flex justify-between items-center">
                                    <div class="mt-0 font-semibold text-xl">${{ slotProps.data.price }}</div>
                                    <span>
                                        <Button icon="pi pi-heart" severity="secondary" outlined />
                                        <Button icon="pi pi-shopping-cart" class="ml-2"/>
                                    </span>
                                </div>
                            </div>
                        </template>
                    </Carousel>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-4 col-xl-4 container-right">
                <div v-if="post" class="container-info-prod p-5">
                    <h3>{{ post.price }} €</h3>
                    <h4>{{ post.title }}</h4>
                    <h4>{{ post.content }}</h4>
                    <h5>{{ post.state }}</h5>
                    <div class="container-categories d-flex gap-2">
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
                <div class="security-info">
                    <!-- imagen de informacion se seguridad -->
                    <img src="" alt="">
                    <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Nostrum earum cumque voluptatibus beatae provident culpa eum, unde laborum pariatur, dicta adipisci exercitationem error dolores explicabo alias totam. Dolorem, dolore atque.
                    </p>
                </div>
                <div class="info-profile-post">
                    <img src="" alt="">
                    <div>
                        <h3>Username</h3>
                        <Rating v-model="value" :stars="5" :value="3" />
                        <div>
                            <img src="" alt="">
                            <p>Calle falsa 123</p>
                        </div>
                    </div>
                </div>
    
            </div>
        </div>
    </div>
</template>
<script setup>

    import { ref, onMounted } from 'vue';
    import Rating from 'primevue/rating';
    import Carousel from 'primevue/carousel';


    onMounted(() => {
        getPost();

    })


    const path = window.location.pathname; // obtiene url
    const segments = path.split('/');
    const id = segments.pop();  // obtiene ultimo elemento (id)

    const post = ref([]);


    const getPost = async () => {
        const respuesta = await axios.get('/api/get-post/'+id); // Asegúrate de que esta URL sea la correcta
        post.value = respuesta.data; // Guardamos los datos en productos
        console.log(respuesta.data);
    };
    
    function getImage(resized_image){
        return Object.values(resized_image || {});
    }

    const images = ref();
    const position = ref('bottom');
    const positionOptions = ref([
        {
            label: 'Bottom',
            value: 'bottom'
        },
        {
            label: 'Top',
            value: 'top'
        },
        {
            label: 'Left',
            value: 'left'
        },
        {
            label: 'Right',
            value: 'right'
        }
    ]);
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

    import '../../../../resources/css/theme.css'
</script>
<style scoped>
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
</style>