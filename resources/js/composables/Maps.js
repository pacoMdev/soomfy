import { ref, onUnmounted } from 'vue';
import axios from 'axios';

export default function useMaps() {
    const latitude = ref(40.4165);
    const longitude = ref(-3.70256);
    const mapInstance = ref(null);
    const marker = ref(null);
    const partialAddress = ref('');
    const error = ref(null);

    /**
     * Inicializa y muestra el mapa de Google Maps
     * @param {string} elementId - ID del elemento HTML donde se mostrará el mapa
     * @param {number} lat - Latitud inicial
     * @param {number} lng - Longitud inicial
     * @param {number} zoom - Nivel de zoom
     */
    const initMap = (elementId, lat = latitude.value, lng = longitude.value, zoom = 13) => {
        if (typeof google === 'undefined') {
            console.error('Google Maps API no está cargada');
            error.value = 'Google Maps API no está cargada';
            return;
        }

        try {
            latitude.value = parseFloat(lat);
            longitude.value = parseFloat(lng);

            const mapElement = document.getElementById(elementId);
            if (!mapElement) {
                console.error(`Elemento con ID "${elementId}" no encontrado`);
                error.value = `Elemento con ID "${elementId}" no encontrado`;
                return;
            }
            
            // Aseguramos que el elemento tenga dimensiones
            mapElement.style.height = '300px';
            mapElement.style.width = '100%';
            
            // Forzamos un reflow/repaint del DOM para asegurar que las dimensiones se apliquen
            void mapElement.offsetHeight;

            mapInstance.value = new google.maps.Map(mapElement, {
                center: { lat: parseFloat(latitude.value), lng: parseFloat(longitude.value) },
                zoom: zoom,
            });

            // Crear un marcador en el mapa
            marker.value = new google.maps.Marker({
                position: { lat: parseFloat(latitude.value), lng: parseFloat(longitude.value) },
                map: mapInstance.value,
                draggable: true, // Permitimos que se pueda arrastrar por el mapa
            });

            // Evento: al arrastrar el marcador
            marker.value.addListener("dragend", (event) => {
                const { lat, lng } = event.latLng.toJSON();
                latitude.value = lat;
                longitude.value = lng;
            });

            // Evento: clic en el mapa
            mapInstance.value.addListener("click", (event) => {
                const { lat, lng } = event.latLng.toJSON();
                latitude.value = lat;
                longitude.value = lng;
                marker.value.setPosition({ lat, lng });
            });
            
            // Forzamos una actualización del tamaño del mapa después de que se haya dibujado
            setTimeout(() => {
                google.maps.event.trigger(mapInstance.value, 'resize');
            }, 100);

            error.value = null;
            return { map: mapInstance.value, marker: marker.value };
        } catch (e) {
            console.error("Error al inicializar el mapa:", e);
            error.value = `Error al inicializar el mapa: ${e.message}`;
            return null;
        }
    };

    /**
     * Obtiene las coordenadas geográficas a partir de una dirección
     * @param {string} address - Dirección a geocodificar
     */
    const getGeoPartialAddress = async (address) => {
        try {
            error.value = null;
            // Usar el endpoint normal pero con manejo de errores mejorado
            const response = await axios.get('/api/geocode', {
                params: { address: address || partialAddress.value }
            });
            
            const components = response.data;
            
            if (components && components.results && components.results.length > 0) {
                latitude.value = components.results[0].geometry.location.lat;
                longitude.value = components.results[0].geometry.location.lng;
                
                // Si el mapa ya está inicializado, actualizamos su centro y el marcador
                if (mapInstance.value && marker.value) {
                    const newPosition = { 
                        lat: parseFloat(latitude.value), 
                        lng: parseFloat(longitude.value) 
                    };
                    mapInstance.value.setCenter(newPosition);
                    marker.value.setPosition(newPosition);
                }
                
                return components.results[0];
            }
            
            return null;
        } catch (e) {
            console.error("Error en API de geocodificación:", e);
            error.value = `Error al geocodificar dirección: ${e.message || 'Error de conexión'}`;
            return null;
        }
    };

    /**
     * Actualiza la posición del mapa y el marcador
     * @param {number} lat - Nueva latitud
     * @param {number} lng - Nueva longitud
     */
    const updateMapPosition = (lat, lng) => {
        try {
            if (!mapInstance.value || !marker.value) return false;
            
            const position = { 
                lat: parseFloat(lat), 
                lng: parseFloat(lng) 
            };
            
            mapInstance.value.setCenter(position);
            marker.value.setPosition(position);
            
            latitude.value = lat;
            longitude.value = lng;
            
            return true;
        } catch (e) {
            console.error("Error al actualizar posición:", e);
            error.value = `Error al actualizar posición: ${e.message}`;
            return false;
        }
    };

    // Limpieza de recursos cuando se desmonta el componente
    onUnmounted(() => {
        if (mapInstance.value) {
            try {
                // Limpiamos listeners y referencias
                google.maps.event.clearInstanceListeners(mapInstance.value);
                if (marker.value) {
                    google.maps.event.clearInstanceListeners(marker.value);
                    marker.value.setMap(null);
                    marker.value = null;
                }
                mapInstance.value = null;
            } catch (e) {
                console.error("Error al limpiar recursos del mapa:", e);
            }
        }
    });

    return {
        latitude,
        longitude,
        partialAddress,
        error,
        initMap,
        getGeoPartialAddress,
        updateMapPosition
    };
}