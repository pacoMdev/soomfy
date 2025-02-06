<template>
    <nav class="navbar navbar-expand-md navbar-light fondoNav shadow-sm">
        <div class="container p-0 m-0 d-flex justify-content-between">
            <div class="d-flex w-50 align-items-center">
                <img src="images/logo.svg" class="logo-size" alt="logo">
                <router-link to="/" class="navbar-brand">Soomfy</router-link>
            </div>
            <div class="d-flex w-50 justify-content-end align-items-center">
                <ul class="navbar-nav align-items-center gap-2">
                    <button class="primary-button">Como funciona</button>
                    <router-link to="/chat" class="nav-link d-flex align-items-center gap-2">
                        <img src="images/chat.svg" class="icons-size" alt="">
                        <p class="textSize-chat">Chat</p>
                    </router-link>
                    <router-link to="/" class="nav-link d-flex align-items-center">
                        <img src="images/favoritos.svg" class="icons-size" alt="">
                    </router-link>
                    <template v-if="!authStore().user?.name">
                        <router-link class="nav-link" to="/register">
                            <img src="images/logoUser.svg" class="icons-size">
                        </router-link>
                    </template>
                    <li v-if="authStore().user?.name" class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            {{ authStore().user?.name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><router-link class="dropdown-item" to="/admin">Admin</router-link></li>
                            <li><router-link to="/admin/posts" class="dropdown-item">Post</router-link></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" @click="logout">Logout</a></li>
                        </ul>
                    </li>
                    <router-link to="/" class="nav-link d-flex align-items-center">
                        <button class="d-flex secondary-button gap-2">
                            <img src="images/agregar.svg" class="icon-plus" alt="">
                            <p>Anuncia y vende</p>
                        </button>
                    </router-link>
                </ul>
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
