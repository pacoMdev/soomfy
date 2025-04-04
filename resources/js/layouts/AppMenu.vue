
<template>
    <button class="relative overflow-hidden w-full p-link flex align-items-center p-2 pl-0 text-color hover:surface-200 border-noround">
        
        <Avatar :image="authStore().user?.avatar" class="mr-3" shape="circle" />
        <span class="inline-flex flex-column">
            <span class="font-bold">{{ authStore().user?.name }}</span>
            <span>
                <span v-for="rol in authStore().user?.roles" class="text-sm mr-2">{{rol.name}}</span>
            </span>
        </span>
    </button>

    <ul class="layout-menu">
        <template v-for="(item, i) in model" :key="item">
            <app-menu-item v-if="!item.separator" :item="item" :index="i"></app-menu-item>
            <li v-if="item.separator" class="menu-separator"></li>
        </template>
    </ul>
</template>

<script setup>
import { ref } from 'vue';
import AppMenuItem from './AppMenuItem.vue';
import {useAbility} from '@casl/vue'
import { authStore } from "../store/auth";

const {can} = useAbility();
const auth = authStore();
//const user = computed(() => auth.user.value)
//console.log(auth.user);
const model = ref([
    {
        label: 'Home',
        permision: 'all',
        items: [{ label: 'Dashboard', icon: 'pi pi-fw pi-home', to: '/admin', permision: 'all'}]
    },
    {
        label: 'Usuarios',
        items: [
            { label: 'Users', icon: 'pi pi-fw pi-id-card', to: '/admin/users', permision: 'user-list' },
            { label: 'Roles', icon: 'pi pi-fw pi-check-square', to: '/admin/roles', permision:'role-list' },
            { label: 'Permisos', icon: 'pi pi-fw pi-bookmark', to: '/admin/permissions', permision:'permission-list' }
        ]
    },
    {
        label: 'Products',
        items: [
            { label: 'Products', icon: 'pi pi-fw pi-shopping-cart', to: '/admin/products', permision: 'product-list' },
            { label: 'Categorias', icon: 'pi pi-fw pi-tags', to: '/admin/categories', permision: 'all' },
            { label: 'Estados', icon: 'pi pi-fw pi-check-circle', to: '/admin/states', permision: 'all' },
            { label: 'Transactions', icon: 'pi pi-truck', to: '/admin/transactions', permision: 'all' },
            { label: 'Opinions', icon: 'pi pi-comment', to: '/admin/opinions', permision: 'all' },

        ]
    }
]);
</script>


<style lang="scss" scoped></style>
