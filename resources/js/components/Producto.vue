<template>
    <div class="row gap-5 justify-items-left justify-content-center">
      <router-link
          :to="'/products/detalle/' + producto.id"
          v-for="producto in productos"
          :key="producto.id"
          class="producto col-6 col-md-4 col-lg-3"
      >
        <div class="contenido-producto">
          <Galleria
              :value="getImages(producto.resized_image)"
              :responsiveOptions="responsiveOptions"
              :numVisible="5"
              :circular="true"
              containerStyle="height: 200px; width: 100%; border-radius: 10px;"
              :showItemNavigators="true"
              :showThumbnails="false"
          >
            <template #item="slotProps">
              <img
                  :src="slotProps.item.original_url"
                  :alt="producto.title"
                  style="width: auto; height: 200px; display: block; object-fit: cover;"
              />
            </template>
          </Galleria>
          <h3 class="">{{ producto.price }}€</h3>
          <h4 class="">{{ producto.title }}</h4>
          <p class="">{{ producto.content }}</p>
          <p class="">{{ producto.estado.name }}</p>
          <Button :label="producto.category.name" rounded />

        </div>
      </router-link>
    </div>
  </template>
  
  <script setup>
  import {defineProps, ref} from 'vue';
  
  
  const props = defineProps({
    productos: Array,
  });
  
  const responsiveOptions = ref([
    { breakpoint: '991px', numVisible: 4 },
    { breakpoint: '767px', numVisible: 3 },
    { breakpoint: '575px', numVisible: 1 }
  ]);
 
  // Función para obtener products desde la API
  function getImages(resized_image) {
      return Object.values(resized_image || {}); // retorna el objeto de la imagen sin UUID
  }
  </script>
  
  <style scoped>
  .producto {
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 350px;
    height: auto;
    border-radius: 15px;
    border: 1px solid rgba(195, 195, 195, 0.5);
    background-color: #fff;
    padding: 15px;
    transition: all 0.2s ease-in-out;
    cursor: pointer;
    overflow-wrap: break-word;
  }
  
  .producto:hover {
    transform: scale(1.02);
    box-shadow: rgba(0, 0, 0, 0.15) 0px 2px 8px;
  }
  </style>
  