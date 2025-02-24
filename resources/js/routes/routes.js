import { authStore } from "../store/auth";

const AuthenticatedLayout = () => import('../layouts/Authenticated.vue')
const AuthenticatedUserLayout = () => import('../layouts/AuthenticatedUser.vue')
const GuestLayout = ()  => import('../layouts/Guest.vue');
const PostsIndex  = ()  => import('../views/admin/posts/Index.vue');
const PostsCreate  = ()  => import('../views/admin/posts/Create.vue');
const PostsEdit  = ()  => import('../views/admin/posts/Edit.vue');

async function requireLogin(to, from, next) {
    const auth = authStore();
    let isLogin = !!auth.authenticated;

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
            next('/app')
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
                path: 'productos',
                name: 'productos',
                component: () => import('../views/productos/productos.vue'),
            },
            {
                path: 'productos/:search',
                name: 'Filtro buscador',
                component: () => import('../views/productos/productos.vue'),
                props: true
            },
            {
                // Pagina de detalle del producto vendido
                path: 'productos/detalle/:id',
                name: 'detalle-producto',
                component: () => import('../views/detalle_producto/detalle_producto.vue'),
            },
            {
                path: 'category/:id',
                name: 'category-productos.index',
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
                path: '',
                name: 'cuenta',
                component: () => import('../views/user/perfil/perfil.vue'),
            },
            {
                path: 'subir-producto',
                name: 'subir producto',
                component: () => import('../views/user/productos/Create.vue')
            },
            {
                path: 'actualizar-producto',
                name: 'actualizar producto',
                component: () => import('../views/user/productos/Create.vue'),
            },
            {
                path: 'chat',
                name: 'chat',
                component: () => import('../views/user/chat/chat.vue'),
            },
            {
                path: 'favoritos',
                name: 'favoritos',
                component: () => import('../views/user/favoritos/favoritos.vue'),
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
                name: 'profile.index',
                path: 'profile',
                component: () => import('../views/admin/profile/index.vue'),
                meta: { breadCrumb: 'Profile' }
            },
            {
                name: 'productos.index',
                path: 'posts',
                component: PostsIndex,
                meta: { breadCrumb: 'Posts' }
            },
            {
                name: 'productos.create',
                path: 'productos/create',
                component: PostsCreate,
                meta: { breadCrumb: 'Add new post' }
            },
            {
                name: 'productos.edit',
                path: 'productos/edit/:id',
                component: PostsEdit,
                meta: { breadCrumb: 'Edit post' }
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
                meta: { breadCrumb: 'Permisos'},
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
                meta: { breadCrumb: 'Usuarios'},
                children: [
                    {
                        name: 'users.index',
                        path: '',
                        component: () => import('../views/admin/users/Index.vue'),
                        meta: { breadCrumb: 'Usuarios' }
                    },
                    {
                        name: 'users.create',
                        path: 'create',
                        component: () => import('../views/admin/users/Create.vue'),
                        meta: {
                            breadCrumb: 'Crear Usuario',
                            linked: false
                        }
                    },
                    {
                        name: 'users.edit',
                        path: 'edit/:id',
                        component: () => import('../views/admin/users/Edit.vue'),
                        meta: {
                            breadCrumb: 'Editar Usuario',
                            linked: false
                        }
                    }
                ]
            },
            {
                name: 'authors',
                path: 'authors',
                meta: { breadCrumb: 'Autores'},
                children: [
                    {
                        name: 'authors.index',
                        path: '',
                        component: () => import('../views/admin/authors/index.vue'),
                        meta: { breadCrumb: 'Listado' }
                    },
                    {
                        name: 'authors.edit',
                        path: 'edit/:id',
                        component: () => import('../views/admin/authors/edit.vue'),
                        meta: { breadCrumb: 'Editar autores' }
                    }
                ]
            },
            {
                name: 'posts',
                path: 'posts',
                meta: { breadCrumb: 'Autores'},
                children: [
                    {
                        name: 'productos.index',
                        path: '',
                        component: () => import('../views/admin/posts/Index.vue'),
                        meta: { breadCrumb: 'Listado' }
                    },
                    {
                        name: 'productos.edit',
                        path: 'edit/:id',
                        component: () => import('../views/admin/posts/Edit.vue'),
                        meta: { breadCrumb: 'Editar autores' }
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
