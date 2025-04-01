<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">

                <div class="card-header bg-transparent ps-0 pe-0">
                    <h5 class="float-start mb-0">Ejercicios</h5>
                </div>

                    <DataTable v-model:filters="filters" :value="transactions.data" paginator :rows="5"
                               :globalFilterFields="['id','userBuyer_id', 'userSeller_id','product_id','initialPrice','finalPrice','created_at','isToSend', 'isRegated']" stripedRows dataKey="id" size="small">

                        <template #header>
                            <Toolbar pt:root:class="toolbar-table">
                                <template #start>
                                    <IconField >
                                        <InputIcon class="pi pi-search"> </InputIcon>
                                        <InputText v-model="filters['global'].value" placeholder="Buscar" />
                                    </IconField>

                                    <Button type="button" icon="pi pi-filter-slash" label="Clear" class="ml-1" outlined @click="initFilters()" />
                                    <Button type="button" icon="pi pi-refresh" class="h-100 ml-1" outlined @click="getTransactions()" />
                                </template>
                                <template #end>
                                    <Button v-if="can('users-create')" icon="pi pi-external-link" label="Crear Usuario" @click="$router.push('transactions/create')" class="float-end" />
                                </template>
                            </Toolbar>
                            <!--
                            <div class="flex justify-content-between flex-column sm:flex-row">

                                <Button icon="pi pi-external-link" label="Export" @click="exportCSV($event)" />

                                <div class="float-end">

                                </div>

                            </div>
                            -->
                        </template>

                        <template #empty> No transactions found. </template>

                        <Column field="id" header="ID" sortable></Column>
                        <Column field="userSeller_id" header="UserSeller_id" sortable></Column>
                        <Column field="userBuyer_id" header="UserBuyer_id" sortable></Column>
                        <Column field="product_id" header="Product_id" sortable></Column>
                        <Column field="initialPrice" header="InitialPrice" sortable></Column>
                        <Column field="finalPrice" header="FinalPrice" sortable></Column>
                        <Column field="isToSend" header="IsToSend" sortable></Column>
                        <Column field="isRegated" header="IsRegated" sortable></Column>
                        <Column field="created_at" header="Created_at" sortable></Column>

<!--                        <Column header="products" sortable-->
<!--                                sortField="products.name"-->
<!--                                filterField="products"-->
<!--                                :showFilterMatchModes="false">-->
<!--                            <template #body="slotProps">-->
<!--                            <span v-for="cat in slotProps.data.products" class="ms-2 badge  bg-secondary bg-gradient">-->
<!--                                {{ cat.name }}-->
<!--                            </span>-->
<!--                            </template>-->

<!--                        </Column>-->

                        <Column class="pe-0 me-0 icon-column-2">
                            <template #body="slotProps">

<!--                                <router-link :to="{ name: 'users.tasks', params: { id: slotProps.data.id } }">-->
<!--                                    <Button icon="pi pi-eye"  severity="help" size="small" class="mr-1"/>-->
<!--                                </router-link>-->

                                <!-- <router-link v-if="can('user-edit')" :to="{ name: 'transactions.edit', params: { id: slotProps.data.id } }">
                                    <Button icon="pi pi-pencil" severity="info" size="small" class="mr-1"/>
                                </router-link> -->

                                <Button icon="pi pi-trash" severity="danger" v-if="can('user-delete')" @click.prevent="deleteTransaction(slotProps.data.id, slotProps.index)" size="small"/>

                            </template>
                        </Column>

                    </DataTable>

            </div>
        </div>
    </div>
</template>

<script setup>
import {ref, onMounted} from "vue";
import useUsers from "../../../composables/users";
import useTransactions from "../../../composables/transactions";
import {useAbility} from '@casl/vue'
import {FilterMatchMode, FilterService} from "@primevue/core/api";

const {transactions, getTransactions, deleteTransaction, resetTransactionDB} = useTransactions()
const {can} = useAbility()

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const initFilters = () => {
    filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS }
    };
};

onMounted(() => {
    getTransactions()
})

</script>
