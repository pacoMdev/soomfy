<template>
    <div class="d-flex flex-wrap justify-content-center gap-6 w-100  p-6">
        <div class="w-100 h-100 d-flex justify-content-center align-items-center">
        <DotLottieVue style="height: 500px; width: 500px" autoplay loop src="https://lottie.host/05c30340-101d-4be5-bae3-eb8692af6d07/4slDMEJ3ri.lottie" />
    </div>
    </div>
    
</template>
  
<script setup>
    import { onMounted, watch, ref } from 'vue';
    import { DotLottieVue } from '@lottiefiles/dotlottie-vue'
    import useCheckout from '../../../composables/checkout';

    import './FinalCheckout.css';
    
    const { 
        registerTransaction,
    } = useCheckout();


    onMounted ( () => {
        registerTransaction();
    });

</script>