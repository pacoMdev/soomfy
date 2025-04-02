import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'
import { useRoute } from 'vue-router'

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
        weight: '',
        width: '',
        heigth: '',
    })
    const router = useRouter()
    const route = useRoute();
    const validationErrors = ref({})
    const isLoading = ref(false)
    const swal = inject('$swal')

    const categoriaSeleccionada = ref(route.query.search_category);
    const buscarTitulo = ref('');
    const buscarEstado = ref('');
    const buscarPrecioMin = ref();
    const buscarPrecioMax = ref();
    // Ubicacion
    const buscarRadio = ref(0);
    // Ordenar
    const ordenarPrecio = ref('');
    const ordenarFecha = ref('');
    const latitude = ref(41.38740000);
    const longitude = ref(2.16860000);

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

const fetchProducts = async () => {
    try {
      console.log('Parámetros de consulta actuales:', route.query);
  
      await getProducts(
          1, // Página inicial
          route.query.search_category || '', // Categoría
          route.query.search_id || '', // ID (vacío por defecto)
          route.query.search_title || '', // Título de búsqueda
          route.query.min_price || '', // Precio mínimo
          route.query.max_price || '', // Precio máximo
          route.query.search_estado || '', // Estado (vacío por defecto)
          route.query.search_location || '', // Ubicación de búsqueda (vacío por defecto)
          route.query.search_content || '', // Contenido (vacío por defecto)
          route.query.search_global || '', // Global (vacío por defecto)
          route.query.order_column || 'created_at', // Columna de orden, por defecto "created_at"
          route.query.order_direction || 'desc', // Dirección de orden, por defecto "desc"
          route.query.order_price || '' // Precio para ordenar
      );
  
    } catch (error) {
      console.error('Error al obtener productos:', error);
    }
  };
  const aplicarFiltro = async () => {
    try {
      // Crear objeto con los filtros usando el prefijo search_
      const filtros = {
        search_category: categoriaSeleccionada.value || '',
        search_title: buscarTitulo.value || '',
        search_estado: buscarEstado.value || '',
        order_column: 'created_at',
        order_direction: ordenarFecha.value || 'desc',
        order_price: ordenarPrecio.value || '',
        min_price: buscarPrecioMin.value || '',
        max_price: buscarPrecioMax.value || '',
      };
  
      // Solo agregamos los parámetros de ubicación si hay un radio seleccionado
      if (buscarRadio.value) {
        filtros.search_latitude = latitude.value;
        filtros.search_longitude = longitude.value;
        filtros.search_radius = buscarRadio.value;
      }
  
      console.log("Filtros a enviar:", filtros);
  
      // Eliminar los filtros vacíos (solo claves con valores no vacíos)
      const filtrosLimpios = Object.fromEntries(
          Object.entries(filtros).filter(([_, value]) =>
              value !== '' && value !== null && value !== undefined
          )
      );
  
      console.log("Filtros limpios:", filtrosLimpios);
  
      // Actualizar la URL con los filtros limpios
      await router.push({
        query: filtrosLimpios,
      });
  
      // Llamar a getProducts con los parámetros ordenados manualmente
      await getProducts(
          1, // Page
          filtrosLimpios.search_category || '', // Categoría
          '', // search_id vacío (no lo estás usando actualmente, pero se requiere por posición)
          filtrosLimpios.search_title || '', // Título
          filtrosLimpios.min_price || '', // Precio mínimo
          filtrosLimpios.max_price || '', // Precio máximo
          filtrosLimpios.search_estado || '', // Estado
          filtrosLimpios.search_location || '', // Ubicación
          '', // search_content vacío (no lo estás usando actualmente)
          '', // search_global vacío (no lo estás usando actualmente)
          'created_at', // Columna para ordenar
          filtrosLimpios.order_direction || '',
          filtrosLimpios.order_price || '' ,// Precio ordenado
          filtrosLimpios.search_latitude || '', // Pasar la latitud
          filtrosLimpios.search_longitude || '', // Pasar la longitud
          filtrosLimpios.search_radius || ''
  
    );
  
    } catch (error) {
      console.error('Error al aplicar filtro:', error);
    }
  };
  const limpiarFiltros = async () => {
    categoriaSeleccionada.value = '';
    buscarTitulo.value = '';
    buscarEstado.value = '';
    buscarTitulo.value = '';
    ordenarFecha.value = '';
    ordenarPrecio.value = '';
    latitude.value = 41.38740000;
    longitude.value = 2.16860000;
    buscarPrecioMin.value = '';
    buscarPrecioMax.value = '';
    buscarRadio.value = 0;
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
        validationErrors,
        isLoading,
        fetchProducts,
        aplicarFiltro,
        limpiarFiltros,
        categoriaSeleccionada,
        buscarTitulo,
        buscarPrecioMin,
        buscarPrecioMax,
        buscarRadio,
        latitude,
        longitude,
        ordenarFecha,
    }
}
