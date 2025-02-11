<template>
    <div class="footer">
        <div class="footer-columns">
            <div class="footer-column">
                <h3>PÁGINAS</h3>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Contact</a></li>
                    <li><a href="#">Home</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>INFO</h3>
                <ul>
                    <li><a href="#">Como funciona</a></li>
                    <li><a href="#">Sobre nosotros</a></li>
                    <li><a href="#">Legal</a></li>
                    <li><a href="#">Devoluciones</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>INFO</h3>
                <ul>
                    <li><a href="#">Como funciona</a></li>
                    <li><a href="#">Sobre nosotros</a></li>
                    <li><a href="#">Legal</a></li>
                    <li><a href="#">Devoluciones</a></li>
                </ul>
            </div>
        </div>
        <div class="language-selector">
            <button class="language-button">
                Español
                <span class="dropdown-icon">▼</span>
            </button>
        </div>
    </div>
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
