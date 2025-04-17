<template>
    <DataView :value="historic" paginator :rows="5">
              <template #list="slotProps">
                  <div class="flex flex-column w-100">
                      <div v-for="(item, index) in slotProps.items" :key="index">
                          <div class="flex flex-col sm:flex-row sm:items-center p-6 gap-4 w-100" :class="{ 'border-t border-surface-200 dark:border-surface-700': index !== 0 }">
                            <div class="md:w-40 relative">
                                <img class="block xl:block mx-auto rounded w-full" :src="item.transaction.product.media[0]['original_url']" style="height: 80px!important; width: 80px!important; object-fit: contain; background-color: antiquewhite;" />
                            </div>
                            <div class="flex flex-row md:flex-col justify-between items-start gap-2">
                                <div>
                                    <span class="font-medium text-surface-500 dark:text-surface-400 text-sm">OTROS</span>
                                    <div v-if="authStore().user.email != item.transaction.seller.email" class="text-lg font-small mt-2">{{ item.transaction.seller.name }} {{ item.transaction.seller.surname1 }}</div>
                                    <div v-else class="text-lg font-small mt-2">{{ item.transaction.buyer.name }} {{ item.transaction.buyer.surname1 }}</div>
                                    <div class="text-lg font-medium mt-2">{{ item.transaction.product.title }}</div>
                                </div>
                            </div>
                            <div class="flex flex-col md:flex-row justify-content-end md:items-center flex-1 gap-6 w-100">
                              <span class="font-medium text-surface-500 dark:text-surface-400 text-sm">{{ new Date(item.transaction.created_at).toLocaleDateString("es-ES", {day: "2-digit", month: "2-digit", year: "numeric", }) }}</span>

                                <div class="flex flex-col md:items-end gap-8">
                                    <!-- VENTA A FAVOR ---------------------- -->
                                    <span v-if="item.transaction.finalPrice < item.transaction.initialPrice && authStore().user.email != item.transaction.seller.email" class="text-xl font-semibold" style="color: green;">
                                        <div class="pi pi-sort-amount-down"></div>
                                        {{ item.transaction.finalPrice }} €
                                    </span>
                                    <!-- VENTA A CONTRA ---------------------- -->
                                    <span v-else-if="item.transaction.finalPrice < item.transaction.initialPrice" class="text-xl font-semibold" style="color: red;">
                                        <div class="pi pi-sort-amount-down"></div>
                                        {{ item.transaction.finalPrice }} €
                                    </span>
                                    <span v-else class="text-xl font-semibold">${{ item.transaction.finalPrice }}</span>
                                    <Button v-if="fromUser" type="button" label="Estado" class="h-50" outlined @click="openHistoric(item)" />
                                    <Button asChild v-slot="slotProps">
                                      <RouterLink :to="'/products/detalle/' + item.transaction.product_id" :class="slotProps.class" icon="pi pi-eye" class="flex-auto md:flex-initial whitespace-nowrap h-50">Ver</RouterLink>
                                    </Button>
                                </div>
                            </div>
                          </div>
                      </div>
                  </div>
              </template>
          </DataView>
          <Dialog v-model:visible="showDialog" modal :header="'Historial del movimiento'" style=" width: min(90vw, 500px); height: min(90vh, 400px); " appendTo=".show">
            <div class="card">
                <Timeline :value="historicMove">
                    <template #marker="slotProps">
                        <span class="flex p-2 rounded-circle items-center justify-center text-black" :style="{ backgroundColor: '#C1F9F4' }">
                            <i :class="slotProps.item.icon"></i>
                        </span>
                    </template>
                    <template #opposite="slotProps">
                        <small class="text-surface-500 dark:text-surface-400">{{formatter.format(new Date(slotProps.item.created_at))}}</small>
                    </template>
                    <template #content="slotProps">
                        {{slotProps.item.status}}
                    </template>
                </Timeline>
            </div>
          </Dialog>
</template>
<script setup>

    import { defineProps, ref } from 'vue';
    import { DataView, Dialog, Timeline }  from 'primevue';
    import { authStore } from "../store/auth";
    import { boolean } from 'yup';
    import useTransactions from '../composables/transactions';


    const {
        historicMove,
        historicMovements,
    } = useTransactions();
    const props = defineProps({
    historic: Array,
    fromUser: boolean,
    });


    const openHistoric = async (historic) => {
        console.log('🔎 historic -->', historic);
        historicMovements(historic.tracking_number);
        showDialog.value = true; 
        };

    const showDialog = ref(false);
    const formatter = new Intl.DateTimeFormat('es-ES', {
    dateStyle: 'short',
    timeStyle: 'short',
});
</script>