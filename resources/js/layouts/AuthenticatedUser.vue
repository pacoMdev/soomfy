<template>
    <div class="layout-wrapper" :class="containerClass">
        <Navbar />

        <div class="">
            <Suspense>
                    <router-view></router-view>
                </Suspense>
        </div>
        <Footer />

        <div class="layout-mask"></div>
    </div>
</template>

<script setup>
import { computed, watch, ref } from 'vue';
import { authStore } from "../store/auth";

import { useRoute, useRouter } from "vue-router";
import Breadcrumb from 'primevue/breadcrumb';

import Navbar from './Nav.vue';
import Footer from './Footer.vue';
import { useLayout } from '../composables/layout';

const auth = authStore();
const user = computed(() => auth.user)
const route = useRoute();
const router = useRouter();

const home = ref({
    icon: 'pi pi-home',
    route: '/app'
});

const crumbs = computed(() => {
    let pathArray = route.path.split("/")
    pathArray.shift()

    let breadcrumbs = pathArray.reduce((breadcrumbArray, path, idx) => {

        breadcrumbArray.push({
            route: breadcrumbArray[idx - 1]
                ? "" + breadcrumbArray[idx - 1].route + "/" + path
                : "/" + path,
            label: route.matched[idx]?.meta.breadCrumb || path,
            disabled: idx + 1 === pathArray.length || route.matched[idx]?.meta.linked === false,
        });

        return breadcrumbArray;
    }, [])
    return breadcrumbs;
});


const items = ref([
    {
        separator: true
    },
    {
        label: 'Documents',
        items: [
            {
                label: 'Tareas',
                icon: 'pi pi-plus',
                // shortcut: '⌘+N',
                command: () => {
                    router.push({ name: 'app.tasks' })
                }
            },
            {
                label: 'Search',
                icon: 'pi pi-search',
                // shortcut: '⌘+S'
            }
        ]
    },
    // {
    //     label: 'Profile',
    //     items: [
    //         {
    //             label: 'Settings',
    //             icon: 'pi pi-cog',
    //             shortcut: '⌘+O'
    //         },
    //         {
    //             label: 'Messages',
    //             icon: 'pi pi-inbox',
    //             badge: 2
    //         },
    //         {
    //             label: 'Logout',
    //             icon: 'pi pi-sign-out',
    //             shortcut: '⌘+Q'
    //         }
    //     ]
    // },
    // {
    //     separator: true
    // }
]);

function selected(crumb) {
    //Console.log(crumb);
}

const { layoutConfig, layoutState, isSidebarActive } = useLayout();

const outsideClickListener = ref(null);

watch(isSidebarActive, (newVal) => {
    if (newVal) {
        bindOutsideClickListener();
    } else {
        unbindOutsideClickListener();
    }
});

const containerClass = computed(() => {
    return {
        'layout-theme-light': layoutConfig.darkTheme.value === 'light',
        'layout-theme-dark': layoutConfig.darkTheme.value === 'dark',
        'layout-overlay': layoutConfig.menuMode.value === 'overlay',
        'layout-static': layoutConfig.menuMode.value === 'static',
        'layout-static-inactive': layoutState.staticMenuDesktopInactive.value && layoutConfig.menuMode.value === 'static',
        'layout-overlay-active': layoutState.overlayMenuActive.value,
        'layout-mobile-active': layoutState.staticMenuMobileActive.value,
        'p-input-filled': layoutConfig.inputStyle.value === 'filled',
        'p-ripple-disabled': !layoutConfig.ripple.value
    };
});
const bindOutsideClickListener = () => {
    if (!outsideClickListener.value) {
        outsideClickListener.value = (event) => {
            if (isOutsideClicked(event)) {
                layoutState.overlayMenuActive.value = false;
                layoutState.staticMenuMobileActive.value = false;
                layoutState.menuHoverActive.value = false;
            }
        };
        document.addEventListener('click', outsideClickListener.value);
    }
};
const unbindOutsideClickListener = () => {
    if (outsideClickListener.value) {
        document.removeEventListener('click', outsideClickListener);
        outsideClickListener.value = null;
    }
};
const isOutsideClicked = (event) => {
    const sidebarEl = document.querySelector('.layout-sidebar');
    const topbarEl = document.querySelector('.layout-menu-button');

    return !(sidebarEl.isSameNode(event.target) || sidebarEl.contains(event.target) || topbarEl.isSameNode(event.target) || topbarEl.contains(event.target));
};

</script>

<style lang="scss">
.bread{
    padding:.1rem;
}

.menu {
    padding: 0;
    border: 0;
}

.menu ul {
    padding: 0;
    border: 0;
}

.layout-sidebar {
    padding: 0.5rem 0.5rem
}
</style>
