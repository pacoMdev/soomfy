<template>
    <div class="admin-dashboard">
        <h1>Panel de Administrador</h1>
        
        <div class="grid mt-3">
            <!-- Tarjetas de estadísticas -->
            <div class="col-12 md:col-6 lg:col-3">
                <div class="card dashboard-card bg-blue-50">
                    <div class="flex justify-content-between">
                        <div>
                            <div class="text-900 font-medium text-xl">{{ stats.users }}</div>
                            <div class="text-600">Usuarios</div>
                        </div>
                        <div class="dashboard-icon bg-blue-100">
                            <i class="pi pi-users text-blue-600"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-green-500">+{{ stats.newUsers }}</span>
                        <span class="text-500"> nuevos esta semana</span>
                    </div>
                </div>
            </div>
            
            <div class="col-12 md:col-6 lg:col-3">
                <div class="card dashboard-card bg-green-50">
                    <div class="flex justify-content-between">
                        <div>
                            <div class="text-900 font-medium text-xl">{{ formatCurrency(stats.revenue) }}</div>
                            <div class="text-600">Ingresos</div>
                        </div>
                        <div class="dashboard-icon bg-green-100">
                            <i class="pi pi-money-bill text-green-600"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-green-500">+{{ stats.revenueGrowth }}%</span>
                        <span class="text-500"> desde el mes pasado</span>
                    </div>
                </div>
            </div>
            
            <div class="col-12 md:col-6 lg:col-3">
                <div class="card dashboard-card bg-orange-50">
                    <div class="flex justify-content-between">
                        <div>
                            <div class="text-900 font-medium text-xl">{{ stats.orders }}</div>
                            <div class="text-600">Pedidos</div>
                        </div>
                        <div class="dashboard-icon bg-orange-100">
                            <i class="pi pi-shopping-cart text-orange-600"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-green-500">{{ stats.pendingOrders }}</span>
                        <span class="text-500"> pendientes</span>
                    </div>
                </div>
            </div>
            
            <div class="col-12 md:col-6 lg:col-3">
                <div class="card dashboard-card bg-purple-50">
                    <div class="flex justify-content-between">
                        <div>
                            <div class="text-900 font-medium text-xl">{{ stats.products }}</div>
                            <div class="text-600">Productos</div>
                        </div>
                        <div class="dashboard-icon bg-purple-100">
                            <i class="pi pi-box text-purple-600"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-orange-500">{{ stats.lowStock }}</span>
                        <span class="text-500"> con stock bajo</span>
                    </div>
                </div>
            </div>
            
            <div class="col-12 lg:col-6">
                <div class="card">
                    <h5>Distribución de productos</h5>
                    <div class="products-distribution">
                        <div v-for="(category, index) in productCategories" :key="index" class="product-category">
                            <div class="category-info">
                                <span class="category-name">{{ category.name }}</span>
                                <span class="category-value">{{ category.percentage }}%</span>
                            </div>
                            <ProgressBar :value="category.percentage" :showValue="false" :class="'category-progress-' + (index + 1)" />
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tabla de actividades recientes -->
            <div class="col-12">
                <div class="card">
                    <h5>Actividad reciente</h5>
                    <DataTable :value="recentActivities" :rows="5" :paginator="true" 
                               class="p-datatable-sm" responsiveLayout="scroll">
                        <Column field="type" header="Tipo">
                            <template #body="slotProps">
                                <span :class="getActivityBadgeClass(slotProps.data.type)">{{ slotProps.data.type }}</span>
                            </template>
                        </Column>
                        <Column field="description" header="Descripción"></Column>
                        <Column field="user" header="Usuario"></Column>
                        <Column field="date" header="Fecha"></Column>
                        <Column header="Acciones" style="width: 100px">
                            <template #body>
                                <Button icon="pi pi-eye" class="p-button-rounded p-button-text" />
                            </template>
                        </Column>
                    </DataTable>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue';
