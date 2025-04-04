@ -0,0 +1,65 @@
<template>
    <div class="w-100 h-100 d-flex justify-content-center align-items-center">
        <DotLottieVue style="height: 500px; width: 500px" autoplay loop src="https://lottie.host/4ab8e752-d3c6-4008-8853-2416f93a20a9/X07ARf2nB0.lottie" />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from "vue-router";
import { DotLottieVue } from '@lottiefiles/dotlottie-vue'
import { authStore } from "../../store/auth";

import axios from 'axios';

const route = useRoute();
const router = useRouter();
const auth = authStore();
const processing = ref(false);
const validationErrors = ref({});





onMounted(async() =>{
    const userData = route.query.user;
    if (userData) {
        const user = JSON.parse(decodeURIComponent(userData));
        console.log('🔎 USER_URL', user);
        if (processing.value) return

        processing.value = true
        validationErrors.value = {}
        try {
            // Redirige a Laravel para iniciar sesión con Google
            await axios.get(`profile/${user.id}`)
                .then( async response => {
                    console.log('🔎 await auth.getUser()');
                    await auth.getUser()
                    console.log('🔎 auth.user.value', auth.user.value);
                    await loginUser()
                    // await router.push({ name: 'home' })
                })
                .catch(error => {
                    if (error.response?.data) {
                        validationErrors.value = error.response.data.errors
                    }
                })
                .finally(() => processing.value = false)

        } catch (error) {
        console.error("❌ ERROR ERROR", error);
        }

        // Redirige a home
        await router.push({ name: "home" });
    } else {
        console.error("⚠️ No se encontraron datos del usuario en la URL");
        await router.push({ name: "home" });
    }
})

        

</script>