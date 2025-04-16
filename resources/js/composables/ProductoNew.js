import axios from 'axios';
import { ref } from 'vue';

export default function useProductoNew() {
    // Inicializar favoritos como un array vacío
    const favoritos = ref([]);
    const loading = ref(false);
    const error = ref(null);

    /**
     * Obtiene la lista de favoritos del usuario actual
     * @returns {Promise<Array>} Lista de productos favoritos
     */
    const getFavoritos = async () => {
        loading.value = true;
        error.value = null;
        
        try {
            const response = await axios.get('/api/favoritos');
            // Asegurarse de que favoritos.value sea siempre un array
            favoritos.value = Array.isArray(response.data) ? response.data : [];
            return favoritos.value;
        } catch (err) {
            console.error('Error al cargar favoritos:', err);
            error.value = err;
            favoritos.value = []; // Resetear a un array vacío en caso de error
            return [];
        } finally {
            loading.value = false;
        }
    };

    /**
     * Gestiona la adición o eliminación de un producto de favoritos (funciona como toggle)
     * @param {number} productId - ID del producto a gestionar
     * @returns {Promise<Object>} Respuesta de la API
     */
    const gestorFavoritos = async (productId) => {
        loading.value = true;
        error.value = null;
        
        try {
            const response = await axios.post(`/api/gestor-favoritos/${productId}`);
            console.log("Acción de favorito realizada:", productId);
            
            // Si tenemos acceso a la respuesta, podemos obtener el nuevo estado
            // pero el componente ya lo manejará directamente
            return response.data;
        } catch (err) {
            console.error('Error al gestionar favoritos:', err);
            error.value = err;
            throw err;
        } finally {
            loading.value = false;
        }
    };

    /**
     * Procesa las imágenes del producto para ser mostradas en la galería
     * @param {Object} resized_image - Objeto de imágenes redimensionadas
     * @returns {Array} Array de objetos de imagen
     */
    const getImages = (resized_image) => {
        return Object.values(resized_image || {});
    };

    /**
     * Verifica si un producto está en la lista de favoritos
     * @param {number} productId - ID del producto a verificar
     * @returns {boolean} true si es favorito, false si no
     */
    const isFavorite = (productId) => {
        // Comprobamos si el ID del producto existe en el array de favoritos
        return favoritos.value.some(favorito => favorito.id === productId);
    };

    /**
     * Formatea el precio para mostrar con separador de miles
     * @param {number|string} price - Precio a formatear
     * @returns {string} Precio formateado
     */
    const formatPrice = (price) => {
        return Number(price).toLocaleString('es-ES');
    };

    /**
     * Formatea la fecha a formato local español
     * @param {string} dateString - Fecha en formato ISO
     * @returns {string} Fecha formateada
     */
    const formatDate = (dateString) => {
        const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
        return new Date(dateString).toLocaleDateString('es-ES', options);
    };

    return {
        favoritos,
        loading,
        error,
        getFavoritos,
        gestorFavoritos,
        getImages,
        isFavorite,
        formatPrice,
        formatDate
    };
}