// Eliminar la importación problemática de Chart
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Badge from 'primevue/badge';
import Checkbox from 'primevue/checkbox';
import ProgressBar from 'primevue/progressbar';

// Estadísticas del dashboard
const stats = reactive({
    users: 1458,
    newUsers: 28,
    revenue: 24750.60,
    revenueGrowth: 12.5,
    orders: 356,
    pendingOrders: 42,
    products: 195,
    lowStock: 8
});

// Actividades recientes
const recentActivities = ref([
    { type: 'Venta', description: 'Nueva venta completada #1089', user: 'María López', date: '15/04/2023 14:30' },
    { type: 'Usuario', description: 'Nuevo registro de usuario', user: 'Sistema', date: '15/04/2023 13:45' },
    { type: 'Producto', description: 'Stock actualizado de "Zapatillas Running"', user: 'Juan Pérez', date: '15/04/2023 11:20' },
    { type: 'Soporte', description: 'Ticket #452 resuelto', user: 'Ana Gómez', date: '14/04/2023 18:15' },
    { type: 'Sistema', description: 'Copia de seguridad completada', user: 'Sistema', date: '14/04/2023 01:00' },
    { type: 'Venta', description: 'Pedido #1088 cancelado', user: 'Carlos Ruiz', date: '13/04/2023 16:40' },
    { type: 'Producto', description: 'Nuevo producto añadido: "Smartwatch Pro"', user: 'Juan Pérez', date: '13/04/2023 14:30' }
]);

// Tareas pendientes
const pendingTasks = ref([
    { description: 'Revisar inventario de temporada', completed: false, priority: 'Alta' },
    { description: 'Aprobar pedidos pendientes', completed: false, priority: 'Alta' },
    { description: 'Responder correos de soporte', completed: false, priority: 'Media' },
    { description: 'Actualizar información de productos', completed: true, priority: 'Baja' },
    { description: 'Reunión con proveedores', completed: false, priority: 'Media' }
]);

// Notificaciones
const notifications = ref([
    { title: 'Stock bajo', message: '5 productos han alcanzado nivel crítico de stock', time: 'Hace 30 minutos', type: 'warning' },
    { title: 'Nueva reseña', message: 'Un cliente ha dejado una reseña de 5 estrellas', time: 'Hace 2 horas', type: 'success' },
    { title: 'Error de pago', message: 'Transacción #1088 ha fallado', time: 'Hace 3 horas', type: 'error' },
    { title: 'Mantenimiento programado', message: 'El sistema estará en mantenimiento esta noche', time: 'Hace 5 horas', type: 'info' }
]);

// Datos para la visualización de ventas
const salesMonths = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'];
const salesData2023 = [12500, 15000, 18200, 24750, 22300, 19800];
const salesData2022 = [10200, 12800, 15600, 17900, 20100, 18500];
const maxSales = computed(() => Math.max(...salesData2023, ...salesData2022));

// Datos para la distribución de productos
const productCategories = [
    { name: 'Electrónica', percentage: 35, color: '#42A5F5' },
    { name: 'Ropa', percentage: 25, color: '#66BB6A' },
    { name: 'Hogar', percentage: 20, color: '#FFA726' },
    { name: 'Deporte', percentage: 15, color: '#EC407A' },
    { name: 'Otros', percentage: 5, color: '#78909C' }
];

// Funciones para clases CSS
const getActivityBadgeClass = (type) => {
    const classes = {
        'Venta': 'activity-badge bg-green-100 text-green-800',
        'Usuario': 'activity-badge bg-blue-100 text-blue-800',
        'Producto': 'activity-badge bg-orange-100 text-orange-800',
        'Soporte': 'activity-badge bg-purple-100 text-purple-800',
        'Sistema': 'activity-badge bg-gray-100 text-gray-800'
    };
    return classes[type] || 'activity-badge bg-gray-100 text-gray-800';
};

const getPrioritySeverity = (priority) => {
    const severities = {
        'Alta': 'danger',
        'Media': 'warning',
        'Baja': 'info'
    };
    return severities[priority] || 'info';
};

