<template>
    <div class="d-none d-md-block">
      <div class="row container mx-auto py-4">
        <div class="col-md-4 mb-4">
          <h5 class="mb-3">PÁGINAS</h5>
          <ul class="list-unstyled">
            <li><a href="/" class="text-decoration-none text-secondary">Home</a></li>
            <li><a href="/products" class="text-decoration-none text-secondary">Productos</a></li>
            <li><a href="#" class="text-decoration-none text-secondary">Contact</a></li>
            <li><a href="#" class="text-decoration-none text-secondary">Home</a></li>
          </ul>
        </div>
        <div class="col-md-4 mb-4">
          <h5 class="mb-3">INFO</h5>
          <ul class="list-unstyled">
            <li><a href="#" class="text-decoration-none text-secondary">Como funciona</a></li>
            <li><a href="#" class="text-decoration-none text-secondary">Sobre nosotros</a></li>
            <li><a href="#" class="text-decoration-none text-secondary">Legal</a></li>
            <li><a href="#" class="text-decoration-none text-secondary">Devoluciones</a></li>
          </ul>
        </div>
        <div class="col-md-4 mb-4">
          <h5 class="mb-3">Idioma</h5>
          <Dropdown
            v-model="selectedLanguage"
            :options="languages"
            optionLabel="label"
            placeholder="Selecciona idioma"
            class="w-100"
          >
            <template #value="slotProps">
              <div class="d-flex align-items-center" v-if="slotProps.value">
                <span :class="`flag-icon ${slotProps.value.icon} me-2`" />
                <span>{{ slotProps.value.label }}</span>
              </div>
              <span v-else class="text-muted">Selecciona idioma</span>
            </template>
            <template #option="slotProps">
              <div class="d-flex align-items-center">
                <span :class="`flag-icon ${slotProps.option.icon} me-2`" />
                <span>{{ slotProps.option.label }}</span>
              </div>
            </template>
          </Dropdown>
        </div>
      </div>
      <footer class="text-center py-3 border-top">
        <p class="mb-0">© {{ currentYear }} Soomfy. Compra venta de segunda mano</p>
      </footer>
    </div>
    <div class="d-block d-md-none w-100 bg-bs-color-primary tx-color-secondari fixed-bottom">
        <div class="d-flex w-100 justify-content-between px-5 py-2 container-nav-movile">
            <router-link to="/" class="tx-color-secondari d-flex flex-column justify-content-center">
                <img src="../../../public/images/home/Icon.svg" alt="Descubre">
                <p>Descubre</p>
            </router-link>
            <router-link to="/favoritos" class="tx-color-secondari d-flex flex-column justify-content-center">
                <img src="../../../public/images/home/Icon-1.svg" alt="Favoritos">
                <p>Favoritos</p>
            </router-link>
            <router-link to="/subir-producto" class="tx-color-secondari d-flex flex-column justify-content-center">
                <img src="../../../public/images/home/Icon-2.svg" alt="Publicar">
                <p>Publicar</p>
            </router-link>
            <router-link to="/chat" class="tx-color-secondari d-flex flex-column justify-content-center">
                <img src="../../../public/images/home/Icon-3.svg" alt="Mensajes">
                <p>Mensajes</p>
            </router-link>
            <router-link to="/app/profile" class="tx-color-secondari d-flex flex-column justify-content-center">
                <img src="../../../public/images/home/Icon-4.svg" alt="Cuenta">
                <p>Cuenta</p>
            </router-link>
      </div>
    </div>
</template>

<script setup>

import useAuth from "@/composables/auth";
import LocaleSwitcher from "../components/LocaleSwitcher.vue";
import { authStore } from "../store/auth";
import { ref, onMounted } from "vue";
import Dropdown from 'primevue/dropdown';
import 'primeicons/primeicons.css';

const { processing, logout } = useAuth();
const currentYear = ref("");
const languages = ref([
  { label: 'Español', value: 'es', icon: 'flag-icon-es' },
  { label: 'Inglés', value: 'en', icon: 'flag-icon-us' },
]);
const selectedLanguage = ref(languages.value[0]);

onMounted(()=>{
    currentYear.value = new Date().getFullYear();
})

</script>
<style>
.container-nav-movile img{
    height: 35px;
}

.fixed-bottom {
    position: sticky;
    bottom: 0;
    left: 0;
    width: 100%;
    text-align: center;
    padding: 10px;
}

.flag-icon {
    width: 20px;
    height: 15px;
    background-size: cover;
    display: inline-block;
}
.flag-icon-es {
    background-image: url('/images/flags/flag-for-flag-spain-svgrepo-com.svg');
}
.flag-icon-us {
    background-image: url('/images/flags/flag-us-svgrepo-com.svg');
}
</style>
