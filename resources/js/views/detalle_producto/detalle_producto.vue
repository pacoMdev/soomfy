<template>
    <div class=" flex justify-center w-100 justify-content-center py-5">
        <Breadcrumb :home="home" :model="breadcrumbs" class="bg-transparent">
            <template #item="{ item, props }">
                <router-link v-if="item.route" v-slot="{ href, navigate }" :to="item.route" custom>
                    <a :href="href" v-bind="props.action" @click="navigate">
                        <span :class="[item.icon, 'text-color']" />
                        <span class="text-primary font-semibold">{{ item.label }}</span>
                    </a>
                </router-link>
                <a v-else :href="item.url" :target="item.target" v-bind="props.action">
                    <span class="text-surface-700 dark:text-surface-0">{{ item.label }}</span>
                </a>
            </template>
        </Breadcrumb>
    </div>
    <div class="container">
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
                            <div  v-if="isSelledProduct" class="d-flex justify-content-between align-items-center">
                                <h3 class="m-0">{{ product.price }} €</h3>
                                <Avatar icon="pi pi-shopping-cart" class="mr-2" size="xlarge" shape="circle" style="background-color: white; color: #B51200; border: solid 2px #B51200" />
                            </div>
                            <h2 class="m-0">{{ product.title }}</h2>
                            <p class="m-0 h2-p">{{ product.content }}</p>
                            <p class="m-0 h3-p" v-if="product.estado">{{ product.estado.name }}</p>
                            <div class="container-categories d-flex gap-2 flex-wrap">
                                <Tag v-for="category in product.categories" :key="category.id" severity="secondary" :value="category.name" rounded></Tag>
                            </div>

                            <div class="button d-flex gap-3" v-if="isSelledProduct==false">
                                    <router-link :to="'/app/profile'" class="w-100" v-if="isYourOwnProduct(product.user?.id)">
                                        <Button label="Editar" class="w-100 p-button secondary" rounded />
                                    </router-link>
                                    <div v-else class="d-flex gap-3 w-100">
                                        <router-link v-if="product.toSend===1" :to="'/app/checkout?productId='+product.id" class="w-50">
                                        <Button label="Comprar" variant="outlined" class="w-100" rounded />
                                        </router-link>
                                        <Button @click.prevent="handleChatCreation" label="Chat" class="w-50 p-button secondary" rounded />

                                    </div>
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
                                <Button label="Chat" raised class="w-50"  />
                            </div>
                        </div>
                        <div class="d-flex gap-4 justify-content-center align-items-center p-3 container-security-info">
                            <!-- imagen de informacion se seguridad -->
                            <img src="../../../../public/images/trust-icon.png" alt="trust icon">
                            <p class="font-xs">Para vender de segunda mano con éxito: usa fotos claras, describe bien el producto, fija un precio justo, responde rápido y acuerda una entrega segura. ¡Vende fácil y seguro!</p>
                        </div>
                        <router-link v-if="product.user" :to="'/profile/detalle/'+product.user.id" class="d-flex gap-3 align-items-center p-4 info-profile-product">
                            <img :src="product.user.media[0]?.original_url" alt="" width="50px" height="50px">
                            <div>
                                <!-- {{ product.user && product.user.length > 0 ? product.user[0].id : 'No hay usuario' }} -->
                                <h4>{{ product.user?.name }} {{ product.user.surname1 }}</h4>
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
                        <Carousel :value="relatedPost.data" :numVisible="6" :numScroll="1" :responsiveOptions="responsiveOptions2">
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
                        <Carousel :value="relatedPost.data" :numVisible="5" :numScroll="1" :responsiveOptions="responsiveOptions2">
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

    import { onMounted, watch } from 'vue';
    import { Skeleton, Carousel, Breadcrumb } from 'primevue';
    import '../../../../resources/css/theme.css'
    import useProductDetail from '../../composables/productDetail';
    import useProducts from '../../composables/products';
    import './detalle_producto.css';

    const {
        checkSelledProduct,
        isSelledProduct,
    } = useProducts();
    const { 
        path,
        segments,
        id,
        product,
        relatedPost,
        images,
        address,
        fullAddress,
        position,
        compradorId,
        auth,
        router,
        chatExists,
        home,
        breadcrumbs,
        products,
        responsiveOptions,
        responsiveOptions2,
        getProduct,
        getRelatedProducts,
        getGeoLocation,
        getImage,
        isYourOwnProduct,
        handleChatCreation,
    } = useProductDetail();
    

    watch(product, (standProduct) => {
        if (standProduct?.id) {
            getGeoLocation();
        }
    });

    onMounted(async () => {
      await getProduct();
      console.log("🔎 PRODUCT DETAIL",product.value);
      if (product.value) {
        await checkSelledProduct(product.value.id);
      }
      if (auth.user) {
        console.log(auth.user);
        compradorId.value = auth.user.id;
      }
      console.log("ID DEL USUARIO AUTENTICADO", compradorId.value);
      await getRelatedProducts();
    });
</script>