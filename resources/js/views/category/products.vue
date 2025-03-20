<template>
    <div class="container">
        <h2 class="text-center my-4">Category Products</h2>
        <div class="row mb-2">
            <div v-for="product in products?.data" :key="product.id" class="col-md-6">
                <div
                    class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                    <div class="col-auto d-none d-lg-block d-md-block">
                        <img :src="getImageUrl(product)" class="img-fluid"/>
                    </div>
                    <div class="col p-4 d-flex flex-column position-static">
                        <strong v-for="category in product.categories" class="d-inline-block mb-2 text-primary">
                            {{ category.name }}
                        </strong>
                        <h3 class="mb-0">{{ product.title }}</h3>
                        <div class="mb-1 text-muted">Nov 12</div>
                        <p class="card-text mb-auto">{{ product.content.substring(0, 90) + "..." }}</p>
                        <router-link :to="{ name: 'public-product.details', params: { id: product.id } }"
                                     class="stretched-link">Continue reading
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from 'axios';
import {ref, onMounted} from 'vue'
import {useRoute} from "vue-router";

const route = useRoute();
const products = ref();

function getImageUrl(product) {
    let image
    if (product.resized_image.length > 0) {
        image = product.resized_image
    } else {
        image = product.original_image
    }
    return new URL(image, import.meta.url).href
}

onMounted(() => {
    axios.get('/api/get-category-products/' + route.params.id).then(({data}) => {
      product.value = data;
    })
})
</script>
