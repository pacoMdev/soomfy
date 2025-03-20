<template>
    <div class="d-none d-md-block">
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
                <select name="" id="" class="" style="width: 250px;">
                    <option value="">
                        <img src="/images/flags/flag-for-flag-spain-svgrepo-com.svg" alt="Español">
                        <p>Español</p>
                    </option>
                    <option value="">
                        <div>
                            <img src="/images/flags/flag-us-svgrepo-com.svg" alt="Ingles">
                            <p>Ingles</p>
                        </div>
                    </option>
                </select>
                
            </div>
        </div>
        <footer class="navbar navbar-expand-md navbar-light shadow-sm justify-content-center">
            <div class="container p-0 m-0 d-flex justify-content-center">
                <div class="d-flex w-50 align-items-center justify-content-center">
                    <p>© {{ currentYear }} Soomfy. Compra venta de segunda mano</p>
                </div>
                
            </div>
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

const { processing, logout } = useAuth();
const currentYear = ref("");

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
</style>
