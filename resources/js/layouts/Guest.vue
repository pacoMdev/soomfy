<template>
    <div>
        <Navbar v-if="!isAuthPage"/>
        <router-view></router-view>
        <Footer v-if="!isAuthPage"/>
    </div>
</template>

<script setup>
import { useRoute } from 'vue-router';
import { computed } from 'vue';
import Navbar from './Nav.vue';
import Footer from './Footer.vue';

const route = useRoute();

const isAuthPage = computed(() => {
    return route.path === '/login' || route.path === '/register' || route.path === '/forgot-password';
});
</script>
