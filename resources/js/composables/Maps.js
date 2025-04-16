import { ref, onUnmounted } from 'vue';
import axios from 'axios';

/**
 * Composable para gestionar mapas de Google Maps y funcionalidades de geolocalización
 * 
 * Este composable proporciona todas las herramientas necesarias para:
 * - Inicializar un mapa de Google Maps
 * - Manejar marcadores y círculos de radio
 * - Gestionar búsquedas por ubicación
 * - Controlar el zoom según el radio seleccionado
 * 
 * @returns {Object} Conjunto de métodos y referencias para trabajar con mapas
 */
export default function useMaps() {
    // Referencias reactivas para coordenadas y elementos del mapa
    const latitude = ref(40.4165);      // Latitud inicial (Madrid)
    const longitude = ref(-3.70256);    // Longitud inicial (Madrid)
    const mapInstance = ref(null);      // Instancia del mapa de Google Maps
    const marker = ref(null);           // Marcador en el mapa
    const circles = ref([]);            // Array para gestionar círculos en el mapa
    const partialAddress = ref('');     // Dirección ingresada por el usuario
    const error = ref(null);            // Almacena errores para mostrar al usuario
    const searchRadius = ref(0);        // Radio de búsqueda en metros

    /**
     * Elimina completamente cualquier círculo existente en el mapa
     * 
     * Este método garantiza una limpieza total de todos los círculos
     * para evitar problemas visuales o superposición de círculos.
     * 
     * @returns {Promise<boolean>} True si la eliminación fue exitosa, False en caso contrario
     */
    const removeCircle = () => {
        return new Promise((resolve) => {
            try {
                console.log("⛔ Eliminando TODOS los círculos");
                
                // Si no hay círculos, resolvemos inmediatamente
                if (circles.value.length === 0) {
                    console.log("No hay círculos para eliminar");
                    resolve(true);
                    return;
                }
                
                // Aseguramos que Google Maps existe
                if (typeof google === 'undefined' || !google.maps) {
                    console.warn("API de Google Maps no disponible");
                    circles.value = [];
                    resolve(false);
                    return;
                }
                
                // Recorremos y eliminamos TODOS los círculos uno por uno
                circles.value.forEach(circle => {
                    if (circle) {
                        try {
                            circle.setMap(null);
                            google.maps.event.clearInstanceListeners(circle);
                        } catch (err) {
                            console.warn("Error al eliminar círculo:", err);
                        }
                    }
                });
                
                // Limpiamos el array de círculos
                circles.value = [];
                
                // Reseteamos el radio de búsqueda
                searchRadius.value = 0;
                
                console.log("Todos los círculos eliminados correctamente");
                
                // Forzamos un refresco del mapa
                if (mapInstance.value) {
                    const currentCenter = mapInstance.value.getCenter();
                    const currentZoom = mapInstance.value.getZoom();
                    
                    google.maps.event.trigger(mapInstance.value, 'resize');
                    mapInstance.value.setCenter(currentCenter);
                    mapInstance.value.setZoom(currentZoom);
                }
                
                resolve(true);
            } catch (e) {
                console.error("Error al eliminar círculos:", e);
                // Forzamos la limpieza incluso si hay error
                circles.value = [];
                searchRadius.value = 0;
                resolve(false);
            }
        });
    };

    /**
     * Actualiza solamente el círculo sin recrear todo el mapa
     * 
     * Método optimizado que intenta actualizar solo el círculo manteniendo
     * el resto del mapa intacto, para una mejor experiencia de usuario.
     *
     * @param {number} radius - Radio del círculo en metros (0 para ubicación exacta)
     * @returns {Promise<boolean>} True si la actualización fue exitosa, False en caso contrario
     */
    const updateCircleOnly = async (radius = 0) => {
        try {
            console.log("🔄 Actualizando solamente el círculo, radio:", radius);
            
            const validRadius = parseInt(radius) || 0;
            
            // Verificamos que tenemos un mapa válido
            if (!mapInstance.value || !marker.value) {
                console.error("Mapa o marcador no inicializado");
                return false;
            }
            
            // PRIMERA ETAPA: Eliminar TODOS los círculos existentes
            await removeCircle();
            
            // SEGUNDA ETAPA: Si se necesita tiempo adicional para la limpieza visual
            await new Promise(resolve => setTimeout(resolve, 50));
            
            // Actualizar el valor del radio
            searchRadius.value = validRadius;
            
            // Si el radio es 0, solo ajustamos el zoom y terminamos
            if (validRadius <= 0) {
                console.log("📍 Estableciendo ubicación exacta (sin círculo)");
                adjustZoom(0);
                return true;
            }
            
            // Obtenemos la posición actual
            const position = { 
                lat: parseFloat(latitude.value), 
                lng: parseFloat(longitude.value) 
            };
            
            // TERCERA ETAPA: Creamos un nuevo círculo (solo si el radio > 0)
            const newCircle = new google.maps.Circle({
                strokeColor: '#FF0000',
                strokeOpacity: 0.8, 
                strokeWeight: 2,
                fillColor: '#FF0000',
                fillOpacity: 0.15,
                map: mapInstance.value,
                center: position,
                radius: validRadius,
                clickable: false,
                zIndex: 1000 // Aseguramos que esté por encima de cualquier otra capa
            });
            
            // CUARTA ETAPA: Añadimos el círculo nuevo al array para hacer seguimiento
            circles.value.push(newCircle);
            
            // Ajustamos el zoom según el radio
            adjustZoom(validRadius);
            
            console.log("✅ Círculo actualizado exitosamente con radio:", validRadius);
            return true;
        } catch (e) {
            console.error("❌ Error al actualizar círculo:", e);
            return false;
        }
    };

    /**
     * Dibuja un círculo en el mapa con el radio especificado
     * 
     * Método estándar para dibujar un círculo nuevo en el mapa,
     * eliminando previamente cualquier círculo existente.
     *
     * @param {number} radius - Radio del círculo en metros
     * @returns {Promise<boolean|null>} True si se dibujó exitosamente, null en caso de error
     */
    const drawCircle = async (radius) => {
        try {
            console.log("Dibujando círculo con radio:", radius);
            
            // Validamos el radio
            const validRadius = parseInt(radius) || 0;
            
            // No dibujamos círculo si el radio es 0 o inválido
            if (validRadius <= 0) {
                console.log("Radio 0 o inválido - no se dibuja círculo");
                await removeCircle();
                adjustZoom(0);
                return null;
            }
            
            // Verificamos si tenemos un mapa válido
            if (!mapInstance.value || !marker.value) {
                console.error("Mapa o marcador no inicializados");
                return null;
            }
            
            // Aseguramos que no haya círculo previo
            await removeCircle();
            
            // Actualizamos el valor del radio
            searchRadius.value = validRadius;
            
            // Obtenemos la posición actual
            const position = { 
                lat: parseFloat(latitude.value), 
                lng: parseFloat(longitude.value) 
            };
            
            // Creamos un nuevo círculo con ID único
            const newCircle = new google.maps.Circle({
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#FF0000',
                fillOpacity: 0.15,
                map: mapInstance.value,
                center: position,
                radius: validRadius,
                clickable: false,
                zIndex: 1
            });
            
            // Añadimos el círculo al array
            circles.value.push(newCircle);
            
            // Ajustamos el zoom según el radio
            adjustZoom(validRadius);
            
            console.log("Círculo dibujado correctamente con radio:", validRadius);
            return true;
        } catch (e) {
            console.error("Error al dibujar círculo:", e);
            error.value = `Error al dibujar círculo: ${e.message}`;
            return null;
        }
    };

    /**
     * Recrea completamente el mapa desde cero
     * 
     * Solución nuclear que destruye y reconstruye todo el mapa.
     * Garantiza una limpieza total y evita problemas de círculos persistentes,
     * aunque es más costoso en términos de rendimiento.
     *
     * @param {string} elementId - ID del elemento HTML donde se renderiza el mapa
     * @param {number} radius - Radio del círculo en metros (0 para ubicación exacta)
     * @returns {Promise<boolean>} True si la recreación fue exitosa, False en caso contrario
     */
    const recreateMap = async (elementId, radius = 0) => {
        try {
            console.log("🔄 Recreando mapa completo");
            
            // Guardamos los datos actuales
            const currentLat = latitude.value;
            const currentLng = longitude.value;
            const validRadius = parseInt(radius) || 0;
            
            // PASOS ADICIONALES DE LIMPIEZA
            // 1. Intentar limpiar todos los círculos
            if (circles.value && circles.value.length > 0) {
                circles.value.forEach(circle => {
                    if (circle) {
                        try {
                            circle.setMap(null);
                        } catch (e) {
                            // Ignorar errores
                        }
                    }
                });
                circles.value = [];
            }
            
            // 2. Buscar círculos residuales en el DOM
            try {
                const mapElement = document.getElementById(elementId);
                if (mapElement) {
                    // Forzar reflow
                    void mapElement.offsetHeight;
                }
            } catch (e) {
                // Ignorar errores
            }
            
            // Si hay un mapa existente, limpiamos todos sus recursos
            if (mapInstance.value) {
                if (marker.value) {
                    marker.value.setMap(null);
                    google.maps.event.clearInstanceListeners(marker.value);
                    marker.value = null;
                }
                
                google.maps.event.clearInstanceListeners(mapInstance.value);
                mapInstance.value = null;
            }
            
            // Esperamos un momento para asegurar limpieza completa
            await new Promise(resolve => setTimeout(resolve, 150));
            
            // Reinicializamos el mapa desde cero
            const mapElement = document.getElementById(elementId);
            if (!mapElement) {
                console.error(`Elemento con ID "${elementId}" no encontrado`);
                return false;
            }
            
            // Recreamos el mapa con los valores actuales y zoom reducido
            mapInstance.value = new google.maps.Map(mapElement, {
                center: { lat: parseFloat(currentLat), lng: parseFloat(currentLng) },
                zoom: getZoomForRadius(validRadius),
                mapTypeControl: true,
                fullscreenControl: false,
                styles: [
                    // Añadir estilos básicos para mejor visualización
                    {
                        featureType: "poi",
                        elementType: "labels",
                        stylers: [{ visibility: "off" }]
                    }
                ]
            });
            
            // Recreamos el marcador
            marker.value = new google.maps.Marker({
                position: { lat: parseFloat(currentLat), lng: parseFloat(currentLng) },
                map: mapInstance.value,
                draggable: true
            });
            
            // Reestablecemos los eventos
            marker.value.addListener("dragend", (event) => {
                const { lat, lng } = event.latLng.toJSON();
                latitude.value = lat;
                longitude.value = lng;
                updateCircle();
            });
            
            mapInstance.value.addListener("click", (event) => {
                const { lat, lng } = event.latLng.toJSON();
                latitude.value = lat;
                longitude.value = lng;
                marker.value.setPosition({ lat, lng });
                updateCircle();
            });
            
            // Reseteamos el valor del radio
            searchRadius.value = validRadius;
            
            // Pequeña pausa para asegurar que el mapa esté listo
            await new Promise(resolve => setTimeout(resolve, 50));
            
            // Dibujamos círculo si es necesario
            if (validRadius > 0) {
                // Dibujamos un círculo nuevo con estilo mejorado
                const newCircle = new google.maps.Circle({
                    strokeColor: '#FF0000',
                    strokeOpacity: 0.9,  // Más visible
                    strokeWeight: 2,
                    fillColor: '#FF0000',
                    fillOpacity: 0.15,
                    map: mapInstance.value,
                    center: { lat: parseFloat(currentLat), lng: parseFloat(currentLng) },
                    radius: validRadius,
                    clickable: false,
                    zIndex: 9999  // Muy alto para asegurar visibilidad
                });
                
                // Añadimos al array para seguimiento
                circles.value = [newCircle]; // Solo un círculo en el array
            }
            
            console.log("✅ Mapa recreado exitosamente" + (validRadius > 0 ? " con círculo de radio " + validRadius : ""));
            return true;
        } catch (e) {
            console.error("❌ Error al recrear mapa:", e);
            return false;
        }
    };

    /**
     * Ajusta el nivel de zoom del mapa según el radio seleccionado
     * 
     * Establece automáticamente un zoom apropiado para visualizar correctamente
     * el área cubierta por el radio especificado.
     *
     * @param {number} radius - Radio en metros
     */
    const adjustZoom = (radius) => {
        if (!mapInstance.value) return;
        
        // Usando la función con zoom reducido
        mapInstance.value.setZoom(getZoomForRadius(parseInt(radius) || 0));
    };

    /**
     * Determina el nivel de zoom apropiado para un radio dado
     * 
     * Calcula el zoom óptimo para mostrar adecuadamente
     * un área con el radio especificado.
     *
     * @param {number} radius - Radio en metros
     * @returns {number} Nivel de zoom (valores más pequeños = más alejado)
     */
    const getZoomForRadius = (radius) => {
        const radiusValue = parseInt(radius) || 0;
        
        if (radiusValue === 0) {
            return 12; // Reducido considerablemente para ubicación exacta
        } else if (radiusValue <= 1000) {
            return 12; // Reducido para 1km
        } else if (radiusValue <= 5000) {
            return 10; // Reducido para 5km
        } else if (radiusValue <= 10000) {
            return 9;  // Reducido para 10km
        } else {
            return 8;  // Reducido para 20km o más
        }
    };

    /**
     * Actualiza el círculo cuando cambian las coordenadas
     * 
     * Se utiliza cuando el usuario mueve el marcador o hace clic en otra ubicación,
     * manteniendo el mismo radio pero redibujando en la nueva posición.
     */
    const updateCircle = async () => {
        // Si hay un radio establecido, eliminamos primero y luego redibujamos
        await removeCircle();
        
        if (searchRadius.value > 0) {
            await drawCircle(searchRadius.value);
        }
    };

    /**
     * Inicializa y muestra un mapa de Google Maps en el elemento especificado
     * 
     * Configura el mapa inicial con un marcador arrastrable y los eventos necesarios
     * para interactuar con él.
     *
     * @param {string} elementId - ID del elemento HTML donde se mostrará el mapa
     * @param {number} lat - Latitud inicial
     * @param {number} lng - Longitud inicial
     * @param {number} zoom - Nivel de zoom inicial
     * @returns {Object|null} Objeto con referencias al mapa y marcador, o null si hay error
     */
    const initMap = (elementId, lat = latitude.value, lng = longitude.value, zoom = 11) => {
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
                zoom: zoom, // Valor por defecto ahora es 11 en lugar de 13
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
     * Obtiene coordenadas geográficas a partir de una dirección
     * 
     * Utiliza la API de geocodificación para convertir una dirección en texto
     * a coordenadas de latitud y longitud.
     *
     * @param {string} address - Dirección a geocodificar (opcional si existe partialAddress)
     * @returns {Promise<Object|null>} Resultado de geocodificación o null si hay error
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
     * 
     * Centra el mapa en nuevas coordenadas y actualiza la posición del marcador.
     * Si hay un radio activo, redibuja el círculo en la nueva posición.
     *
     * @param {number} lat - Nueva latitud
     * @param {number} lng - Nueva longitud
     * @returns {Promise<boolean>} True si la actualización fue exitosa, False en caso contrario
     */
    const updateMapPosition = async (lat, lng) => {
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
            
            // Si hay un radio establecido, primero eliminamos y luego redibujamos
            if (searchRadius.value > 0) {
                await removeCircle();
                await drawCircle(searchRadius.value);
            }
            
            return true;
        } catch (e) {
            console.error("Error al actualizar posición:", e);
            error.value = `Error al actualizar posición: ${e.message}`;
            return false;
        }
    };

    /**
     * Limpieza de recursos cuando se desmonta el componente
     * 
     * Garantiza que todos los recursos del mapa se liberen correctamente
     * para evitar fugas de memoria cuando se abandona la página.
     */
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
                
                // Limpiamos todos los círculos
                if (circles.value.length > 0) {
                    circles.value.forEach(circle => {
                        if (circle) {
                            try {
                                circle.setMap(null);
                                google.maps.event.clearInstanceListeners(circle);
                            } catch (e) {
                                // Ignorar errores
                            }
                        }
                    });
                    circles.value = [];
                }
                
                mapInstance.value = null;
            } catch (e) {
                console.error("Error al limpiar recursos del mapa:", e);
            }
        }
    });

    // Retorna las variables y métodos que serán accesibles desde fuera
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
        removeCircle,
        recreateMap,
        updateCircleOnly
    };
}