<template>
    <nav class="navbar navbar-expand-md navbar-light fondoNav shadow-sm">
        <div class="d-none d-md-block w-100">
            <div class="px-5 m-0 d-flex flex-row justify-content-between w-100">
                <router-link to="/" class="align-items-center d-flex">
                    <img src="/images/logo.svg" class="logo-size" alt="Soomfy">
                    <div class="tx-color-secondari">Soomfy</div>
                </router-link>

              <SearchBar v-if="!isRestrictedRoute" />
              <div class="align-items-center">
                    <ul class="navbar-nav align-items-center gap-2">
                        <button class="primary-button">Como funciona</button>
                        <router-link to="/chat" class="nav-link d-flex align-items-center gap-2">
                            <img src="/images/chat.svg" class="icons-size" alt="">
                            <p class="textSize-chat">Chat</p>
                        </router-link>
                        <router-link to="/favoritos" class="nav-link d-flex align-items-center">
                            <img src="/images/favoritos.svg" class="icons-size" alt="">
                        </router-link>
                        <template v-if="!authStore().user?.name">
                            <router-link class="nav-link" to="/login">
                                <img src="/images/logoUser.svg" class="icons-size">
                            </router-link>
                        </template>
                        <router-link v-if="authStore().user?.name" :to="'/app/profile'" class="nav-item dropdown">
                            <img :src="authStore().user?.avatar" class="icons-size" style="height: 30px; width: 30px; border-radius: 100px; background-color: antiquewhite;">
                        </router-link>
                        <router-link to="/subir-producto" class="nav-link d-flex align-items-center">
                            <button class="d-flex primary-button-2 gap-2">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.59383 5.88469V12.1152M5.47856 8.99995H11.7091M16.382 8.99995C16.382 13.3012 12.8951 16.7881 8.59383 16.7881C4.29254 16.7881 0.805664 13.3012 0.805664 8.99995C0.805664 4.69867 4.29254 1.21179 8.59383 1.21179C12.8951 1.21179 16.382 4.69867 16.382 8.99995Z" stroke="currentColor" stroke-width="1.40187" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <p>Anuncia y vende</p>
                            </button>
                        </router-link>
                    </ul>
                </div>
            </div>
        </div>
        <div class="d-block d-md-none w-100 justify-content-center">
            <div class="d-flex flex-column justify-content-center w-100 py-4">
                <router-link to="/" class="d-flex align-items-center py-4 mx-auto">
                    <img src="/images/logo.svg" class="logo-size" alt="Soomfy">
                    <p>Soomfy</p>
                </router-link>
                <SearchBar/>
            </div>
        </div>
    </nav>
</template>


<script setup>

import useAuth from "@/composables/auth";
import LocaleSwitcher from "../components/LocaleSwitcher.vue";
import { authStore } from "../store/auth";
import SearchBar from "@/components/SearchBar.vue";
import {computed} from "vue";
import {useRoute} from "vue-router";

const { processing, logout } = useAuth();
const route = useRoute(); // Añadimos esta línea

// Verificar si estamos en una ruta restringida
const isRestrictedRoute = computed(() => {
  const restrictedRoutes = ['home', 'login', 'register'];
  return restrictedRoutes.includes(route.name);
});



</script>
