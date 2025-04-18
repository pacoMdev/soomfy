<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">

                <div class="card-header bg-transparent ps-0 pe-0">
                    <h5 class="float-start mb-0">Gestor establecimiento transaccion:</h5>
                </div>
                <DataTable  :value="tss.data" v-model:filters="filters" paginator :rows="15" stripedRows dataKey="id, transactions_id, shipping_address_id, status, tracking_number, created_at" size="small">

                    <template #header>

                        <Toolbar pt:root:class="toolbar-table">
                            <template #start>
                                <IconField >
                                    <InputIcon class="pi pi-search"> </InputIcon>
                                    <InputText v-model="filters['global'].value" placeholder="Buscar" class="mr-1"/>
                                </IconField>

                                <Button type="button" icon="pi pi-filter-slash" label="Clear" outlined @click="initFilters()" />
                                <Button type="button" icon="pi pi-refresh" class="h-100 ml-1" outlined @click="getGroups()" />
                            </template>

                            <template #end>
                                <Button v-if="can('establecimientoTransaction-create')"  @click="$router.push('transaction_stablishment/create')" icon="pi pi-external-link" label="Crear TS" class="float-end" />
                            </template>
                        </Toolbar>
                    </template>

                    <template #empty> No establecimiento found. </template>

                    <Column field="id" header="ID" sortable>
                        <template #body="{ data }">
                            {{ data.id }}
                        </template>
                    </Column>
                    <Column field="transactions_id" header="transactions_id" sortable></Column>
                    <Column field="shipping_address_id" header="shipping_address_id" sortable></Column>
                    <Column field="status" header="status" sortable></Column>
                    <Column field="tracking_number" header="tracking_number" sortable></Column>
                    <Column field="created_at" header="Created_at" sortable></Column>
                </DataTable>

            </div>
        </div>
    </div>


</template>

<script setup>
    import {ref, onMounted, watch} from "vue";
    import useTS from "../../../composables/shippingTransaction";
    import {useAbility} from '@casl/vue'
    import {FilterMatchMode} from "@primevue/core/api";

    const {
        tss,
        ts, 
        getTs, 
        deleteTs,
    } = useTS()
    const {can} = useAbility()

    onMounted(() => {
        getTs()
    })

    const filters = ref({
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },

    })

    const initFilters = () => {
        filters.value = {
            global: { value: null, matchMode: FilterMatchMode.CONTAINS },
        };
    }

</script>
