<template>
    <DataView :value="historic" paginator :rows="5">
              <template #list="slotProps">
                  <div class="flex flex-column w-100">
                      <div v-for="(item, index) in slotProps.items" :key="index">
                          <div class="flex flex-col sm:flex-row sm:items-center p-6 gap-4 w-100" :class="{ 'border-t border-surface-200 dark:border-surface-700': index !== 0 }">
                            <div class="md:w-40 relative">
                                <img class="block xl:block mx-auto rounded w-full" :src="item.product.media[0]['original_url']" style="height: 80px!important; width: 80px!important; object-fit: contain; background-color: antiquewhite;" />
                            </div>
                            <div class="flex flex-row md:flex-col justify-between items-start gap-2">
                                <div>
                                    <span class="font-medium text-surface-500 dark:text-surface-400 text-sm">OTROS</span>
                                    <div v-if="authStore().user.email != item.seller.email" class="text-lg font-small mt-2">{{ item.seller.name }} {{ item.seller.surname1 }}</div>
                                    <div v-else class="text-lg font-small mt-2">{{ item.buyer.name }} {{ item.buyer.surname1 }}</div>
                                    <div class="text-lg font-medium mt-2">{{ item.product.title }}</div>
                                </div>
                            </div>
                            <div class="flex flex-col md:flex-row justify-content-end md:items-center flex-1 gap-6 w-100">
                              <span class="font-medium text-surface-500 dark:text-surface-400 text-sm">{{ new Date(item.created_at).toLocaleDateString("es-ES", {day: "2-digit", month: "2-digit", year: "numeric", }) }}</span>

                                <div class="flex flex-col md:items-end gap-8">
                                    <!-- VENTA A FAVOR ---------------------- -->
                                    <span v-if="item.finalPrice < item.initialPrice && authStore().user.email != item.seller.email" class="text-xl font-semibold" style="color: green;">
                                        <div class="pi pi-sort-amount-down"></div>
                                        {{ item.finalPrice }} €
                                    </span>
                                    <!-- VENTA A CONTRA ---------------------- -->
                                    <span v-else-if="item.finalPrice < item.initialPrice" class="text-xl font-semibold" style="color: red;">
                                        <div class="pi pi-sort-amount-down"></div>
                                        {{ item.finalPrice }} €
                                    </span>
                                    <span v-else class="text-xl font-semibold">${{ item.finalPrice }}</span>
                                    <Button asChild v-slot="slotProps">
                                      <RouterLink :to="'/products/detalle/' + item.product_id" :class="slotProps.class" icon="pi pi-eye" class="flex-auto md:flex-initial whitespace-nowrap">Ver</RouterLink>
                                    </Button>
                                </div>
                            </div>
                          </div>
                      </div>
                  </div>
              </template>
          </DataView>
</template>
<script setup>

    import { defineProps } from 'vue';
    import DataView from 'primevue/dataview';
    import { authStore } from "../store/auth";

    const props = defineProps({
    historic: Array,
    });
</script>
<style scoped>
</style>