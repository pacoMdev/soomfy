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
        estado_id: '',
        thumbnails: '',
        toSend: '',
        weight: '',
        width: '',
        heigth: '',
    })

    
    const router = useRouter()
    const validationErrors = ref({})
    const isLoading = ref(false)
    const swal = inject('$swal')
    const selectedProduct = ref(null);
    const usersInterested = ref([]);
    const selectedUserId = ref(null); // Guardará el ID del usuario seleccionado
    const selectedUser = ref(null);
    const isProcessedProduct = ref(false);
    const isSelledProduct = ref(false);
    const finalPrice = ref(0);




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
        order_price = '',
        search_latitude = '',
        search_longitude = '',
        search_radius = '',
        paginate = ''
    ) => {
        try {
            const response = await axios.get('/api/products?page=' + page +
                '&search_category=' + search_category +
                '&search_id=' + search_id +
                '&search_title=' + search_title +
                '&min_price=' + min_price +
                '&max_price=' + max_price +
                '&search_estado=' + search_estado +
                '&search_location=' + search_location +
                '&search_content=' + search_content +
                '&search_global=' + search_global +
                '&order_column=' + order_column +
                '&order_direction=' + order_direction +
                '&order_price=' + order_price +
                '&search_latitude=' + search_latitude +
                '&search_longitude=' + search_longitude +
                '&search_radius=' + search_radius +
                '&paginate=' + paginate
            );
    
            console.log("Respuesta completa:", response.data);
            products.value = response.data;
            return response.data; // Devuelve los datos
        } catch (error) {
            console.error("Error en getProducts:", error);
            throw error; // Propaga el error
        }
    };

    const getProduct = async (id) => {
        console.log("ID DESDE GET PRODUCT:", id);
        if(id){
            try {
                const response = await axios.get('/api/products/' + id);  // Usamos `await` para esperar la respuesta
                product.value = response.data.data;  // Asignamos los datos al `product.value`
                return product.value;  // Retornamos el producto obtenido
            } catch (error) {
                console.error("Error al obtener el producto", error);
                return null;  // En caso de error, retornamos `null` o un valor predeterminado
            }
        }

    };


    const storeProduct = async (formData) => {
        console.log('Archivo products.js ')
        for (let pair of formData.entries()) {
          console.log(pair[0] + ': ' + pair[1]);
        }
      
        if (isLoading.value) return;
        isLoading.value = true;
        validationErrors.value = {};
      
        try {
          const response = await axios.post('/api/products', formData, {
            headers: {
              "Content-Type": "multipart/form-data"
            }
          });
      
          console.log('Respuesta del servidor:', response.data);
      
          // Mostrar mensaje de éxito
          await swal({
            icon: 'success',
            title: 'Producto guardado exitosamente',
            showConfirmButton: true,
            timer: 2000
          });
      
          // Redirigir al índice de productos
          await router.push({ name: 'products.index' });
      
          return response.data; // Retorna la respuesta para usarla en otro lugar
        } catch (error) {
            console.error('Error al guardar el producto:', error);
            if (error.response && error.response.status === 422) {
                validationErrors.value = error.response.data.errors || {};
            }
            throw error; // Propaga el error para manejarlo en otro lugar
        } finally {
            isLoading.value = false;
        }
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
            await router.push({ name: 'logged.cuenta' });

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

    const updateProduct = async (formData, productId) => {
        if (isLoading.value) return;

        isLoading.value = true
        validationErrors.value = {}

        formData.append('_method', 'PUT');

        // Canviar aquest mètode de put a post
        axios.post('/api/products/' + productId, formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            }
        })
            .then(response => {
                console.log("Resposta del servidor:", response.data);
                // router.push({name: 'products.index'})
                swal({
                    icon: 'success',
                    title: 'Product actualizado correctamente',
                    showConfirmButton: true,
                    timer: 2000
                })
            })
            .catch(error => {
                console.error("Error en actualitzar:", error.response?.data);
                swal({
                    icon: 'error',
                    title: 'Producto no actualizado correctamente',
                    showConfirmButton: true,
                    timer: 2000
                })
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
    const delProduct = async (id) => {
        axios.delete('/api/products/' + id)
        .then(response => {
            getProducts()
            router.push({ name: 'home' });
            swal({
                icon: 'success',
                title: 'Product deleted successfully',
                showConfirmButton: false,
                timer: 1500,
            })
        })
        .catch(error => {
            swal({
                icon: 'error',
                title: 'Something went wrong'
            })
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

    const getInterested = async (usersInterestedIds) => {
        try {
            await axios.post('/api/getUsersConversations', {
                usersInterested: usersInterestedIds
            })
            .then (response =>{
                usersInterested.value = response.data || [];
                console.log('🔎 USERS INTERESTED  --->', usersInterested.value);

            });
        } catch (err) {
          console.log("Error al obtener los datos.");
        }
      };
      
      const sellProduct = async () => {
        try{
          const response = await axios.post('/api/sellProduct', {
            userBuyer_id: selectedUserId.value,
            product_id: selectedProduct.value.id,
            finalPrice: finalPrice.value,
            isToSend: false,
          });
          console.log("Producto vendido -->", response.data);
    
        }catch(error){
          console.error('Error al vender el producto:', error);
        }
      }
      const checkSelledProduct = async (productId) => {
        try {
          const response = await axios.post('/api/checkSelledProduct', {
            product_id: productId,
          });
          isProcessedProduct.value = response.data.isProcessed;
          isSelledProduct.value = response.data.isSelled;
          console.log("Producto procesado -->", isProcessedProduct.value);
          console.log("Producto vendido -->", isSelledProduct.value);
        } catch (error) {
          console.error("Error al verificar el producto vendido:", error);
          return false;
        }
      };



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
        delProduct,
        validationErrors,
        isLoading,
        getInterested,
        sellProduct,
        selectedProduct,
        usersInterested,
        selectedUserId,
        selectedUser,
        checkSelledProduct,
        isProcessedProduct,
        isSelledProduct,
        finalPrice,
    }
}
