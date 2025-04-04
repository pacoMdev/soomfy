<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">

                <div class="card-header bg-transparent ps-0 pe-0">
                    <h5 class="float-start mb-0">Gestor estados:</h5>
                </div>

                <DataTable  :value="estados.data" v-model:filters="filters" paginator :rows="15" stripedRows dataKey="id, name, created_at" size="small">

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
                                <Button v-if="can('category-create')"  @click="$router.push('states/create')" icon="pi pi-external-link" label="Crear Categoria" class="float-end" />
                            </template>
                        </Toolbar>
                    </template>

                    <template #empty> No states found. </template>

                    <Column field="id" header="ID" sortable>
                        <template #body="{ data }">
                            {{ data.id }}
                        </template>
                    </Column>
                    <Column field="name" header="name" sortable></Column>
                    <Column field="created_at" header="Created_at" sortable></Column>
                </DataTable>

            </div>
        </div>
    </div>


</template>

<script setup>
    import {ref, onMounted, watch} from "vue";
    import useEstados from "../../../composables/estados";
    import {useAbility} from '@casl/vue'
    import {FilterMatchMode} from "@primevue/core/api";

    const {estados, getEstados, deleteEstado} = useEstados()
    const {can} = useAbility()

    onMounted(() => {
        getEstados()
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
