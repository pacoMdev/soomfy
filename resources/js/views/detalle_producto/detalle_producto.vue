<template>
    <div class="container py-5">
        <div class="w-100">
            <div class="col-md-6 col-lg-8 col-xl-8 w-100 container-product">
                <!-- INFORMACION DEL PRODUCTO   --------------------------------------------- -->
                <div class="row w-100">
                    <div class="col-md-7 col-lg-7 col-xl-8 container-left">
                        <div class="container-carrusel">
                            <!-- Estaria bien que con hover cambia de imagen -->
                          <Galleria
                              :value="getImage(product.resized_image)"
                              :responsiveOptions="responsiveOptions"
                              :numVisible="6"
                              :indicatorsPosition="position"
                              containerStyle="max-height: 625px;">
                            <template #item="slotProps">
                              <img
                                  v-if="slotProps.item && slotProps.item.original_url"
                                  :src="slotProps.item.original_url"
                                  :alt="slotProps.item.name || product.title"
                                  class="gallery-image"
                              />
                            </template>
                            <template #thumbnail="slotProps">
                              <img
                                  v-if="slotProps.item && slotProps.item.original_url"
                                  :src="slotProps.item.original_url"
                                  :alt="slotProps.item.name || product.title"
                                  class="gallery-thumbnail"
                              />
                            </template>
                          </Galleria>

                        </div>
                    </div>
                    <div class="col-md-5 col-lg-5 col-xl-4 d-flex flex-column gap-4 container-right">
                        <div v-if="product" class="container-info-prod p-5 d-flex flex-column gap-3">
                            <h3 class="m-0">{{ product.price }} €</h3>
                            <h2 class="m-0">{{ product.title }}</h2>
                            <p class="m-0 h2-p">{{ product.content }}</p>
                            <p class="m-0 h3-p" v-if="product.estado">{{ product.estado.name }}</p>
                          <div class="container-categories d-flex gap-2 flex-wrap">
                              <p v-if="product.category" class="cont-category h3-p">{{ product.category.name }}</p>
                            </div>
                            <div class="button d-flex gap-3 ">
                                <Button v-if="product.toSend==1" label="Comprar" variant="outlined" class="w-50" />
                                <Button label="Chat" raised class="w-50" />
                            </div>
                        </div>
                        <div v-else class="container-info-prod p-5">
                            <Skeleton width="10rem" class="mb-2"></Skeleton>
                            <Skeleton width="15rem" class="mb-2"></Skeleton>
                            <Skeleton width="14rem" class="mb-2"></Skeleton>
                            <Skeleton width="8rem" class="mb-2"></Skeleton>
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
                        <router-link v-if="product.user" :to="'/profile/detalle/'+product.user.id" class="d-flex gap-3 align-items-center p-4 info-profile-product">
                            <img src="../../../../public/images/Github.svg" alt="">
                            <div>
                                <!-- {{ product.user && product.user.length > 0 ? product.user[0].id : 'No hay usuario' }} -->
                                <h4>{{ product.user[0]?.name }} {{ product.user[0].surname1 }}</h4>
                                <div>
                                    <p>{{ fullAddress?.results[0].formatted_address }}</p>
                                </div>
                            </div>
                        </router-link>
                        <div v-else class="d-flex gap-3 align-items-center p-4 info-profile-product">
                            <Skeleton shape="circle" size="4rem" class="mb-2"></Skeleton>
                            <div>
                                <Skeleton width="10rem" class="mb-2"></Skeleton>
                                <div>
                                    <Skeleton width="15rem" class="mb-2"></Skeleton>
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
                                <router-link :to="'/products/detalle/' + slotProps.data.id" :key="slotProps.data.id" target="_blank" class="producto col-6 col-md-4 col-lg-3">
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
                                      <p class="" v-if="slotProps.data.content">{{ slotProps.data.content }}</p>
                                      <p class="tamaño-estadoProducto">{{ slotProps.data.estado.name }}</p>
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
                                <router-link :to="'/products/detalle/' + slotProps.data.id" :key="slotProps.data.id" target="_blank" class="producto col-6 col-md-4 col-lg-3">
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
                                        <p class="tamaño-estadoProducto">{{ slotProps.data.estado.name }}</p>
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

    import { ref, onMounted, watch } from 'vue';
    import { Skeleton, Rating, Carousel } from 'primevue';
    import '../../../../resources/css/theme.css'

    const path = window.location.pathname; // obtiene url
    const segments = path.split('/');
    const id = segments.pop();  // obtiene ultimo elemento (id)
    const product = ref([]);
    const relatedPost = ref([]);
    const images = ref();
    const address = ref('null');
    const fullAddress = ref(null);
    const position = ref('left');

    onMounted(async () => {
        await getProduct();
        getRelatedProducts();
    })

    watch(product, (standProduct) => {
        if (standProduct?.id) {
            getGeoLocation();
        }
    });
    


    // Peticiones de API
    const getProduct = async () => {
        console.log(id);
        const respuesta = await axios.get('/api/products/'+id); // Asegúrate de que esta URL sea la correcta
        product.value = respuesta.data.data || {}; // Guardamos los datos en productos
        console.log('PRODUCT:', respuesta.data.data);
    };
    const getRelatedProducts = async () => {
        const respuesta = await axios.get('/api/products');
        relatedPost.value = respuesta.data;
        console.log('RELATED PRODUCTS:', respuesta.data);
    };


    const getGeoLocation = async () => {
        try{
            const latitude = product.value.user[0].latitude;
            const longitude = product.value.user[0].longitude;

            const respuesta = await axios.get('/api/geoLocation', {
                params: {latitude, longitude}
            });
            fullAddress.value = respuesta.data || {};
            console.log('FULLADDRESS -->', fullAddress);

        }catch(err){
            console.log('Falla en API: ', err);
        }
    };

    function getImage(resized_image){
        return Object.values(resized_image || {});
    }

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


</script>
<style scoped>
    .info-profile-product {
        border: solid 1px grey;
        border-radius: 14px;
    }
    .info-profile-product img{
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
     .info-profile-product{
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

    .gallery-image,
    .gallery-thumbnail {
      width: 100%; /* Ajusta al contenedor */
      height: 510px; /* Altura consistente */
      object-fit: cover; /* Ajusta la imagen conservando su proporción */
      border-radius: 8px; /* Opcional, para esquinas redondeadas */
      display: block; /* Asegura el comportamiento como bloque */
    }

    /* Miniaturas en la galería */
    .gallery-thumbnail {
      width: 80px;
      height: 80px;
      object-fit: cover;
    }


</style>