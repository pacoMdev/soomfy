<template>
    <div v-if="userProduct[0]?.toSend===1" class="d-flex flex-wrap justify-content-center gap-6 w-100  p-6">
        <div v-if="isSelledProduct == false" class="w-100 d-flex flex-wrap justify-content-center gap-4">
            <div class="shadow rounded p-6 gap-5 m-0 container-opinion">
                <h4>Comprando</h4>
                <form @submit.prevent="submitPurchaseForm" class="d-flex flex-column gap-4">
                    <div class="d-flex flex-column gap-5">
                        <div class="flex flex-column gap-4">
                            <div class="d-flex flex-column gap-3">
                                <div class="flex justify-between gap-2 items-center">
                                    <div>
                                        <div class="d-flex gap-3">
                                            <i class="pi pi-home" style="font-size: 2rem"></i>
                                            <p style="font-size: 1.1rem">Entrega en mi dirección 4,78 €</p>
                                        </div>
                                    </div>
                                    <RadioButton v-model="selectedMethod" :inputId="1" name="dynamic" value="1" />
                                </div>
                                <Button label="Añadir dirección" icon="pi pi-plus" class="p-button-text w-100" @click="showDialog = true" :disabled="selectedMethod!=1" />
                            </div>
                            <div class="d-flex flex-column gap-3">
                                <div class="flex justify-between gap-2 items-center">
                                    <div>
                                        <div class="d-flex gap-3">
                                            <i class="pi pi-building" style="font-size: 2rem"></i>
                                            <p style="font-size: 1.1rem">Entrega en centro de recogida</p>
                                        </div>
                                    </div>
                                    <RadioButton v-model="selectedMethod" :inputId="2" name="dynamic" value="2" />
                                </div>
                                <Button label="Seleccionar centro" icon="pi pi-plus" class="p-button-text w-100" @click="showSelectCenter = true" :disabled="selectedMethod!=2" />
                            </div>
                        </div> 
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
            <h1>este producto ya fue vendido</h1>
        </div>
    </div>
    <div v-else>
        <i class="pi pi-spin pi-spinner" style="font-size: 2rem"></i>
    </div>
    <!-- Dialog añadir dirección ------------------------------------------------------------------------  -->
    <Dialog v-model:visible="showDialog" modal header="Añadir dirección" :style="{ width: '26rem' }">
        <div class="d-flex flex-column gap-4 py-4">
            <!-- SECTION DATA ADDRESS ------------------------------------ -->
            <div class="d-flex flex-column gap-4 py-4">
                <div class="p-fluid">
                    <FloatLabel>
                        <InputText id="address" v-model="address.newAddress" fluid />
                        <label for="address">Dirección</label>
                    </FloatLabel>
                </div>
                <div class="p-fluid">
                    <FloatLabel>
                        <InputText id="cp" v-model="address.newCp" inputId="withoutgrouping" :useGrouping="false" fluid />
                        <label for="cp">Codigo postal</label>
                    </FloatLabel>
                </div>
                <div class="p-fluid">
                    <FloatLabel>
                        <InputText id="city" v-model="address.newCity" fluid/>
                        <label for="city">Ciudad</label>
                    </FloatLabel>
                </div>
                <div class="p-fluid">
                    <FloatLabel>
                        <InputText id="country" v-model="address.newCountry" fluid />
                        <label for="country">Pais</label>
                    </FloatLabel>
                </div>
            </div>
        </div>
        <template #footer class="">
            <Button label="Guardar" icon="pi pi-check" class="p-button-primary mx-auto" @click="saveAddress" />
        </template>
    </Dialog>
    <!-- Dialog añadir dirección ------------------------------------------------------------------------  -->
    <Dialog v-model:visible="showSelectCenter" modal header="Selecciona centro" style=" width: min(90vw, 500px); height: min(90vh, 500px); ">
        <div class="d-flex flex-column gap-4 py-4">
            <div class="p-fluid">
                <FloatLabel>
                    <InputText id="cp" v-model="cpCercano" inputId="withoutgrouping" :useGrouping="false" fluid />
                    <label for="cp">Intoduce codigo postal</label>
                </FloatLabel>
            </div>
            <div>
                <Listbox v-model="selectedStablishment" :options="stablishments || []" optionLabel="name" class="w-full md:w-56" listStyle="max-height:250px">
                    <template #option="slotProps">
                        <div class="flex items-center">
                            <div class="d-flex flex-column gap-2">
                                <div class="d-flex gap-3 p-1">
                                    <i class="pi pi-map-marker" style="font-size: 1.5rem"></i>
                                    <div>
                                        <div class="d-flex gap-2">
                                            <p class="m-0"><b>{{ slotProps.option.contact_name }}</b></p>
                                            <i v-tooltip.right="'Telefono: ' + slotProps.option.contact_phone + '\n Email: ' + slotProps.option.contact_email" class="pi pi-info-circle" style="font-size: 1rem"></i>
                                        </div>
                                        <p class="m-0">{{ slotProps.option.address }}, {{ slotProps.option.country }}, {{ slotProps.option.city }}</p>
                                        <p class="m-0">{{ slotProps.option.notes }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </Listbox>
            </div>
        </div>
        <template #footer class="">
            <Button :disabled="selectedStablishment == undefined" @click="saveShippingAddress" label="Guardar" icon="pi pi-check" class="p-button-primary mx-auto" />
        </template>
    </Dialog>
</template>
  
<script setup>
    import { Dialog, RadioButton, Button, InputText, Listbox } from 'primevue';
    import { onMounted, watch, ref } from 'vue';
    import Producto from '../../components/Producto.vue';
    import useCheckout from '../../composables/checkout';
    import useShippingAddress from '../../composables/shippingAddress'; 
    import useProduct from '../../composables/products';
    import { useRoute } from 'vue-router';
    import { loadStripe } from '@stripe/stripe-js';
    import './Create.css';
    
    const route = useRoute();

    const {
        isSelledProduct,
        checkSelledProduct,
    } = useProduct();

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


    onMounted (async () => {
        getUserProduct();
        await checkSelledProduct(route.query.productId);
        getDistributionsCenters();

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


    // const stripe = await loadStripe(import.meta.env.VITE_STRIPE_PUBLIC_KEY);
    // const response = await fetch('/api/create-checkout-session', {
    // method: 'POST',
    // body: JSON.stringify({ amount: 1000 }),
    // });
    // const session = await response.json();

    // stripe.redirectToCheckout({ sessionId: session.id });
</script>
