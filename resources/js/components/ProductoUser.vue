<template>
  <div class="row gap-5 justify-items-left justify-content-center">
    <div v-for="producto in productos" class="producto col-6 col-md-4 col-lg-3">
      <router-link :to="'/products/detalle/' + producto.id" :key="producto.id" class="contenido-producto">
        <div class="d-flex justify-content-end w-100">
          <i
              v-if="!producto.esFavorito"
              @click.stop.prevent="gestorFavoritos(producto.id)"
              class="fa-regular fa-heart pb-1"
              style="color: #e66565;"
          ></i>
          <i
              v-else
              @click.stop.prevent="gestorFavoritos(producto.id)"
              class="fa-solid fa-heart pb-1"
              style="color: #e66565;"
          ></i>
        </div>
        <Galleria
            :value="getImages(producto.resized_image)"
            :responsiveOptions="responsiveOptions"
            :numVisible="5"
            :circular="true"
            containerStyle="height: 150px; width: 100%; border-radius: 10px;"
            :showItemNavigators="true"
            :showThumbnails="false"
        >
          <template #item="slotProps">
            <img
                :src="slotProps.item.original_url"
                :alt="producto.title"
                style="width: auto; height: 150px; display: block; object-fit: cover;"
            />
          </template>
        </Galleria>
        <p class="h1-p">{{ producto.price }}€</p>
        <p class="h4-p">{{ producto.title }}</p>
        <p class="">{{ producto.content }}</p>
        <p class="tamaño-estadoProducto">{{ producto.estado.name }}</p>
        
      </router-link>
      <Button label="Vender" @click.stop="openDialog(producto)" />
        <Dialog v-model:visible="visible" modal :header="'Vendiendo '+selectedProduct?.title" style=" width: 450px; height: 400px; ">
          <form @submit.prevent="submitForm">
            <Stepper value="1">
              <StepList>
                  <Step value="1">Usuario al que vender</Step>
                  <Step value="2">Precio</Step>
              </StepList>
              <StepPanels>
                  <StepPanel v-slot="{ activateCallback }" value="1">
                      <div class="flex flex-col h-48">
                        <!-- Usuario al que se vende ------------------------------------------------ -->
                          <div class="w-100">
                            <FloatLabel>
                              <Select name="city" :options="usersInterested" optionLabel="name" fluid class="w-100">
                                <template #option="slotProps">
                                  <div class="flex items-center">
                                      <img :alt="slotProps.option.label" src="http://127.0.0.1:8000/storage/17/Imagen.PNG" :class="`mr-2 flag flag-${slotProps.option.code.toLowerCase()}`" style="width: 18px" />
                                      <div>{{ slotProps.option.name }}</div>
                                  </div>
                                </template>
                              </Select>
                              <label>Comprador</label>
                            </FloatLabel>
                          </div>
                      </div>
                      <div class="flex pt-6 justify-end">
                          <Button label="Next" icon="pi pi-arrow-right" iconPos="right" @click="activateCallback('2')" />
                      </div>
                  </StepPanel>
                  <StepPanel v-slot="{ activateCallback }" value="2">
                      <!-- precio al que se vende ------------------------------------------------ -->
                      <div>
                        <p>Precio del producto {{ selectedProduct?.price }} €</p>
                        <FloatLabel>
                            <InputNumber inputId="local-user" :minFractionDigits="2" fluid id="price-product"/>
                            <label for="price-product">Precio final</label>
                        </FloatLabel>
                      </div>
                      <div class="flex pt-6 justify-between">
                          <Button label="Back" severity="secondary" icon="pi pi-arrow-left" @click="activateCallback('1')" />
                      </div>
                  </StepPanel>
              </StepPanels>
            </Stepper>
          </form>
        <template #footer>
            <Button label="Cancelar" text severity="secondary" @click="visible = false" autofocus />
            <Button label="Vender" outlined severity="secondary" @click="visible = false" autofocus />
        </template>
      </Dialog>
    </div>

  </div>
</template>

<script setup>
import {defineProps, inject, onMounted, ref} from 'vue';
import axios from 'axios';
import { Dialog } from 'primevue';
import { Stepper, StepList, StepPanels, StepItem, Step, StepPanel } from 'primevue';




// Variables del Dialog
const visible = ref(false);
const selectedProduct = ref(null);
const userInterested = ref(null);
const openDialog = async (producto) => {
  selectedProduct.value = producto; 
  visible.value = true; // abre el Dialog
  console.log('SELECTEDPRODUCT -->', selectedProduct);


  fetchProducts = async (endpoint, type) => {
  loading.value = true;
  error.value = null;
  selectedTab.value = type; // guardamos que se esta viendo

  try {
    const response = await axios.get(`/api/getUsersConversations/${producto.id}`);
    // guarda info segun seccion
    if (type === 'purchases') purchases.value = response.data.data || [];
  } catch (err) {
    error.value = "Error al obtener los datos.";
  } finally {
    loading.value = false;
  }
};

};
const usersInterested = ref([
    { name: 'Admin', code: '1' },
    { name: 'Manolo', code: '2' },
    { name: 'Paco', code: '3' },
    { name: 'Ramon', code: '4' },
    { name: 'Monica', code: '5' }
]);
// const usersInterested = ref([]);


const props = defineProps({
  productos: Array, // Recibe la lista de products
  actualizarFavoritos: Function, // Recibe función para actualizar favoritos en la vista padre
  esVistaFavoritos: Boolean,
  actualizarProductos: Function,
});


const responsiveOptions = ref([
  { breakpoint: '991px', numVisible: 4 },
  { breakpoint: '767px', numVisible: 3 },
  { breakpoint: '575px', numVisible: 1 }
]);

const favoritos = ref([
]);

// Sincronizamos la lista de favoritos al montar el componente
onMounted(async () => {
  if (props.actualizarFavoritos) {
    try {
      const favoritosData = await props.actualizarFavoritos();
      // Asegúrate de que favoritosData sea un array
      favoritos.value = Array.isArray(favoritosData) ? favoritosData : [];
    } catch (error) {
      console.error('Error al cargar favoritos:', error);
      favoritos.value = [];
    }
  }
});


const swal = inject('$swal')


const gestorFavoritos = async(productId) => {
  console.log("Este es el id del producto que has clicado: " + productId);
  const respuesta = await axios.post(`/api/gestor-favoritos/${productId}`);
  if (props.esVistaFavoritos){
      await props.actualizarFavoritos();
  } else {
      await props.actualizarProductos();
  }
}
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
  width: 250px;
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
