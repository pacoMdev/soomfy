<template>
    <div class="carousel-container">
      <Carousel
        :value="productos"
        :numVisible="1"
        :numScroll="1"
        :circular="true"
        :autoplayInterval="3000"
        class="custom-carousel"
      >
        <template #item="slotProps">
          <div class="producto-carousel">
            <div class="contenido-producto">
              <div class="d-flex justify-content-end w-100">
                <i
                  v-if="!slotProps.data.esFavorito"
                  @click.stop.prevent="gestorFavoritos(slotProps.data.id)"
                  class="fa-regular fa-heart pb-1"
                  style="color: #e66565;"
                ></i>
                <i
                  v-else
                  @click.stop.prevent="gestorFavoritos(slotProps.data.id)"
                  class="fa-solid fa-heart pb-1"
                  style="color: #e66565;"
                ></i>
              </div>
              <Galleria
                :value="getImages(slotProps.data.resized_image)"
                :responsiveOptions="responsiveOptions"
                :numVisible="1"
                :circular="true"
                containerStyle="height: 150px; width: 100%; border-radius: 10px;"
                :showItemNavigators="true"
                :showThumbnails="false"
              >
                <template #item="slotProps">
                  <img
                    :src="slotProps.item.original_url"
                    :alt="slotProps.data.title"
                    style="width: auto; height: 150px; display: block; object-fit: cover;"
                  />
                </template>
              </Galleria>
              <p class="h1-p">{{ slotProps.data.price }}€</p>
              <p class="h4-p">{{ slotProps.data.title }}</p>
              <p class="">{{ slotProps.data.content }}</p>
              <p class="tamaño-estadoProducto">{{ slotProps.data.estado.name }}</p>
            </div>
          </div>
        </template>
      </Carousel>
    </div>
  </template>
  
  <script setup>
  import { defineProps, inject, ref } from "vue";
  import { Carousel } from "primevue/carousel";
  import Galleria from "primevue/galleria";
  import axios from "axios";
  
  const props = defineProps({
    productos: Array, // Recibe la lista de productos
    actualizarFavoritos: Function, // Recibe función para actualizar favoritos en la vista padre
    esVistaFavoritos: Boolean,
    actualizarProductos: Function,
  });
  
  const responsiveOptions = ref([
    { breakpoint: "991px", numVisible: 1 },
    { breakpoint: "767px", numVisible: 1 },
    { breakpoint: "575px", numVisible: 1 },
  ]);
  
  const gestorFavoritos = async (productId) => {
    console.log("Este es el id del producto que has clicado: " + productId);
    const respuesta = await axios.post(`/api/gestor-favoritos/${productId}`);
    if (props.esVistaFavoritos) {
      await props.actualizarFavoritos();
    } else {
      await props.actualizarProductos();
    }
  };
  
  function getImages(resized_image) {
    return Object.values(resized_image || {}); // retorna el objeto de la imagen sin UUID
  }
  </script>
  
  <style scoped>
  .carousel-container {
    width: 100%;
    max-width: 600px;
    margin: 0 auto;
  }
  
  .producto-carousel {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: auto;
    border-radius: 15px;
    border: 1px solid rgba(195, 195, 195, 0.5);
    background-color: #fff;
    padding: 15px;
    transition: all 0.2s ease-in-out;
    cursor: pointer;
    overflow-wrap: break-word;
  }
  
  .producto-carousel:hover {
    transform: scale(1.02);
    box-shadow: rgba(0, 0, 0, 0.15) 0px 2px 8px;
  }
  </style>