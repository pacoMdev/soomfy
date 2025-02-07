<template>
    <footer class="navbar navbar-expand-md navbar-light shadow-sm justify-content-center">
        <div class="container p-0 m-0 d-flex justify-content-center">
            <div class="d-flex w-50 align-items-center justify-content-center">
                <p>© 2025 Soomfy. Compra venta de segunda mano</p>
            </div>
            
        </div>
    </footer>
</template>


<script setup>

import useAuth from "@/composables/auth";
import LocaleSwitcher from "../components/LocaleSwitcher.vue";
import { authStore } from "../store/auth";

const { processing, logout } = useAuth();
</script>
