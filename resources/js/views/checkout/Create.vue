<template>
    <div v-if="userProduct[0]?.toSend===1" class="d-flex flex-wrap justify-content-center gap-6 w-100  p-6">
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
                    <!-- AÑADIR MAS CAMPOS EN CASO DE ENVIAR A DIRECCION -->
                        <!-- Revisar la pagina con stripe(pagina de pagos) -->
                    <!-- <div class="d-flex flex-column">
                        <FloatLabel>
                            <label for="">Tipo de envio</label>
                            <Select v-model="typeShippment" :options="typeShippemt" optionLabel="name" checkmark :highlightOnSelect="false" class="w-full md:w-56" />
                        </FloatLabel>
                    </div> -->
                </div>
            </div>
            <template #footer class="">
                <Button label="Guardar" icon="pi pi-check" class="p-button-primary mx-auto" @click="saveAddress" />
            </template>
        </Dialog>
        <!-- Dialog añadir dirección ------------------------------------------------------------------------  -->
        <Dialog v-model:visible="showSelectCenter" modal header="Selecciona centro" :style="{ width: '26rem' }">
            <Maps  />
            <div class="d-flex flex-column gap-4 py-4">
                <div class="p-fluid">
                    <FloatLabel>
                        <InputText id="cp" v-model="address.newCp" inputId="withoutgrouping" :useGrouping="false" fluid />
                        <label for="cp">Introduce direccion</label>
                    </FloatLabel>
                </div>
            </div>
            <template #footer class="">
                <Button label="Guardar" icon="pi pi-check" class="p-button-primary mx-auto" />
            </template>
        </Dialog>
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
    import { Dialog, RadioButton, Button, InputText } from 'primevue';
    import { onMounted, watch } from 'vue';
    import Producto from '../../components/Producto.vue';
    import useCheckout from '../../composables/checkout';
    import Maps from '../../components/Map.vue'

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
    } = useCheckout();


    onMounted (() => {
        getUserProduct();
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