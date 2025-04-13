<template>
    <div class="map-container">
        <div id="map" class="google-map"></div>
        <div class="map-controls">
            <FloatLabel>
                <InputText v-model="partialAddress" id="address-input" @keyup.enter="searchAddress"/>
                <label for="address-input">Introduce una dirección</label>
            </FloatLabel>
            <Button @click="searchAddress" label="Buscar" class="mt-2" />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import useMaps from '@/composables/Maps';
import usePerfil from "@/composables/perfil";
import { watch } from 'vue';

// Importamos los composables
const { 
    latitude, 
    longitude, 
    partialAddress,
    initMap, 
    getGeoPartialAddress,
    updateMapPosition
} = useMaps();

const {
    user,
    fullAddress,
    getDataProfile
} = usePerfil();

// Método para buscar dirección
const searchAddress = async () => {
    await getGeoPartialAddress();
};

// Inicializar el mapa con la ubicación del usuario actual
const initializeMapWithUserLocation = async () => {
    // Obtener datos del perfil si aún no están disponibles
    if (!user.value || !user.value.id) {
        await getDataProfile();
    }
    
    // Si el usuario tiene coordenadas guardadas, usarlas para el mapa
    if (user.value && user.value.latitude && user.value.longitude) {
        latitude.value = parseFloat(user.value.latitude);
        longitude.value = parseFloat(user.value.longitude);
    }
    
    // Inicializar el mapa con las coordenadas disponibles (del usuario o las predeterminadas)
    setTimeout(() => {
        initMap("map", latitude.value, longitude.value);
    }, 300);
    
    // Si el usuario tiene dirección formateada, mostrarla en el campo de búsqueda
    if (fullAddress.value?.results && fullAddress.value.results.length > 0) {
        partialAddress.value = fullAddress.value.results[0].formatted_address;
    }
};

// Al montar el componente, inicializar con la ubicación del usuario
onMounted(() => {
    initializeMapWithUserLocation();
});

// Observar cambios en el usuario y actualizar el mapa
watch(() => user.value, (newUser) => {
    if (newUser && newUser.latitude && newUser.longitude) {
        updateMapPosition(parseFloat(newUser.latitude), parseFloat(newUser.longitude));
    }
}, { deep: true });

// Exponemos las variables para que puedan ser utilizadas desde fuera del componente
defineExpose({
    latitude,
    longitude,
    partialAddress,
    searchAddress,
    updateMapPosition,
    initializeMapWithUserLocation
});
</script>

<style scoped>
.google-map {
  height: 300px;
  width: 100%;
  border: 1px solid #ccc;
  border-radius: 8px;
  min-height: 300px;
  position: relative;
  z-index: 10;
}

.map-container {
  margin-bottom: 1rem;
  display: flex;
  flex-direction: column;
  width: 100%;
}

.map-controls {
  margin-top: 1rem;
  width: 100%;
}
</style>