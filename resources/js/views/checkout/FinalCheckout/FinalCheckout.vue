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
    import { useRoute } from 'vue-router'
    import { usePurchaseStore } from '@/store/purchaseStore';
    import './FinalCheckout.css';
    
    const route = useRoute();
    const purchaseStore = usePurchaseStore();


    onMounted (async () => {

        const sessionId = route.query.session_id;

        // datos guardados de store por pinia
        const data = purchaseStore.purchaseData;
        // recupera de localStorage y guarda en purchaseStore
        const saved = localStorage.getItem('purchaseData');
        if (saved && !purchaseStore.purchaseData) {
            purchaseStore.setPurchase(JSON.parse(saved));
        }


        // Registrar la compra en tu backend
        console.log('🔎 data', purchaseStore.purchaseData);
        const purchaseResponse = await axios.post('/api/fakePurchaseProduct', {
            purchaseData: purchaseStore.purchaseData,
            sessionId: sessionId,
        })
        .then(() => {
            console.log('Producto comprado', purchaseResponse.data);
            console.log('👌 Status:', purchaseResponse.status);
            
            swal({
                icon: 'success',
                title: 'Compra realizada',
                showConfirmButton: false,
                timer: 1500,
            }); 
            
            router.push({ name: 'home' });
        });
    });

</script>