<template>
    <div class="separacion-general">
        <div class="centrar-productos">
            <div class="productos">
                <Producto />
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center">
        <button class="primary-button">¡Ver mas!</button>
    </div>
    
</template>

<script setup>

import '../../../css/home/home.css';
import Producto from '../../components/Producto.vue';

</script>