const getNotificationIconClass = (type) => {
    const classes = {
        'warning': 'notification-icon bg-yellow-100 text-yellow-700',
        'success': 'notification-icon bg-green-100 text-green-700',
        'error': 'notification-icon bg-red-100 text-red-700',
        'info': 'notification-icon bg-blue-100 text-blue-700'
    };
    return classes[type] || 'notification-icon bg-gray-100 text-gray-700';
};

const getNotificationIcon = (type) => {
    const icons = {
        'warning': 'pi pi-exclamation-triangle',
        'success': 'pi pi-check-circle',
        'error': 'pi pi-times-circle',
        'info': 'pi pi-info-circle'
    };
    return icons[type] || 'pi pi-bell';
};

// Formatear moneda
const formatCurrency = (value) => {
    return new Intl.NumberFormat('es-ES', {
        style: 'currency',
        currency: 'EUR'
    }).format(value);
};

// Formatear moneda en formato corto
const formatShortCurrency = (value) => {
    if (value >= 1000) {
        return (value / 1000).toFixed(1) + 'k €';
    }
    return value + ' €';
};

onMounted(() => {
    console.log('Dashboard component mounted');
});
</script>

<style scoped>
.admin-dashboard {
    padding: 2.6rem 1rem 1rem 1rem;
}

h1 {
    margin-bottom: 1.5rem;
    color: var(--text-color);
}

.dashboard-card {
    padding: 1.25rem;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
}

.dashboard-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.dashboard-icon i {
    font-size: 1.5rem;
}

.activity-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.875rem;
    display: inline-block;
    white-space: nowrap;
}

.notification-item {
    padding: 0.75rem;
    transition: background-color 0.2s;
}

.notification-item:hover {
    background-color: var(--surface-hover);
}

.notification-icon {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.notification-icon i {
    font-size: 1.25rem;
}

/* Estilos para la visualización de ventas */
.sales-chart-container {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    height: 250px;
    margin: 1.5rem 0;
}

.sales-bar-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
}

.sales-bar-label {
    margin-top: 0.5rem;
    font-size: 0.8rem;
    color: var(--text-color-secondary);
}

.sales-bar-chart {
    display: flex;
    height: 200px;
    align-items: flex-end;
    width: 80%;
}

.sales-bar {
    width: 45%;
    margin: 0 2.5%;
    transition: height 0.3s ease;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
}

.current-year {
    background-color: #4CAF50;
}

.previous-year {
    background-color: #9E9E9E;
}

.sales-bar-value {
    font-size: 0.8rem;
    margin-top: 0.5rem;
    color: var(--text-color);
}

.sales-chart-legend {
    display: flex;
    justify-content: center;
    margin-top: 1rem;
}

.legend-item {
    display: flex;
    align-items: center;
    margin-right: 1.5rem;
}

.legend-color {
    width: 1rem;
    height: 1rem;
    margin-right: 0.5rem;
    border-radius: 3px;
}

.current-year-bg {
    background-color: #4CAF50;
}

.previous-year-bg {
    background-color: #9E9E9E;
}

/* Estilos para la distribución de productos */
.products-distribution {
    margin: 1.5rem 0;
}

.product-category {
    margin-bottom: 1rem;
}

.category-info {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.3rem;
}

.category-name {
    font-weight: 500;
}

.category-value {
    color: var(--text-color-secondary);
}

/* Colores personalizados para las barras de progreso */
:deep(.category-progress-1) .p-progressbar-value {
    background-color: #42A5F5;
}

:deep(.category-progress-2) .p-progressbar-value {
    background-color: #66BB6A;
}

:deep(.category-progress-3) .p-progressbar-value {
    background-color: #FFA726;
}

:deep(.category-progress-4) .p-progressbar-value {
    background-color: #EC407A;
}

:deep(.category-progress-5) .p-progressbar-value {
    background-color: #78909C;
}
</style>
