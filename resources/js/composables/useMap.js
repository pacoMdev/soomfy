import { ref } from 'vue';

/**
 * Composable para manejar la funcionalidad del mapa de Google Maps
 * @param {number} initialLatitude - Latitud inicial del mapa
 * @param {number} initialLongitude - Longitud inicial del mapa
 * @returns {Object} Retorna las coordenadas y función de inicialización del mapa
 */
export default function useMap(initialLatitude = 41.38740000, initialLongitude = 2.16860000) {
    /**
     * Referencias reactivas para las coordenadas
     */
    const latitude = ref(initialLatitude);
    const longitude = ref(initialLongitude);
    
    /**
     * Inicializa el mapa de Google Maps en el elemento especificado
     * @param {string} elementId - ID del elemento DOM donde se mostrará el mapa
     */
    const initMap = (elementId) => {
        const map = new google.maps.Map(document.getElementById(elementId), {
            center: { lat: latitude.value, lng: longitude.value },
            zoom: 13,
        });

        const marker = new google.maps.Marker({
            position: { lat: latitude.value, lng: longitude.value },
            map,
            draggable: true,
        });

        // Evento: al arrastrar el marcador
        marker.addListener("dragend", (event) => {
            const { lat, lng } = event.latLng.toJSON();
            latitude.value = lat;
            longitude.value = lng;
        });

        // Evento: clic en el mapa
        map.addListener("click", (event) => {
            const { lat, lng } = event.latLng.toJSON();
            latitude.value = lat;
            longitude.value = lng;
            marker.setPosition({ lat, lng });
        });

        return { map, marker };
    };

    return {
        latitude,
        longitude,
        initMap
    };
}
