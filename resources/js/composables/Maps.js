import { ref, onUnmounted } from 'vue';
import axios from 'axios';

export default function useMaps() {
    const latitude = ref(40.4165);
    const longitude = ref(-3.70256);
    const mapInstance = ref(null);
    const marker = ref(null);
    const circle = ref(null);
    const partialAddress = ref('');
    const error = ref(null);
    const searchRadius = ref(0);

    /**
     * Dibuja un círculo en el mapa con el radio especificado
     * @param {number} radius - Radio del círculo en metros
     */
    const drawCircle = (radius) => {
        // Este método ahora recreará el círculo desde cero cada vez
        
        // 1. Asegurarnos de tener instancias válidas
        if (!mapInstance.value || !marker.value) return null;
        
        try {
            // 2. Eliminar completamente cualquier círculo existente
            removeCircle();
            
            // 3. Actualizar el valor del radio de búsqueda
            searchRadius.value = parseInt(radius) || 0;
            
            // 4. Si el radio es 0 o inválido, no dibujamos ningún círculo
            if (searchRadius.value <= 0) {
                mapInstance.value.setZoom(15);
                return null;
            }
            
            // 5. Asegurarnos de que las coordenadas sean números válidos
            const position = { 
                lat: parseFloat(latitude.value), 
                lng: parseFloat(longitude.value) 
            };
            
            // 6. Crear un nuevo círculo
            circle.value = new google.maps.Circle({
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#FF0000',
                fillOpacity: 0.15,
                map: mapInstance.value,
                center: position,
                radius: searchRadius.value
            });
            
            // 7. Ajustar el zoom basado en el radio
            adjustZoom(searchRadius.value);
            
            return true;
        } catch (e) {
            console.error("Error al dibujar círculo:", e);
            error.value = `Error al dibujar círculo: ${e.message}`;
            return null;
        }
    };
    
    /**
     * Ajusta el zoom del mapa basado en el radio
     * @param {number} radius - Radio en metros
     */
    const adjustZoom = (radius) => {
        if (!mapInstance.value) return;
        
        // Establecer zoom basado en el tamaño del círculo
        const radiusValue = parseInt(radius) || 0;
        
        if (radiusValue <= 100) {
            mapInstance.value.setZoom(18);
        } else if (radiusValue <= 500) {
            mapInstance.value.setZoom(16);
        } else if (radiusValue <= 2000) {
            mapInstance.value.setZoom(14);
        } else if (radiusValue <= 10000) {
            mapInstance.value.setZoom(12);
        } else {
            mapInstance.value.setZoom(10);
        }
    };

    /**
     * Elimina completamente cualquier círculo existente
     */
    const removeCircle = () => {
        if (circle.value) {
            // 1. Desvincularlo del mapa
            circle.value.setMap(null);
            
            // 2. Remover event listeners si existen
            if (typeof google !== 'undefined') {
                google.maps.event.clearInstanceListeners(circle.value);
            }
            
            // 3. Eliminar la referencia
            circle.value = null;
        }
    };

    /**
     * Actualiza el círculo cuando cambian las coordenadas
     * En lugar de actualizar, redibuja completamente
     */
    const updateCircle = () => {
        // Si hay un radio establecido, redibujamos el círculo completo
        if (searchRadius.value > 0) {
            drawCircle(searchRadius.value);
        }
    };

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
                updateCircle();
            });

            // Evento: clic en el mapa
            mapInstance.value.addListener("click", (event) => {
                const { lat, lng } = event.latLng.toJSON();
                latitude.value = lat;
                longitude.value = lng;
                marker.value.setPosition({ lat, lng });
                updateCircle();
            });
            
            // Forzamos una actualización del tamaño del mapa después de que se haya dibujado
            setTimeout(() => {
                google.maps.event.trigger(mapInstance.value, 'resize');
            }, 100);

            // Dibujar círculo si hay un radio establecido
            if (searchRadius.value > 0) {
                drawCircle(searchRadius.value);
            }

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
                    updateCircle();
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
            
            // Asegurarnos de que los valores sean números
            latitude.value = parseFloat(lat);
            longitude.value = parseFloat(lng);
            
            const position = { 
                lat: latitude.value, 
                lng: longitude.value 
            };
            
            // Actualizar el mapa y el marcador
            mapInstance.value.setCenter(position);
            marker.value.setPosition(position);
            
            // Si hay un radio establecido, redibujar el círculo
            if (searchRadius.value > 0) {
                // Eliminar cualquier círculo existente antes de dibujar uno nuevo
                removeCircle();
                // Esperar un momento para asegurar que la eliminación se ha completado
                setTimeout(() => {
                    drawCircle(searchRadius.value);
                }, 50);
            }
            
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
                removeCircle();
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
        searchRadius,
        error,
        initMap,
        getGeoPartialAddress,
        updateMapPosition,
        drawCircle,
        removeCircle
    };
}