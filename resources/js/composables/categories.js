import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'

export default function useCategories() {
    const categories = ref([])
    const categoryList = ref([])
    const category = ref({
        name: ''
    })

    const router = useRouter()
    const validationErrors = ref({})
    const isLoading = ref(false)
    const swal = inject('$swal')

    const getCategories = async (
        page = 1,
        search_id = '',
        search_title = '',
        search_global = '',
        order_column = 'created_at',
        order_direction = 'desc'
    ) => {
        axios.get('/api/categories?page=' + page +
            '&search_id=' + search_id +
            '&search_title=' + search_title +
            '&search_global=' + search_global +
            '&order_column=' + order_column +
            '&order_direction=' + order_direction)
            .then(response => {
                categories.value = response.data;
            })
    }

    const getCategory = async (id) => {
        axios.get('/api/categories/' + id)
            .then(response => {
                category.value = response.data.data;
            })
    }

    const storeCategory = async (category) => {
        if (isLoading.value) return;

        isLoading.value = true;
        validationErrors.value = {};

        // Creo una funcion de formData para almacenar los datos del formulario
        const formData = new FormData();

        formData.append('name', category.name);
        if (category.thumbnail) {
            formData.append('image', category.thumbnail);
        }
        console.log('Archivo categories.js ' +
            'storeCategory ');
        console.log('Contenido completo del FormData:');
        for (let pair of formData.entries()) {
            console.log(pair[0], ':', pair[1]);
        }


        // Se envia el formData
        try {
            console.log('Iniciando envío de datos...');
            console.log('FormData a enviar:', formData);
            const response = await axios.post('/api/categories', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });
            console.log('Respuesta exitosa:', response.data);


            await router.push({name: 'categories.index'});
            swal({
                icon: 'success',
                title: 'Categoría creada exitosamente'
            });
        } catch (error) {
            if (error.response?.data) {
                validationErrors.value = error.response.data.errors;
            }
        } finally {
            isLoading.value = false;
        }
    };

    const updateCategory = async (category) => {
        if (isLoading.value) return;

        isLoading.value = true;
        validationErrors.value = {};

        try {
            const nombreCategoria = category.name.value || category.name;
            console.log('Valor real de name:', nombreCategoria);

            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('name', nombreCategoria);
            if (category.thumbnail) {
                formData.append('image', category.thumbnail);
            }

            const response = await axios.post(`/api/categories/${category.id}`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });

            router.push({name: 'categories.index'});
            swal({
                icon: 'success',
                title: 'Categoría actualizada exitosamente'
            });
        } catch (error) {
            // Mostrar más detalles del error
            console.error('Error completo:', error);
            console.error('Estado de la respuesta:', error.response?.status);
            console.error('Datos del error:', error.response?.data);

            if (error.response?.data) {
                validationErrors.value = error.response.data.errors;
                // Mostrar el error en un alert para debug
                swal({
                    icon: 'error',
                    title: 'Error de validación',
                    text: JSON.stringify(error.response.data, null, 2)
                });
            }
        } finally {
            isLoading.value = false;
        }
    };

    const deleteCategory = async (id) => {
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
                    axios.delete('/api/categories/' + id)
                        .then(response => {
                            getCategories()
                            router.push({name: 'categories.index'})
                            swal({
                                icon: 'success',
                                title: 'Category deleted successfully'
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

    const getCategoryList = async () => {
        axios.get('/api/category-list')
            .then(response => {
                categoryList.value = response.data.data;
            })
    }



    return {
        categoryList,
        categories,
        category,
        getCategories,
        getCategoryList,
        getCategory,
        storeCategory,
        updateCategory,
        deleteCategory,
        validationErrors,
        isLoading
    }
}
