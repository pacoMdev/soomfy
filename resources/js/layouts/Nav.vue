<template>
    <nav class="navbar navbar-expand-md navbar-light fondoNav shadow-sm">
        <div class="d-none d-md-block w-100">
            <div class="px-5 m-0 d-flex flex-row justify-content-between w-100">
                <router-link to="/" class="align-items-center d-flex">
                    <img src="/images/logo.svg" class="logo-size" alt="Soomfy">
                    <div class="tx-color-secondari">Soomfy</div>
                </router-link>

                <div :class="[
                    'nav-content-base',
                    isRestrictedRoute ? 'nav-content-small' : 'nav-content-full'
                ]">
                    <SearchBar v-if="!isRestrictedRoute" class="search-bar" />
                    
                    <!-- Botón hamburguesa para pantallas medianas -->
                    <button class="hamburger-btn" @click="toggleMenu">
                        <i class="pi pi-bars"></i>
                    </button>

                    <!-- Menú que se convertirá en dropdown -->
                    <div class="nav-menu" :class="{ 'menu-active': isMenuOpen }">
                        <ul class="navbar-nav align-items-center gap-2">
                            <button class="primary-button">Como funciona</button>
                            <router-link to="/chat" class="nav-link d-flex align-items-center gap-2">
                                <img src="/images/chat.svg" class="icons-size" alt="">
                                <p class="nav-text">Chat</p>
                            </router-link>
                            <router-link to="/favoritos" class="nav-link d-flex align-items-center gap-2">
                                <img src="/images/favoritos.svg" class="icons-size" alt="">
                                <p class="nav-text mobile-only-text">Favoritos</p>
                            </router-link>
                            <template v-if="!authStore().user?.name">
                                <router-link class="nav-link d-flex align-items-center gap-2" to="/login">
                                    <img src="/images/logoUser.svg" class="icons-size">
                                    <p class="nav-text mobile-only-text">Tu cuenta</p>
                                </router-link>
                            </template>
                            <router-link v-else :to="'/app/profile'" class="nav-link d-flex align-items-center gap-2">
                                <img :src="authStore().user?.avatar" class="icons-size user-avatar">
                                <p class="nav-text mobile-only-text">Tu cuenta</p>
                            </router-link>
                            <router-link to="/subir-producto" class="d-flex align-items-center">
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
        </div>
        <div class="d-block d-md-none w-100 justify-content-center">
            <div class="d-flex flex-column justify-content-center w-100 py-4">
                <router-link to="/" class="d-flex align-items-center py-4 mx-auto">
                    <img src="/images/logo.svg" class="logo-size" alt="Soomfy">
                    <p>Soomfy</p>
                </router-link>
                <SearchBar :smallScreen="true" class="search-bar-small" />
            </div>
        </div>
    </nav>
</template>

<script setup>
import { ref } from 'vue';
import useAuth from "@/composables/auth";
import { authStore } from "../store/auth";
import SearchBar from "@/components/SearchBar.vue";
import {computed} from "vue";
import {useRoute} from "vue-router";

const { processing, logout } = useAuth();
const route = useRoute(); // Añadimos esta línea

/**
 * Comprueba si la ruta actual es una ruta restringida (home, login, register)
 * Se usa para controlar el layout y comportamiento del nav
 */
const isRestrictedRoute = computed(() => {
  const restrictedRoutes = ['home', 'login', 'register'];
  return restrictedRoutes.includes(route.name);
});

/**
 * Estado del menú móvil (abierto/cerrado)
 */
const isMenuOpen = ref(false);

/**
 * Alterna el estado del menú móvil
 */
const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};
</script>

<style scoped>
/* Estilos base compartidos */
.nav-content-base {
    display: flex;
    align-items: center;
    gap: 1rem;
}

/* Para páginas normales (no home) en pantallas grandes */
.nav-content-full {
    width: 100%;
    justify-content: space-between;
}

/* Para home en pantallas grandes - sin width definido */
.nav-content-small {
    justify-content: flex-end;
    /* Sin width aquí para que los elementos se ajusten naturalmente a la derecha */
}

.hamburger-btn {
    display: none;
    background: none;
    border: none;
    color: var(--secondary-color);
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0.5rem;
}

.user-avatar {
    height: 30px;
    width: 30px;
    border-radius: 100px;
    background-color: antiquewhite;
}

.nav-link {
    width: auto;
    padding: 0.5rem;
    display: flex;
    align-items: center;
    border-radius: 8px;
    transition: background-color 0.2s ease;
}


.nav-link p {
    margin: 0;
}

.textSize-cuenta {
    margin: 0;
    font-size: 14px;
}

.nav-text {
    margin: 0;
    font-size: 16px;
    font-weight: 500;
}

.mobile-only-text {
    display: none;
}

@media (max-width: 1350px) {
    .hamburger-btn {
        display: block;
    }

    .nav-menu {
        display: none;
        position: absolute;
        top: 100%;
        right: 0;
        justify-content: center;
        background: var(--primary-color);
        padding: 1rem;
        border-radius: 0 0 10px 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        z-index: 1000;
    }

    .menu-active {
        display: block;
    }

    .search-bar {
        width: 100% !important;
        max-width: 400px;
    }

    .navbar-nav {
        flex-direction: column;
        align-items: flex-start !important;
    }

    .nav-link {
        width: 100%;
        padding: 0.5rem 0;
    }

    .nav-content-full {
        width: 80% !important;
        justify-content: space-between;
    }
    
    .nav-link:hover {
        background-color: var(--secondary-color);
    }

    /* En pantallas pequeñas, nav-content-small no tiene width definido */
    .nav-content-small {
        justify-content: flex-end;
    }

    .nav-text {
        font-size: 14px;
    }

    .mobile-only-text {
        display: block;
    }
}
</style>
