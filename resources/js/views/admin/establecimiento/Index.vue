<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">

                <div class="card-header bg-transparent ps-0 pe-0">
                    <h5 class="float-start mb-0">Gestor establecimientos:</h5>
                </div>
                <DataTable  :value="establecimientos.data" v-model:filters="filters" paginator :rows="15" stripedRows dataKey="id, address, cp, country, city, role_address, contact_name, contact_phone, contact_email, created_at" size="small">

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
                                <Button v-if="can('establecimiento-create')"  @click="$router.push('establecimientos/create')" icon="pi pi-external-link" label="Crear establecimiento" class="float-end" />
                            </template>
                        </Toolbar>
                    </template>

                    <template #empty> No establecimiento found. </template>

                    <Column field="id" header="ID" sortable>
                        <template #body="{ data }">
                            {{ data.id }}
                        </template>
                    </Column>
                    <Column field="address" header="address" sortable></Column>
                    <Column field="cp" header="cp" sortable></Column>
                    <Column field="country" header="country" sortable></Column>
                    <Column field="city" header="city" sortable></Column>
                    <Column field="role_address" header="role_address" sortable></Column>
                    <Column field="notes" header="notes" sortable></Column>
                    <Column field="contact_name" header="contact_name" sortable></Column>
                    <Column field="contact_phone" header="contact_phone" sortable></Column>
                    <Column field="contact_email" header="contact_email" sortable></Column>
                    <Column field="created_at" header="Created_at" sortable></Column>
                </DataTable>

            </div>
        </div>
    </div>


</template>

<script setup>
    import {ref, onMounted, watch} from "vue";
    import useEstablecimiento from "../../../composables/establecimiento";
    import {useAbility} from '@casl/vue'
    import {FilterMatchMode} from "@primevue/core/api";

    const {establecimientos, getEstablecimientos, deleteEstablecimiento} = useEstablecimiento()
    const {can} = useAbility()

    onMounted(() => {
        getEstablecimientos()
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
