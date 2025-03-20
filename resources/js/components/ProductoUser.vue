<template>
  <div class="row gap-5 justify-items-left justify-content-center">
    <div v-for="producto in productos" class="producto col-6 col-md-4 col-lg-3">
      <router-link :to="'/products/detalle/' + producto.id" :key="producto.id" class="contenido-producto">
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
        <Tag icon="" severity="secondary" :value="producto.estado.name" rounded class="my-3"></Tag>
        
      </router-link>
      <div class="d-flex gap-5">
        <Button label="Vender" @click.stop="openSellProduct(producto)" />
        <Button label="Editar" @click.stop="openEditProduct(producto)" />
        <Button label="Eliminar" @click.stop="openDeleteProduct(producto)" style="background-color: #B51200!important; border: #b51500!important;" />
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
      <Dialog v-model:visible="visibleEditProduct" modal :header="'Editando '+selectedProduct?.title" style=" width: 350px; height: 400px; ">
        <Tabs value="0">
          <TabList>
              <Tab appendTo=".show" value="0">Foto de Perfil 📸</Tab>
              <Tab appendTo=".show" value="1">Detalles del Perfil 📝</Tab>
          </TabList>
          <TabPanels class="w-100">
              <TabPanel value="0">
                <div class="mb-3">
              <h3>Fotos</h3>
              <DropZoneV v-model="product.thumbnails" class="imagenes"/>
              <div class="text-danger mt-1">
                {{ errors.thumbnails }}
              </div>
              <div class="text-danger mt-1">
                <div v-for="message in validationErrors?.thumbnails">
                  {{ message }}
                </div>
              </div>
            </div>
              </TabPanel>
              <TabPanel value="1">

                <form @submit.prevent="editUser" class="d-flex flex-column gap-5">
                  <div class="d-flex flex-column gap-5">
                    <!-- TITULO ---------------------------------------------------- -->
                    <div class="">
                      <FloatLabel>
                          <InputText appendTo=".show" v-model="product.title" inputId="title-product" fluid id="title"/>
                          <label for="title-product">Nombre del producto</label>
                      </FloatLabel>
                      <div class="text-danger mt-1">{{ errors.title }}</div>
                      <div class="text-danger mt-1">
                          <div v-for="message in validationErrors?.title">
                              {{ message }}
                          </div>
                      </div>
                    </div>

                    <div class="d-flex gap-3 w-100">
                      <!-- CONTENIDO ---------------------------------------------------- -->
                      <div class="">
                        <FloatLabel>
                            <InputText appendTo=".show" v-model="product.content" inputId="content-product" fluid id="content" rows="5" cols="50" />
                            <label for="content-product">Contenido</label>
                        </FloatLabel>
                        <div class="text-danger mt-1">{{ errors.content }}</div>
                        <div class="text-danger mt-1">
                          <div v-for="message in validationErrors?.content">
                              {{ message }}
                          </div>
                        </div>
                      </div>
                      <!-- PRECIO ---------------------------------------------------- -->
                      <div class="">
                        <FloatLabel>
                            <InputNumber v-model="product.price" inputId="price-product" :minFractionDigits="2" fluid id="price-product"/>
                            <!-- <InputText appendTo=".show" v-model="product.price" inputId="price-product" fluid id="price"/> -->
                            <label for="price-product">Precio</label>
                        </FloatLabel>
                        <div class="text-danger mt-1">{{ errors.price }}</div>
                        <div class="text-danger mt-1">
                          <div v-for="message in validationErrors?.price">
                              {{ message }}
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- ESTADO ---------------------------------------------------- -->
                    <div class="">
                      <FloatLabel>
                        <select
                            v-model="product.estado"
                            id="product-estado"
                            class="form-select"
                        >
                          <option value="" disabled>Select a state</option>
                          <option
                              v-for="estado in estadoList"
                              :key="estado.id"
                              :value="estado.id"
                          >
                            {{ estado.name }}
                          </option>
                        </select>
                          <label for="email-user">Estado</label>
                      </FloatLabel>
                      <div class="text-danger mt-1">{{ errors.estado_id }}</div>
                      <div class="text-danger mt-1">
                          <div v-for="message in validationErrors?.estado_id">
                              {{ message }}
                          </div>
                      </div>
                    </div>
                    <!-- CATEGORIA ---------------------------------------------------- -->
                    <div class="">
                      <FloatLabel>
                        <Select
                          v-model="product.category"
                          :options="categoryList"
                          optionLabel="name"
                          optionValue="id"
                          :loading="isLoading"
                          :disabled="isLoading"
                          class="w-full md:w-80"
                          appendTo=".show"
                          />                          
                          <label for="password-user">Selecciona categoria</label>
                      </FloatLabel>
                      <div class="text-danger mt-1">{{ errors.categories }}</div>
                      <div class="text-danger mt-1">
                          <div v-for="message in validationErrors?.categories">
                              {{ message }}
                          </div>
                      </div>
                    </div>
                  </div>
                  <Button type="submit" label="Actualizar" class="w-100" appendTo=".show" outlined severity="secondary" autofocus />
                </form>
              </TabPanel>
          </TabPanels>
        </Tabs>
      </Dialog>
      <Dialog v-model:visible="visibleDelete" modal :header="'Eliminar '+selectedProduct?.title" style=" width: 350px; height: 400px; ">
        <div class="card flex justify-center">
          <p>Introduce el siguiente codico para eliminar <b>7405</b></p>
          <InputOtp v-model="confirmationDelete" integerOnly />
        </div>
      </Dialog>
    </div>

  </div>
