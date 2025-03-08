import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'

export default function useProducts() {
    const products = ref({data:[]})
    const estadoList = ref([])
    const product = ref({
        title: '',
        content: '',
        category_id: '',
        price: '',
        estado: '',
        thumbnails: '',
    })
    const router = useRouter()
    const validationErrors = ref({})
    const isLoading = ref(false)
    const swal = inject('$swal')

    const getProducts = async (
        page = 1,
        search_category = '',
        search_id = '',
        search_title = '',
        min_price = '',
        max_price = '',
        search_estado = '',
        search_location = '',
        search_content = '',
        search_global = '',
        order_column = 'created_at',
        order_direction = 'desc',
        order_date = 'created_at',
        order_price = '',
    ) => {
        axios.get('/api/products?page=' + page +
            '&search_category=' + search_category +
            '&search_id=' + search_id +
            '&min_price=' + min_price +
            '&max_price=' + max_price +
            '&search_estado=' + search_estado +
            '&search_location=' + search_location +
            '&search_content=' + search_content +
            '&search_global=' + search_global +
            '&order_column=' + order_column +
            '&order_direction=' + order_direction +
            '&order_date=' + order_date +
            '&order_price=' + order_price)
            .then(response => {
                console.log(response.data);
                products.value = response.data;
            })

    }

    const getProduct = async (id) => {
        axios.get('/api/products/' + id)
            .then(response => {
                product.value = response.data.data;
            })
    }

    const storeProduct = async (formData) => {
        // Verificar contenido
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }

        if (isLoading.value) return;
        isLoading.value = true;
        validationErrors.value = {};

        const response = await axios.post('/products', formData, {
            headers: {
                "Content-Type": "multipart/form-data"
            }
        });

        await  swal({
            icon: 'success',
            title: 'Producto guardado exitosamente',
            showConfirmButton: true,
            timer: 2000
        });
        await router.push({name: 'products.index'});
    };
    const storeUserProduct = async (formData) => {
        // Verificar contenido de formData en consola (opcional, para debug)
        for (let pair of formData.entries()) {
            console.log(pair[0] + ':', pair[1]);
        }

        if (isLoading.value) return; // Prevenir envíos múltiples
        isLoading.value = true; // Activar estado de carga
        validationErrors.value = {}; // Asegurar limpieza previa de errores

        try {
            // Intentar realizar la solicitud POST
            const response = await axios.post('/api/products', formData, {
                headers: {
                    "Content-Type": "multipart/form-data"
                }
            });

            // Mostrar mensaje de éxito si la solicitud fue exitosa
            await swal({
                icon: 'success',
                title: 'Producto guardado exitosamente',
                showConfirmButton: true,
                timer: 2000
            });

            // Redirigir al índice de productos
            await router.push({ name: 'products.index' });

        } catch (error) {
            // Manejo de errores
            console.error("Error al guardar el producto:", error);

            // Verificar si el error es de validación (422)
            if (error.response && error.response.status === 422) {
                // Asignar los errores de validación al estado reactivo
                validationErrors.value = error.response.data.errors || {};
                console.error("Errores de validación:", validationErrors.value);
            } else {
                // Mostrar error inesperado
                await swal({
                    icon: 'error',
                    title: 'Error inesperado',
                    text: 'No se pudo guardar el producto. Por favor, inténtalo de nuevo.',
                    showConfirmButton: true
                });
            }
        } finally {
            // Asegurar que el estado de carga termine
            isLoading.value = false;
        }
    };

    const updateProduct = async (product) => {
        if (isLoading.value) return;

        isLoading.value = true
        validationErrors.value = {}
        console.log(product);
        axios.put('/api/products/' + product.id, product)
            .then(response => {
                router.push({name: 'Products.index'})
                swal({
                    icon: 'success',
                    title: 'Product actualizado correctamente'
                })
            })
            .catch(error => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => isLoading.value = false)
    }
    const getEstadoList = async () => {
        axios.get('/api/estado-list')
            .then(response => {
                estadoList.value = response.data.data;
            })
    }
    const deleteProduct = async (id) => {
        swal({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this action!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            confirmButtonColor: '#ef4444',
            timer: 20000,
            timerProgressBar: true,
            reverseButtons: true
        })
            .then(result => {
                if (result.isConfirmed) {
                    axios.delete('/api/products/' + id)
                        .then(response => {
                            getProducts()
                            router.push({name: 'products.index'})
                            swal({
                                icon: 'success',
                                title: 'Product deleted successfully'
                            })
                        })
                        .catch(error => {
                            swal({
                                icon: 'error',
                                title: 'Something went wrong'
                            })
                        })
                }
            })

    }

    return {
        getEstadoList,
        estadoList,
        products,
        product,
        getProducts,
        getProduct,
        storeProduct,
        storeUserProduct,
        updateProduct,
        deleteProduct,
        validationErrors,
        isLoading
    }
}
