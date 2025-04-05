<template>
  <main>
    <div class="">      
      <div class="d-flex flex-wrap gap-5 align-items-center justify-content-center py-5 p-5">
        <div v-if="user.email" class="container-info d-flex gap-5">
          <img v-if="user.media?.[0]" :src="user.media[0]['original_url']" :alt="user.media[0]['original_url']">
          <img v-else :src="user.avatar"  alt="default-image">

          <div class="container-info-profile d-flex flex-column gap-1">
            <h4 class="m-0">{{ user.name }} {{ user.surname1 }}</h4>
            <div class="d-flex gap-2 container-rating">
              <Rating v-model="mediaRating" readonly />
              <p>({{ reviews.length }})</p>
            </div>
            <Tag icon="pi pi-map-marker" severity="secondary" :value="fullAddress?.results && fullAddress.results.length > 0 ? fullAddress.results[0].formatted_address
  : 'Direccion no disponible 🚫'" rounded></Tag>
          </div>
        </div>

        <!-- Botones de acciones -->
        
      </div>

      <div>
        <div class="d-flex gap-3 w-100 justify-content-center flex-wrap">
          <div class="d-flex gap-3">
            <Button @click="fetchProducts(`getAllToSell`, user.id, 'activeProducts')" label="Mis Productos" icon="pi pi-shop" :badge="activeProducts.length" rounded />
            <Button @click="fetchProducts(`getValorations`, user.id, 'reviews')" label="Valoraciones" icon="pi pi-comment" :badge="reviews.length" rounded />
          </div>
          <div class="d-flex gap-3">
            <Button @click="fetchProducts(`getPurchase`, user.id, 'purchases')" label="Compras" icon="pi pi-box" :badge="purchases.length" rounded />
            <Button @click="fetchProducts(`getSales`, user.id, 'sales')" label="Ventas" icon="pi pi-dollar" :badge="sales.length" rounded />
          </div>
        </div>

        <div class="container w-100 d-flex gap-5">
          <div v-if="loading">Cargando...</div>
          <div v-if="error" style="color: red;">{{ error }}</div>

           <!-- COMPRAS -------------------------------------------------------------------------------------------- -->
          <div v-if="selectedTab === 'purchases'" class="w-100">
            <div v-if="purchases.length > 0">
              <h4>Historial de Compras</h4>
              <HistoricInfo :historic="purchases" />
            </div>
            <div v-else class="container-else">
              <h1>Parece que no hay compras</h1>
              <img src="/images/undraw_file-search_cbur.svg" alt="Imagen compras" class="image-else">
          </div>
          </div>
          <!-- VENTAS -------------------------------------------------------------------------------------------- -->
          <div v-if="selectedTab === 'sales'" class="w-100">
            <div v-if="sales.length > 0">
              <h4>Historial de Ventas</h4>
              <HistoricInfo :historic="sales" />
            </div>
            <div v-else class="container-else">
              <h1>Parece que no hay ventas</h1>
              <img src="/images/undraw_file-search_cbur.svg" alt="Imagen ventas" class="image-else">
          </div>
          </div>
          <!-- PRODUCTOS -------------------------------------------------------------------------------------------- -->
          <div v-if="selectedTab === 'activeProducts'">
            <div v-if="activeProducts.length > 0">
              <h4>Mis Productos</h4>
              <ProductoNew :productos="activeProducts" :actualizarProductos="fetchProducts" />
            </div>
            <div v-else class="container-else">
              <h1>Parece que aun no hay productos</h1>
              <img src="/images/undraw_file-search_cbur.svg" alt="Imagen productos" class="image-else">
          </div>
          </div>
          <!-- OPINIONES -------------------------------------------------------------------------------------------- -->
          <div v-if="selectedTab === 'reviews'" class="w-100">
            <div v-if="reviews.length > 0">
              <h4>Valoraciones</h4>
              <ValorationInfo :reviews="reviews" />
            </div>
            <div v-else class="container-else">
              <h1>Parece que no hay valoraciones</h1>
              <img src="/images/undraw_public-discussion_693m.svg" alt="Imagen valoracion" class="image-else">
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>


<script setup>
import { onMounted, watch } from 'vue';
import Rating from 'primevue/rating';
import ProductoNew from '../../components/ProductoNew.vue';
import HistoricInfo from '@/components/historicInfo.vue';
import ValorationInfo from '../../components/valorationInfo.vue';
import useProfile from '../../composables/profile';

const {
  mediaRating,
  activeProducts,
  purchases,
  loading,
  error,
  user,
  selectedTab,
  fullAddress,
  reviews,
  sales,
  fetchProducts,
  getDataProfile,
  getGeoLocation,
} = useProfile();

onMounted(async () => {
  await getDataProfile();
});

watch(user, (newUser) => {
  if (newUser.id) {
    fetchProducts(`getPurchase`, newUser.id, 'purchases');
    fetchProducts(`getSales`, newUser.id, 'sales');
    fetchProducts(`getValorations`, newUser.id, 'reviews');
    fetchProducts(`getAllToSell`, newUser.id, 'activeProducts');
    getGeoLocation();
  }
});


</script>


<style scoped>
.container-info img {
  height: 100px;
  width: 100px;
  border-radius: 100px;
  background-color: var(--primary-color);
}
.container-else {
  width: 100%;
  text-align: center;
}
.image-else {
  width: auto;
  max-height: 200px;
}
</style>