</template>

<script setup>
import {defineProps, ref} from 'vue';
import axios from 'axios';
import { Dialog } from 'primevue';
import { Stepper, StepList, StepPanels, StepItem, Step, StepPanel, InputOtp, Tabs, TabList, Tab, TabPanels, TabPanel } from 'primevue';
import {useForm, useField, defineRule} from "vee-validate";
import DropZoneV from "@/components/DropZone-varios.vue";
import useCategories from "@/composables/categories";
import useProducts from "@/composables/products.js";
import {required, min} from "@/validation/rules"
defineRule('required', required)
defineRule('min', min);


// Variables del Dialog
const visible = ref(false);
const visibleEditProduct = ref(false);
const visibleDelete = ref(false);
const selectedProduct = ref(null);
const usersInterested = ref([]);
const selectedUserId = ref(null); // Guardará el ID del usuario seleccionado
const selectedUser = ref(null);
const user = ref(null);
const finalPrice = ref(0);
const confirmationDelete = ref(null);

    // Define a validation schema
    const schema = {
        title: 'required|min:8',
        content: 'required|min:25',
        category: null,
        price: 'required|min:1',
        estado: null,
        thumbnails: null
    }
    // Create a form context with the validation schema
    const { validate, errors, resetForm } = useForm({ validationSchema: schema })
    // Define actual fields for validation
    const { value: title } = useField('title', null, { initialValue: '' });
    const { value: content } = useField('content', null, { initialValue: '' });
    const { value: price } = useField('price', null, { initialValue: '' });
    const { value: estado } = useField('estado', null, { initialValue: '' });
    const { value: category } = useField('category', null, { initialValue: '', label: 'category' });
    const { value: thumbnails } = useField('thumbnails', null, { initialValue: [] });
    const { categoryList, getCategoryList } = useCategories()
    const { product: productData,getEstadoList,estadoList, getProduct, updateProduct, validationErrors, isLoading } = useProducts()

    const product = ref({
        title,
        content,
        price,
        estado,
        category,
        thumbnails
    })


const openSellProduct = async (producto) => {
  selectedProduct.value = producto; 
  visible.value = true;
  console.log('🔎 SELECTEDPRODUCT -->', selectedProduct);
  getInterested(producto.id);
};
const openEditProduct = async (producto) => {
  selectedProduct.value = producto; 
  selectedUser.value = user;
  product.value.title = selectedProduct.value.title;
  product.value.content = selectedProduct.value.content;
  product.value.price = selectedProduct.value.price;
  product.value.estado = selectedProduct.value.estado;
  product.value.category = selectedProduct.value.category;
  visibleEditProduct.value = true; 
  console.log('🔎 SELECTEDPRODUCT -->', selectedProduct);
};
const openDeleteProduct = async (producto) => {
  selectedProduct.value = producto; 
  visibleDelete.value = true; 
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
});


const responsiveOptions = ref([
  { breakpoint: '991px', numVisible: 4 },
  { breakpoint: '767px', numVisible: 3 },
  { breakpoint: '575px', numVisible: 1 }
]);

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
