import { authStore } from "../store/auth";

const AuthenticatedLayout = () => import('../layouts/Authenticated.vue')
const AuthenticatedUserLayout = () => import('../layouts/AuthenticatedUser.vue')
const GuestLayout = ()  => import('../layouts/Guest.vue');
const ProductsIndex  = ()  => import('../views/admin/products/Index.vue');
const ProductsCreate  = ()  => import('../views/admin/products/Create.vue');
const ProductsEdit  = ()  => import('../views/admin/products/Edit.vue');

const ProductsUserCreate  = ()  => import('../views/user/products/Create.vue');
const ProductsUserEdit  = ()  => import('../views/admin/products/Edit.vue');

async function requireLogin(to, from, next) {
    const auth = authStore();
    let isLogin = !!auth.authenticated;
    console.log("¿Guard ejecutado? Autenticado:", isLogin); // Agrega este log para depurar

    if (isLogin) {
        next()
    } else {
        next('/login')
    }
}

function hasAdmin(roles) {
    for (let rol of roles) {
        if (rol.name && rol.name.toLowerCase().includes('admin')) {
            return true;
        }
    }
    return false;
}
async function guest(to, from, next) {
    const auth = authStore()

    let isLogin = !!auth.authenticated;

    if (isLogin) {
        next('/')
    } else {
        next()
    }
}

async function requireAdmin(to, from, next) {

    const auth = authStore();
    let isLogin = !!auth.authenticated;
    let user = auth.user;

    if (isLogin) {
        if( hasAdmin(user.roles)){
            next()
        }else{
            next('/app/profile')
        }
    } else {
        next('/login')
    }
}

