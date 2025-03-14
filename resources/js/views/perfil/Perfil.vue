<template>
    <main>
      <div class="">      
        <div class="d-flex flex-wrap gap-5 align-items-center justify-content-center py-5">
          <div v-if="user.email" class="container-info d-flex gap-5">
            <img v-if="user.media?.[0]" :src="user.media[0]['original_url']" :alt="user.media[0]['original_url']">
            <img v-else :src="user.avatar"  alt="default-image">
  
            <div class="container-info-profile d-flex flex-column gap-1">
              <h4 class="m-0">{{ user.name }} {{ user.surname1 }}</h4>
              <div class="d-flex gap-2 container-rating">
                <Rating v-model="mediaRating" readonly />
                <p>({{ reviews.length }})</p>
              </div>
              <p>Vendedor novel</p>
            </div>
          </div>
  
          <!-- Botones de acciones -->
          <div class="d-flex flex-wrap gap-3 w-auto h-100 container-extra-info">
            <Button @click="fetchProducts(`getPurchase`, user.id, 'purchases')" label="Compras" icon="pi pi-box" :badge="purchases.length" rounded />
            <Button @click="fetchProducts(`getSales`, user.id, 'sales')" label="Ventas" icon="pi pi-dollar" :badge="sales.length" rounded />
            <Button label="Localización" icon="pi pi-map-marker" rounded />
          </div>
        </div>
  
        <div>
          <div class="d-flex gap-3 w-100 justify-content-center">
            <Button @click="fetchProducts(`getAllToSell`, user.id, 'activeProducts')" label="Mis Productos" icon="pi pi-shop" :badge="activeProducts.length" rounded />
            <Button @click="fetchProducts(`getValorations`, user.id, 'reviews')" label="Valoraciones" icon="pi pi-comment" :badge="reviews.length" rounded />
          </div>
  
          <div class="container w-100 d-flex gap-5">
            <div v-if="loading">Cargando...</div>
            <div v-if="error" style="color: red;">{{ error }}</div>
  
             <!-- COMPRAS -------------------------------------------------------------------------------------------- -->
            <div v-if="selectedTab === 'purchases'" class="w-100">
              <h4>Historial de Compras</h4>
              <div v-if="purchases.length > 0">
                <HistoricInfo :historic="purchases" />
              </div>
              <h1 v-else>No hay Compras</h1>
            </div>
            <!-- VENTAS -------------------------------------------------------------------------------------------- -->
            <div v-if="selectedTab === 'sales'" class="w-100">
              <h4>Historial de Ventas</h4>
              <div v-if="sales.length > 0">
                <HistoricInfo :historic="sales" />
              </div>
              <h1 v-else>No hay Ventas</h1>
            </div>
            <!-- PRODUCTOS -------------------------------------------------------------------------------------------- -->
            <div v-if="selectedTab === 'activeProducts'">
              <h4>Mis Productos</h4>
              <div v-if="activeProducts.length > 0">
                <ProductoNew :productos="activeProducts" :actualizarProductos="fetchProducts" />
              </div>
              <h1 v-else>No hay Productos</h1>
            </div>
            <!-- OPINIONES -------------------------------------------------------------------------------------------- -->
            <div v-if="selectedTab === 'reviews'" class="w-100">
              <h4>Valoraciones</h4>
              <div v-if="reviews.length > 0">
                <ValorationInfo :reviews="reviews" />
              </div>
              <h1 v-else>No hay Valoraciones</h1>
            </div>
          </div>
        </div>
      </div>
    </main>
  </template>
  
  
  <script setup>
  import { ref, onMounted, watch } from 'vue';
  import axios from 'axios';
  import useAuth from "@/composables/auth";
  import Rating from 'primevue/rating';
  import ProductoNew from '../../components/ProductoNew.vue';
  import HistoricInfo from '../../components/historicInfo.vue';
  import ValorationInfo from '../../components/valorationInfo.vue';
  
  
  const mediaRating = ref(0);
  const activeProducts = ref([]);
  const purchases = ref([]);
  const sales = ref([]);
  const reviews = ref([]);
  const loading = ref(false);
  const error = ref(null);
  const { logout } = useAuth();
  const user = ref({});
  const selectedTab = ref(null);
  
  onMounted(async () => {
    await getDataProfile();
    // ProductService.getProductsSmall().then((data) => (products.value = data));
  
  });
//   Otencion de la id del usuario para mostrar el resto de datos
  const path = window.location.pathname; // obtiene url
  const segments = path.split('/');
  const id = segments.pop();  // obtiene ultimo elemento (id)

  
  // ejecuta fetchProducts cuando userData este disponible
  watch(user, (newUser) => {
    if (newUser.id) {
      fetchProducts(`getPurchase`, newUser.id, 'purchases');
      fetchProducts(`getSales`, newUser.id, 'sales');
      fetchProducts(`getValorations`, newUser.id, 'reviews');
      fetchProducts(`getAllToSell`, newUser.id, 'activeProducts');
    }
  });
  
  const fetchProducts = async (endpoint, id, type) => {
    loading.value = true;
    error.value = null;
    selectedTab.value = type; // guardamos que se esta viendo
  
    try {
      const response = await axios.post(`/api/${endpoint}`, {
        userId: id,
      });
      
      // guarda info segun seccion
      if (type === 'purchases') purchases.value = response.data.data || [];
      else if (type === 'sales') sales.value = response.data.data || [];
      else if (type === 'activeProducts') activeProducts.value = response.data.data || [];
      else if (type === 'reviews') {reviews.value = response.data.data || []; calcularMediaRating();};
      console.log('purchase -->', purchases);
      console.log('sales -->', sales);
      console.log('activeProducts -->', activeProducts);
      console.log('reviews -->', reviews);
  
    } catch (err) {
      error.value = "Error al obtener los datos.";
    } finally {
      loading.value = false;
    }
  };
  
  const getDataProfile = async () => {
    try {
      const respuesta = await axios.get('/api/profile/'+id);
      user.value = respuesta.data.data || {};
      console.log('user --->', user);
      console.log(user.value.id);
    } catch (error) {
      console.error("Error al obtener usuario:", error);
    }
  };
  const calcularMediaRating = () => {
  if (reviews.value.length === 0) {
    mediaRating.value = 0;
    return;
  }
  const total = reviews.value.reduce((sum, review) => sum + review.calification, 0);
  mediaRating.value = total / reviews.value.length;
};
  </script>
  
  
  <style scoped>
  .container-info img {
    height: 100px;
    width: 100px;
    border-radius: 100px;
    background-color: var(--primary-color);
  }
  </style>