import { ref } from 'vue';

export default function useMap(initialLatitude = 41.38740000, initialLongitude = 2.16860000) {
    const latitude = ref(initialLatitude);
    const longitude = ref(initialLongitude);
    
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
