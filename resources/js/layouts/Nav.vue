<template>
    <nav class="navbar navbar-expand-md navbar-light fondoNav shadow-sm">
        <div class="d-none d-md-block w-100">
            <div class="px-5 m-0 d-flex flex-row justify-content-between w-100">
                <router-link to="/" class="align-items-center d-flex">
                    <img src="../../../public//images/logo.svg" class="logo-size" alt="Soomfy">
                    <div class="tx-color-secondari">Soomfy</div>
                </router-link>
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
                            <router-link class="nav-link" to="/register">
                                <img src="/images/logoUser.svg" class="icons-size">
                            </router-link>
                        </template>
                        <li v-if="authStore().user?.name" class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <img src="/images/logoUser.svg" class="icons-size">
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><p class="h4-p text-center">Bienvenid@ {{ authStore().user?.name }}</p></li>
                                <li><router-link class="dropdown-item" v-if="authStore().isAdmin" to="/admin">Admin</router-link></li>
                                <li><router-link to="/profile" class="dropdown-item">Cuenta</router-link></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="javascript:void(0)" @click="logout">Logout</a></li>
                            </ul>
                        </li>
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
            <div class="d-flex flex-column justify-content-center w-100">
                <router-link to="/" class="d-flex align-items-center py-4 mx-auto">
                    <img src="/images/logo.svg" class="logo-size" alt="Soomfy">
                    <p>Soomfy</p>
                </router-link>
                <input type="text" class="buscadorProductosH mx-auto" placeholder="Buscar">
            </div>
        </div>
    </nav>
</template>


<script setup>

import useAuth from "@/composables/auth";
import LocaleSwitcher from "../components/LocaleSwitcher.vue";
import { authStore } from "../store/auth";

const { processing, logout } = useAuth();
</script>
<style>
.buscadorProductosH {
    height: 40px;
    border-radius: 25px;
    border: 1px solid var(--primary-color);
    padding-left: 25px;
    width: 75%;
}
</style>