<template>
    <nav class="navbar navbar-expand-md navbar-light fondoNav shadow-sm">
        <div class="container p-0 m-0">
            <img src="images/logo.svg" class="logo-size" alt="logo">
            <router-link to="/" class="navbar-brand">Soomfy</router-link>
            <a class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </a>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mt-2 mt-lg-0 me-auto mb-2 mb-lg-0">
                    <LocaleSwitcher />
                </ul>
                <ul class="navbar-nav mt-2 mt-lg-0 ms-auto">
                        <button class="primary-button">Como funciona</button>
                        <router-link to="/" class="nav-link d-flex align-items-center" aria-current="page">
                            <img src="images/chat.svg" class="icons-size" alt="">
                            <p>Chat</p>
                        </router-link>
                        <router-link to="/" class="nav-link d-flex align-items-center" aria-current="page">
                            <img src="images/favoritos.svg" class="icons-size" alt="">
                        </router-link>
                    <template v-if="!authStore().user?.name">
                            <router-link class="nav-link" to="/register"><img src="images/logoUser.svg" class="icons-size"></router-link>
                    </template>
                    <li v-if="authStore().user?.name" class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ authStore().user?.name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><router-link class="dropdown-item" to="/admin">Admin</router-link></li>
                            <li><router-link to="/admin/posts" class="dropdown-item">Post</router-link></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" @click="logout">Logout</a></li>
                        </ul>
                    </li>
                    <router-link to="/" class="nav-link d-flex align-items-center" aria-current="page">
                        <button class="d-flex secondary-button">
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
