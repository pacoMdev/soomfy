<template>
    <FloatLabel>
        <Button placeholder="Buscar" label="iniciar mapa" class="btn btn-primary" @click="startMap"/>
        <div class="my-3">
            <FloatLabel>
            <InputText v-model="partialAddress" id="address-input" @keyup.enter="buscarUbicacio"/>
            <label for="address-input">Intoduce una direccion</label>
            </FloatLabel>
            <Button @click="getGeoPartialAddress" label="Buscar" class="mt-2" />
        </div>
        <div id="map" class="google-map"></div>
    </FloatLabel>
</template>

<script setup>
    import {map} from "lodash";

    
    const startMap = async () => {
  const map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: latitude.value, lng: longitude.value },
    zoom: 13,
  });

  // Crear un marcador en el mapa
  const marker = new google.maps.Marker({
    // Coordenadas iniciales donde se colocara el marcador
    position: { lat: latitude.value, lng: longitude.value },
    map, // Indica que mapa
    draggable: true, // Permitimos que se pueda arrastrar por e l mapa
  });

  // Evento: al arrastrar el marcador
  marker.addListener("dragend", (event) => {
    const { lat, lng } = event.latLng.toJSON();
    latitude.value = lat;
    longitude.value = lng;
    console.log('🆕 POSITION');
    console.log('📟 latitude -->', latitude.value);
    console.log('📟 longitude -->', longitude.value);
  });

  // Evento: clic en el mapa
  map.addListener("click", (event) => {
    const { lat, lng } = event.latLng.toJSON();
    latitude.value = lat;
    longitude.value = lng;
    marker.setPosition({ lat, lng });
    console.log("Mapa clickeado en:", latitude.value, longitude.value);
  });
}


</script>
<style scoped>
.google-map {
  height: 300px; /* Define la altura del mapa */
  width: 100%; /* Define el ancho del mapa */
  border: 1px solid #ccc; /* Opcional: bordes del mapa */
  border-radius: 8px; /* Opcional: bordes redondeados */
}

</style>