<template>
    <div v-if="userProduct[0]?.toSend===1" class="d-flex flex-wrap justify-content-center gap-6 w-100  p-6">
        <div class="shadow rounded p-6 gap-5 m-0 container-opinion">
            <h4>Checkout</h4>
            <form @submit.prevent="submitPurchaseForm" class="d-flex flex-column gap-4">
                <div class="d-flex flex-column gap-5">
                    
                </div>
                <Button label="Comprar" type="submit" class="w-full" />
                <p v-if="error" class="text-red-500 text-center mt-2 w-75 mx-auto" style="color: red!important;">{{ errorMessage }}</p>

            </form>
        </div>
        <div v-if="userProduct">
            <Producto :productos="userProduct" />
        </div>
        <div v-else class="shadow rounded p-6 gap-5 m-0 container-opinion">
            {{ userProduct }}
            <h1>nada</h1>
        </div>
    </div>
    <div v-else>
        <i class="pi pi-spin pi-spinner" style="font-size: 2rem"></i>
    </div>
</template>
  
<script setup>
    import { Dialog, RadioButton, Button, InputText, Listbox, Tooltip } from 'primevue';
    import { onMounted, watch, ref } from 'vue';
    import Producto from '../../components/Producto.vue';
    import useCheckout from '../../composables/checkout';
    import useShippingAddress from '../../composables/shippingAddress'; 
import { right } from '@popperjs/core';
    
    const {
        stablishments,
        cpCercano,
        getDistributionsCenters,
        getProximityCenters,
    } = useShippingAddress();

    const { 
        checkout,
        address,
        productId,
        userProduct,
        selectedMethod,
        showDialog,
        newAddress,
        newCity,
        newCp,
        newCountry,
        error,
        typeShippment,
        showSelectCenter,
        getUserProduct,
        submitPurchaseForm,
        saveAddress,
        saveShippingAddress,
        errorMessage,
        selectedStablishment,
    } = useCheckout();


    onMounted (() => {
        getUserProduct();
        getDistributionsCenters();
        console.log('stablishments', stablishments.value);
    });
    watch(cpCercano, () => {
        if(cpCercano.value.length == 5){
            console.log('CP -->', cpCercano.value);
            // getProximityCenters(cpCercano.value);
        }
    });
    watch(selectedStablishment, () => {
        console.log('selectedStablishment', selectedStablishment.value?.address || undefined);
        console.log(errorMessage.value);
    })
</script>

<style scoped>
    .purchase-container {
    max-width: 400px;
    margin: auto;
    text-align: center;
    }
    .success-message {
    color: green;
    margin-top: 10px;
    }
</style>