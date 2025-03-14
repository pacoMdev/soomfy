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
      <div class="d-flex gap-5">
        <Button label="Vender" @click.stop="openSellProduct(producto)" />
        <Button label="Editar" @click.stop="openEditProduct(producto)" />
      </div>
        <Dialog v-model:visible="visible" modal :header="'Vendiendo '+selectedProduct?.title" style=" width: 350px; height: 400px; ">
          <form @submit.prevent="sellProduct">
            <Stepper value="1">
              <StepList>
                  <Step value="1">Usuario al que vender</Step>
                  <Step value="2">Precio</Step>
              </StepList>
              <StepPanels>
                  <StepPanel v-slot="{ activateCallback }" value="1">
                    <div>
                      <div class="flex flex-col h-48">
                        <!-- Usuario al que se vende ------------------------------------------------ -->
                          <div class="w-100">
                            <FloatLabel>
                              <Select v-model="selectedUserId" name="name" :options="usersInterested" optionLabel="name" optionValue="id" fluid class="w-100" required>
                                <template #option="{option: user}">
                                  <div class="flex items-center">
                                      <img alt="imagen" :src="user.media?.[0].original_url || ''" :class="`mr-2`" style="width: 50px; height: 50px; border-radius: 50%;" />
                                    </div>
                                    <h4 class="d-flex align-items-center m-0">{{ user.name }}</h4>
                                </template>
                              </Select>
                              <label>Comprador</label>
                            </FloatLabel>
                          </div>
                      </div>
                      <div class="flex pt-6 justify-end">
                          <Button label="Next" icon="pi pi-arrow-right" iconPos="right" @click="activateCallback('2')" />
                      </div>
                    </div>
                  </StepPanel>
                  <StepPanel v-slot="{ activateCallback }" value="2">
                    <div class="d-flex flex-column gap-5">
                      <!-- precio al que se vende ------------------------------------------------ -->
                      <div>
                        <p>Precio del producto {{ selectedProduct?.price }} €</p>
                        <FloatLabel>
                            <InputNumber v-model="finalPrice" inputId="local-user" :minFractionDigits="2" fluid id="price-product"/>
                            <label for="price-product">Precio final</label>
                        </FloatLabel>
                      </div>
                      <div class="flex pt-6 w-100 justify-between gap-5">
                          <Button label="Back" severity="secondary" icon="pi pi-arrow-left" @click="activateCallback('1')" />
                          <Button type="submit" label="Vender" outlined severity="secondary" @click="visible = false" autofocus />
                      </div>
                    </div>
                  </StepPanel>
              </StepPanels>
            </Stepper>
          </form>
      </Dialog>
      <Dialog v-model:visible="visibleEdit" modal :header="'Editando '+selectedProduct?.title" style=" width: 350px; height: 400px; ">

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
const visibleEdit = ref(false);
const selectedProduct = ref(null);
const usersInterested = ref([]);
const selectedUserId = ref(null); // Guardará el ID del usuario seleccionado
const finalPrice = ref(0);

const openSellProduct = async (producto) => {
  selectedProduct.value = producto; 
  visible.value = true;
  console.log('🔎 SELECTEDPRODUCT -->', selectedProduct);
  getInterested(producto.id);
};
const openEditProduct = async (producto) => {
  selectedProduct.value = producto; 
  visibleEdit.value = true; 
  console.log('🔎 SELECTEDPRODUCT -->', selectedProduct);
};

const getInterested = async (productId) => {
  try {
    const response = await axios.get(`/api/getUsersConversations/${productId}`);
    usersInterested.value = response.data || [];
    console.log('🔎 USERS INTERESTED  --->', usersInterested);
  } catch (err) {
    console.log("Error al obtener los datos.");
  }
};

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


const sellProduct = async () => {
  try{
    const response = await axios.post('/api/sellProduct', {
      userBuyer_id: selectedUserId.value,
      product_id: selectedProduct.value.id,
      finalPrice: finalPrice.value,
      isToSend: false,
    });
    console.log("Producto vendido -->", response.data);

  }catch(error){
    console.error('Error al vender el producto:', error);
  }
}


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
