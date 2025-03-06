import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'

export default function useProducts() {
    const products = ref({data:[]})
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
        search_content = '',
        search_global = '',
        order_column = 'created_at',
        order_direction = 'desc'
    ) => {
        axios.get('/api/products?page=' + page +
            '&search_category=' + search_category +
            '&search_id=' + search_id +
            '&search_title=' + search_title +
            '&search_content=' + search_content +
            '&search_global=' + search_global +
            '&order_column=' + order_column +
            '&order_direction=' + order_direction)
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
        // Verificar contenido
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }

        if (isLoading.value) return;
        isLoading.value = true;
        validationErrors.value = {};

        const response = await axios.post('/api/products', formData, {
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
                    title: 'Product updated successfully'
                })
            })
            .catch(error => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => isLoading.value = false)
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
