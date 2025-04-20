<template>
    <DataView :value="reviews" paginator :rows="5">
              <template #list="slotProps">
                  <div class="flex flex-column w-100">
                      <div v-for="(item, index) in slotProps.items" :key="index">
                          <div class="flex flex-col sm:flex-row sm:items-center p-6 gap-4 w-100" :class="{ 'border-t border-surface-200 dark:border-surface-700': index !== 0 }">
                            <div class="md:w-40 relative">
                                <img class="block xl:block mx-auto rounded w-full" :src="item.product.media[0]['original_url']" style="height: 80px!important; width: 80px!important; object-fit: contain; background-color: antiquewhite;" />
                            </div>
                            <div class="flex flex-row md:flex-col justify-between items-start gap-2">
                                <div>
                                    <div class="text-lg font-small mt-2" style="font-weight: 500;">{{ item.user.name }} {{ item.user.surname1 }}</div>
                                    <Rating :model-value="item.calification" readonly />
                                    <div class="text-lg font-medium mt-2">{{ item.title }}</div>
                                    <div class="text-lg font-medium mt-2">{{ item.destription }}</div>
                                </div>
                            </div>
                            <div class="flex flex-col md:flex-row justify-content-end md:items-center flex-1 gap-6 w-100">
                              <span class="font-medium text-surface-500 dark:text-surface-400 text-sm">{{ new Date(item.created_at).toLocaleDateString("es-ES", {day: "2-digit", month: "2-digit", year: "numeric", }) }}</span>

                                <div class="flex flex-col md:items-end gap-8">
                                    
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
    import { Rating } from 'primevue';

    const props = defineProps({
    reviews: Array,
    });
</script>
<style scoped>
</style>