export default [
    {
        path: '/',
        // redirect: { name: 'login' },
        component: GuestLayout,
        children: [
            { 
                // Si intentan entrar sin la sesion iniciada, de momento lo redirigiremos al login (TEMPORAL)
                path: 'cuenta',
                name: 'cuenta',
                component: () => import('../views/login/Login.vue'),
            },
            {
                path: '/',
                name: 'home',
                component: () => import('../views/home/index.vue'),
            },
            {
                path: 'products',
                name: 'public.products',
                component: () => import('../views/products/products.vue'),
            },
            {
                // Pagina de detalle del producto vendido
                path: 'products/detalle/:id',
                name: 'detalle-producto',
                component: () => import('../views/detalle_producto/detalle_producto.vue'),
            },
            {
                path: 'profile/detalle/:id',
                name: 'detalle-profile',
                component: () => import('../views/perfil/Perfil.vue'),
            },
            {
                path: 'category/:id',
                name: 'category-products.index',
                component: () => import('../views/category/products.vue'),
            },
            {
                path: 'login',
                name: 'auth.login',
                component: () => import('../views/login/Login.vue'),
                beforeEnter: guest,
            },
            {
                path: 'register',
                name: 'auth.register',
                component: () => import('../views/register/index.vue'),
                beforeEnter: guest,
            },
            {
                path: 'forgot-password',
                name: 'auth.forgot-password',
                component: () => import('../views/auth/passwords/Email.vue'),
                beforeEnter: guest,
            },
            {
                path: 'reset-password/:token',
                name: 'auth.reset-password',
                component: () => import('../views/auth/passwords/Reset.vue'),
                beforeEnter: guest,
            },
            {
                path: 'auth/callback',
                name: 'auth.callback',
                component: () => import('../views/auth/callback.vue'),
                beforeEnter: guest,
            },
        ]
    },

    {
        path: '/app',
        component: AuthenticatedUserLayout,
        // redirect: {
        //     name: 'admin.index'
        // },
        name: 'app',
        beforeEnter: requireLogin,
        meta: { breadCrumb: 'Dashboard' },
        children: [
            // Subir producto
            {
                path: 'profile',
                name: 'cuenta',
                component: () => import('../views/user/perfil/perfil.vue'),
            },
            {
                path: '/subir-producto',
                name: 'subir producto',
                component: () => import('../views/user/products/Create.vue')
            },
            {
                path: '/actualizar-producto',
                name: 'actualizar producto',
                component: () => import('../views/user/products/Create.vue'),
            },
            {
                path: '/chat',
                name: 'chat',
                component: () => import('../views/user/chat/chat.vue'),
            },
            {
                path: '/favoritos',
                name: 'favoritos',
                component: () => import('../views/user/favoritos/favoritos.vue'),
            },
            {
                path: '/opinion',
                name: 'opinion',
                component: () => import('../views/user/valoration/Create.vue'),
            },
            {
                path: '/app/checkout',
                name: 'checkout',
                component: () => import('../views/checkout/Create.vue'),
            },
            {
                name: 'products',
                path: 'products',
                meta: { breadCrumb: 'Products'},
                children: [
                    {
                        name: 'auth.products',
                        path: 'products',
                        component: ProductsIndex,
                        meta: { breadCrumb: 'Products' }
                    },
                    {
                        name: 'auth.products.create',
                        path: 'products/create',
                        component: ProductsUserCreate,
                        meta: { breadCrumb: 'Add new product' }
                    },
                    {
                        name: 'auth.products.edit',
                        path: 'products/edit/:id',
                        component: ProductsUserEdit,
                        meta: { breadCrumb: 'Edit product' }
                    },
                ]
            }
        ]
    },


    {
        path: '/admin',
        component: AuthenticatedLayout,
        // redirect: {
        //     name: 'admin.index'
        // },
        beforeEnter: requireAdmin,
        meta: { breadCrumb: 'Dashboard' },
        children: [
            {
                name: 'admin.index',
                path: '',
                component: () => import('../views/admin/index.vue'),
                meta: { breadCrumb: 'Admin' }
            },
            {
                name: 'profile',
                path: '',
                component: () => import('../views/user/perfil/perfil.vue'),
                meta: { breadCrumb: 'Perfil' }
            },
            {
                name: 'products',
                path: 'products',
                meta: { breadCrumb: 'Products'},
                children: [
                    {
                        name: 'products.index',
                        path: '',
                        component: () => import('../views/admin/products/Index.vue'),
                        meta: { breadCrumb: 'View category' }
                    },
                    {
                        name: 'products.create',
                        path: 'create',
                        component: () => import('../views/admin/products/Create.vue'),
                        meta: {
                            breadCrumb: 'Add new category' ,
                            linked: false,
                        }
                    },
                    {
                        name: 'products.edit',
                        path: 'edit/:id',
                        component: () => import('../views/admin/products/Edit.vue'),
                        meta: {
                            breadCrumb: 'Edit category',
                            linked: false,
                        }
                    }

                ]
            },
            {
                name: 'profile.index',
                path: 'profile',
                component: () => import('../views/admin/profile/index.vue'),
                meta: { breadCrumb: 'Profile' }
            },
            {
                name: 'categories',
                path: 'categories',
                meta: { breadCrumb: 'Categories'},
                children: [
                    {
                        name: 'categories.index',
                        path: '',
                        component: () => import('../views/admin/categories/Index.vue'),
                        meta: { breadCrumb: 'View category' }
                    },
                    {
                        name: 'categories.create',
                        path: 'create',
                        component: () => import('../views/admin/categories/Create.vue'),
                        meta: {
                            breadCrumb: 'Add new category' ,
                            linked: false,
                        }
                    },
                    {
                        name: 'categories.edit',
                        path: 'edit/:id',
                        component: () => import('../views/admin/categories/Edit.vue'),
                        meta: {
                            breadCrumb: 'Edit category',
                            linked: false,
                        }
                    }
                ]
            },
            {
                name: 'permissions',
                path: 'permissions',
                meta: { breadCrumb: 'Permission'},
                children: [
                    {
                        name: 'permissions.index',
                        path: '',
                        component: () => import('../views/admin/permissions/Index.vue'),
                        meta: { breadCrumb: 'Permissions' }
                    },
                    {
                        name: 'permissions.create',
                        path: 'create',
                        component: () => import('../views/admin/permissions/Create.vue'),
                        meta: {
                            breadCrumb: 'Create Permission',
                            linked: false,
                        }
                    },
                    {
                        name: 'permissions.edit',
                        path: 'edit/:id',
                        component: () => import('../views/admin/permissions/Edit.vue'),
                        meta: {
                            breadCrumb: 'Permission Edit',
                            linked: false,
                        }
                    }
                ]
            },
            {
                name: 'users',
                path: 'users',
                meta: { breadCrumb: 'Users'},
                children: [
                    {
                        name: 'users.index',
                        path: '',
                        component: () => import('../views/admin/users/Index.vue'),
                        meta: { breadCrumb: 'Users' }
                    },
                    {
                        name: 'users.create',
                        path: 'create',
                        component: () => import('../views/admin/users/Create.vue'),
                        meta: {
                            breadCrumb: 'Create Users',
                            linked: false
                        }
                    },
                    {
                        name: 'users.edit',
                        path: 'edit/:id',
                        component: () => import('../views/admin/users/Edit.vue'),
                        meta: {
                            breadCrumb: 'Edit Users',
                            linked: false
                        }
                    }
                ]
            },
            {
                name: 'transactions',
                path: 'transactions',
                meta: { breadCrumb: 'Transactions'},
                children: [
                    {
                        name: 'transactions.index',
                        path: '',
                        component: () => import('../views/admin/transactions/Index.vue'),
                        meta: { breadCrumb: 'Transactions' }
                    },
                    {
                        name: 'transactions.create',
                        path: 'create',
                        component: () => import('../views/admin/transactions/Create.vue'),
                        meta: {
                            breadCrumb: 'Create Transactions',
                            linked: false
                        }
                    },
                    {
                        name: 'transactions.edit',
                        path: 'edit/:id',
                        component: () => import('../views/admin/transactions/Edit.vue'),
                        meta: {
                            breadCrumb: 'Edit Transactions',
                            linked: false
                        }
                    }
                ]
            },
            {
                name: 'states',
                path: 'states',
                meta: { breadCrumb: 'States'},
                children: [
                    {
                        name: 'states.index',
                        path: '',
                        component: () => import('../views/admin/states/Index.vue'),
                        meta: { breadCrumb: 'States' }
                    },
                    {
                        name: 'states.create',
                        path: 'create',
                        component: () => import('../views/admin/states/Create.vue'),
                        meta: {
                            breadCrumb: 'Create States',
                            linked: false
                        }
                    },
                    {
                        name: 'states.edit',
                        path: 'edit/:id',
                        component: () => import('../views/admin/states/Edit.vue'),
                        meta: {
                            breadCrumb: 'Edit States',
                            linked: false
                        }
                    }
                ]
            },
            {
                name: 'opinions',
                path: 'opinions',
                meta: { breadCrumb: 'Opinions'},
                children: [
                    {
                        name: 'opinions.index',
                        path: '',
                        component: () => import('../views/admin/opinions/Index.vue'),
                        meta: { breadCrumb: 'Opinions' }
                    },
                    {
                        name: 'opinions.create',
                        path: 'create',
                        component: () => import('../views/admin/opinions/Create.vue'),
                        meta: {
                            breadCrumb: 'Create Opinions',
                            linked: false
                        }
                    },
                    {
                        name: 'opinions.edit',
                        path: 'edit/:id',
                        component: () => import('../views/admin/opinions/Edit.vue'),
                        meta: {
                            breadCrumb: 'Edit Opinions',
                            linked: false
                        }
                    }
                ]
            },



            //TODO Organizar rutas
            {
                name: 'roles.index',
                path: 'roles',
                component: () => import('../views/admin/roles/Index.vue'),
                meta: { breadCrumb: 'Roles' }
            },
            {
                name: 'roles.create',
                path: 'roles/create',
                component: () => import('../views/admin/roles/Create.vue'),
                meta: { breadCrumb: 'Create Role' }
            },
            {
                name: 'roles.edit',
                path: 'roles/edit/:id',
                component: () => import('../views/admin/roles/Edit.vue'),
                meta: { breadCrumb: 'Role Edit' }
            },

        ]
    },
    {
        path: "/:pathMatch(.*)*",
        name: 'NotFound',
        component: () => import("../views/errors/404.vue"),
    },
];
