<template>
    <div v-if="userProduct[0]?.toSend===1" class="d-flex flex-wrap justify-content-center gap-6 w-100  p-6">
        <div class="shadow rounded p-6 gap-5 m-0 container-opinion">
            <h4>Comprando</h4>
            <form @submit.prevent="submitPurchaseForm" class="d-flex flex-column gap-4">
                <div class="d-flex flex-column gap-5">
                    <div class="flex flex-column gap-4">
                        <div class="flex justify-between gap-2 items-center">
                            <div>
                                <div class="d-flex gap-3">
                                    <i class="pi pi-home" style="font-size: 2rem"></i>
                                    <p style="font-size: 1.1rem">Entrega en mi dirección 4,78 €</p>
                                </div>
                            </div>
                            <RadioButton v-model="selectedMethod" :inputId="1" name="dynamic" value="1" />
                        </div>

                        <Button label="Añadir dirección" icon="pi pi-plus" class="p-button-text" @click="showDialog = true" />

                        <!-- Dialog para añadir dirección -->
                        <Dialog v-model:visible="showDialog" modal header="Añadir dirección" :style="{ width: '26rem' }">
                            <div class="d-flex flex-column gap-4 py-4">
                                <div class="p-fluid">
                                    <FloatLabel>
                                        <InputText id="address" v-model="newAddress" fluid />
                                        <label for="address">Dirección</label>
                                    </FloatLabel>
                                </div>
                                <div class="p-fluid">
                                    <FloatLabel>
                                        <InputText id="city" v-model="newCity" fluid/>
                                        <label for="city">Ciudad</label>
                                    </FloatLabel>
                                </div>
                                <div class="p-fluid">
                                    <FloatLabel>
                                        <InputText id="cp" v-model="newCp" inputId="withoutgrouping" :useGrouping="false" fluid />
                                        <label for="cp">Codigo postal</label>
                                    </FloatLabel>
                                </div>
                                <div class="p-fluid">
                                    <FloatLabel>
                                        <InputText id="country" v-model="newCountry" fluid />
                                        <label for="country">Pais</label>
                                    </FloatLabel>
                                </div>
                            </div>
                            <template #footer class="">
                                <Button label="Guardar" icon="pi pi-check" class="p-button-primary mx-auto" @click="saveAddress" />
                            </template>
                        </Dialog>
                        <div class="flex justify-content-between w-100 gap-2">
                            <div class="d-flex gap-3">
                                <i class="pi pi-map-marker" style="font-size: 2rem"></i>
                                <p style="font-size: 1.1rem">Compra en persona</p>
                            </div>
                            <RadioButton v-model="selectedMethod" :inputId="0" name="dynamic" value="0" />
                        </div>
                    </div> 
                </div>
                <Button label="Comprar" type="submit" class="w-full" />
                <p v-if="error" class="text-red-500 text-center mt-2" style="color: red!important;">Por favor, completa la dirección antes de continuar.</p>

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
        <h1>Este producto no es para enviar</h1>
    </div>
</template>
  
<script setup>
    import { Dialog, RadioButton, Button } from 'primevue';
    import InputText from 'primevue/inputtext';
    import { useRoute } from 'vue-router';
    import { onMounted, ref, watch } from 'vue';
    import Producto from '../../components/Producto.vue';


    const route = useRoute();
    const productId = route.query.productId;
    console.log('🔎 productId -->', productId);

    const userProduct = ref({});

    const selectedMethod = ref('1');
    const showDialog = ref(false);
    const newAddress = ref('');
    const newCity = ref('');
    const newCp = ref('');
    const newCountry = ref('');
    const error = ref(false);
    const googleApiKey = import.meta.env.VITE_GOOGLE_API_KEY;


    onMounted (() => {
        getUserProduct();
        console.log('API KEY -->', googleApiKey);
    });


    watch(newCp, async (cp) => {
        if (cp.length === 5) {
            try {
                const response = await axios.get('/api/geocode', {
                    params: { address: cp,}
                });
                const components = response.data.results[0]?.address_components;
                if (components) {
                    newCity.value = components.find(c => c.types.includes('locality'))?.long_name || '';
                    newCountry.value = components.find(c => c.types.includes('country'))?.long_name || '';
                }
            } catch (error) {
                console.error("Eror en api de GOOGLE -->", error);
            }
        }
    });


    const getUserProduct = async () => {
        try {
            const respuesta = await axios.get('/api/products/'+productId);
            userProduct.value[0] = respuesta.data.data || {};
            console.log('UserProduct -->', userProduct);
        } catch (error) {
            console.error("Error al valorar -->", error);
        }
    };
    const submitPurchaseForm = async () => {
        // check direccion de envio
        if (selectedMethod.value === '1'){
            if (!newAddress.value || !newCity.value || !newCp.value || !newCountry.value){
                error.value = true;
            return;
            }
        }
        // check ok
        error.value = false;
        try {
            const respuesta = await axios.post('/api/fakePurchaseProduct', {
                product_id: parseInt(productId),
                userSeller_id: userProduct.value[0].user.id,
                price: userProduct.value[0].price,
                isToSend: parseInt(selectedMethod.value),
                shippingAddress: {
                    'newAddress': newAddress.value,
                    'newCity': newCity.value,
                    'newCp': newCp.value,
                    'newCountry': newCountry.value
                }
            });
            console.log('Producto comprado', respuesta);
        } catch (error) {
            console.error("Error al valorar -->", error);
        }
    };
    const saveAddress = () => {
        showDialog.value = false;
    };
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