import axios from 'axios';
import { ref } from 'vue';

export default function usePerfil() {
    const user = ref({});
    const activeProducts = ref([]);
    const purchases = ref([]);
    const sales = ref([]);
    const reviews = ref([]);
    const loading = ref(false);
    const error = ref(null);
    const mediaRating = ref(0);
    const fullAddress = ref(null);

    /**
     * Obtiene los datos del perfil del usuario autenticado
     * @returns {Object} Datos del usuario
     */
    const getDataProfile = async () => {
        try {
            loading.value = true;
            error.value = null;
            const respuesta = await axios.get('/api/user');
            user.value = respuesta.data.data || {};
            
            // Si el usuario tiene coordenadas, intentamos obtener su dirección
            if (user.value.latitude && user.value.longitude) {
                await getGeoLocation(user.value.latitude, user.value.longitude);
            }
            
            return user.value;
        } catch (error) {
            console.error("Error al obtener usuario:", error);
            error.value = "Error al obtener datos del usuario";
            return null;
        } finally {
            loading.value = false;
        }
    };

    /**
     * Obtiene los productos según el endpoint y tipo especificado
     */
    const fetchProducts = async (endpoint, id, type) => {
        if (!id) return [];
        
        loading.value = true;
        error.value = null;

        try {
            const response = await axios.post(`/api/${endpoint}`, {
                userId: id,
            });

            // Guarda info según sección
            if (type === 'purchases') purchases.value = response.data.data || [];
            else if (type === 'sales') sales.value = response.data.data || [];
            else if (type === 'activeProducts') activeProducts.value = response.data.data || [];
            else if (type === 'reviews') {
                reviews.value = response.data.data || []; 
                calcularMediaRating();
            }
            
            return response.data.data || [];
        } catch (err) {
            console.error(`Error al obtener ${type}:`, err);
            error.value = `Error al obtener los datos de ${type}.`;
            return [];
        } finally {
            loading.value = false;
        }
    };

    /**
     * Calcula la valoración media de las reseñas
     */
    const calcularMediaRating = () => {
        if (reviews.value.length === 0) {
            mediaRating.value = 0;
            return;
        }
        const total = reviews.value.reduce((sum, review) => sum + review.calification, 0);
        mediaRating.value = total / reviews.value.length;
    };

    /**
     * Obtiene la dirección a partir de las coordenadas
     * @param {number} latitude - Latitud
     * @param {number} longitude - Longitud
     * @returns {Object} Información de la dirección
     */
    const getGeoLocation = async (latitude, longitude) => {
        if (!latitude || !longitude) {
            console.log('Coordenadas no válidas');
            return null;
        }

        try {
            loading.value = true;
            error.value = null;
            
            const respuesta = await axios.get('/api/geoLocation', {
                params: { latitude, longitude },
                timeout: 10000
            });
            
            fullAddress.value = respuesta.data || null;
            return fullAddress.value;
        } catch (err) {
            console.log('Falla en API de geolocalización: ', err);
            return null;
        } finally {
            loading.value = false;
        }
    };

    /**
     * Obtiene las coordenadas geocodificadas a partir de una dirección
     * Manejo mejorado de errores
     */
    const getGeocodeData = async (address = 'Barcelona') => {
        try {
            loading.value = true;
            error.value = null;
            
            const response = await axios.get('/api/geocode', {
                params: { address },
                timeout: 10000
            });
            
            return response.data;
        } catch (err) {
            console.error('Error durante la llamada a geocodificación:', err);
            // No establecemos error.value para evitar mostrar mensajes de error al usuario
            return null;
        } finally {
            loading.value = false;
        }
    };

    return {
        user,
        activeProducts,
        purchases,
        sales,
        reviews,
        loading,
        error,
        mediaRating,
        fullAddress,
        getDataProfile,
        fetchProducts,
        calcularMediaRating,
        getGeoLocation,
        getGeocodeData
    };
}