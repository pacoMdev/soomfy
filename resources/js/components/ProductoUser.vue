<template>
  <div class="show"></div>
  <div class="row gap-5 justify-items-left justify-content-center">
    <div v-for="producto in productos" class="producto col-12 col-sm-6 col-md-4 col-lg-3">
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
    </div>
    <!-- SELL PRODUCT --------------------------------------------- -->
    <Dialog v-model:visible="visible" modal :header="'Vendiendo '+selectedProduct?.title" style=" width: min(90vw, 500px); height: min(90vh, 400px); " appendTo=".show">
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
                              <Select v-model="selectedUserId" name="name" :options="usersInterested" optionLabel="name" optionValue="id" fluid class="w-100" appendTo=".show" required>
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
                          <Button type="submit" label="Vender" outlined severity="secondary" @click="visible = false" :disabled="selectedBuyer" autofocus />
                      </div>
                    </div>
                  </StepPanel>
              </StepPanels>
            </Stepper>
          </form>
    </Dialog>
    <!-- EDIT PRODUCT --------------------------------------------- -->
    <Dialog v-model:visible="visibleEditProduct" modal :header="'Editando '+selectedProduct?.title" style=" width: min(90vw, 500px); height: min(90vh, 600px); " appendTo=".show">
      <Tabs value="0">
        <TabList>
            <Tab appendTo=".show" value="0" class="w-50">Foto de Perfil 📸</Tab>
            <Tab appendTo=".show" value="1" class="w-50">Detalles del Perfil 📝</Tab>
        </TabList>
        <TabPanels class="w-100">
            <TabPanel value="0">
              <div class="mb-3">
                <h3>Fotos</h3>
                <DropZoneV v-model="productData.thumbnails" class="imagenes"/>
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

              <form @submit.prevent="submitEditUser" class="d-flex flex-column gap-5 py-4">
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

                  <!-- CONTENIDO ---------------------------------------------------- -->
                  <div class="">
                    <FloatLabel>
                        <Textarea appendTo=".show" v-model="product.content" inputId="content-product" fluid id="content" rows="5" cols="50" />
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
                        <label for="price-product">Precio</label>
                    </FloatLabel>
                    <div class="text-danger mt-1">{{ errors.price }}</div>
                    <div class="text-danger mt-1">
                      <div v-for="message in validationErrors?.price">
                          {{ message }}
                      </div>
                    </div>
                  </div>
                  <div class="d-flex w-100 gap-3">
                    <!-- ESTADO ---------------------------------------------------- -->
                    <div class="w-50">
                    <FloatLabel>
                      <Select
                        v-model="productData.estado"
                        :options="estadoList"
                        optionLabel="name"
                        optionValue="id"
                        :loading="isLoading"
                        :disabled="isLoading"
                        class="w-full md:w-80"
                        appendTo=".show"
                        />                          
                        <label for="password-user">Selecciona estado</label>
                    </FloatLabel>
                    <div class="text-danger mt-1">{{ errors.estado_id }}</div>
                    <div class="text-danger mt-1">
                        <div v-for="message in validationErrors?.estado_id">
                            {{ message }}
                        </div>
                    </div>
                  </div>
                  <!-- CATEGORIA ---------------------------------------------------- -->
                  <div class="w-50">
                    <FloatLabel>
                      <Select
                        v-model="productData.category"
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
                  <!-- CATEGORIA ---------------------------------------------------- -->
                  <div class=" card d-flex flex-row m-0 p-2 justify-content-between">
                    <p class="m-0">Para enviar 📦</p>
                    <ToggleSwitch v-model="productData.toSend" />
                  </div>
                </div>
                <Button type="submit" label="Actualizar" class="w-100" appendTo=".show" outlined severity="secondary" autofocus />
              </form>
            </TabPanel>
        </TabPanels>
      </Tabs>
    </Dialog>
    <!-- DELETE PRODUCT --------------------------------------------- -->
    <Dialog v-model:visible="visibleDelete" modal :header="'Eliminar '+selectedProduct?.title" style=" width: min(90vw, 500px); height: min(90vh, 400px); " appendTo=".show">
      <div class="card flex justify-center gap-3">
        <p>Introduce el siguiente codico para eliminar <b>{{ randomNum }}</b></p>
        <InputOtp v-model="confirmationDelete" integerOnly  class="mx-auto"/>
        <Button @click="delProduct(selectedProduct.id)" label="Eliminar" :disabled="isBtnDelete" />

      </div>
    </Dialog>
  </div>
</template>

<script setup>
import { defineProps, ref, watch, watchEffect, onMounted } from 'vue';
import { Dialog, Textarea } from 'primevue';
import { Stepper, StepList, StepPanels, StepItem, Step, StepPanel, InputOtp, Tabs, TabList, Tab, TabPanels, TabPanel, ToggleSwitch } from 'primevue';
import {useForm, useField, defineRule} from "vee-validate";
import DropZoneV from "@/components/DropZone-varios.vue";
import useCategories from "@/composables/categories";
import useProducts from "@/composables/products.js";
import {required, min} from "@/validation/rules"
import useFirebase from "../composables/firebase";

defineRule('required', required)
defineRule('min', min);


const {
  getUsersIdInterested,
 } = useFirebase();


// Variables del Dialog
const visible = ref(false);
const visibleEditProduct = ref(false);
const visibleDelete = ref(false);
const user = ref(null);
const confirmationDelete = ref(null);
const randomNum = ref(null);
const isBtnDelete = ref(true);
const selectedBuyer = ref(true);

    // Define a validation schema
    const schema = {
        title: 'required|min:5',
        content: 'required|min:5',
        category: null,
        price: 'required|min:1',
        estado: null,
    }
    // Create a form context with the validation schema
    const { validate, errors, resetForm } = useForm({ validationSchema: schema })
    // Define actual fields for validation
    const { value: title } = useField('title', null, { initialValue: '' });
    const { value: content } = useField('content', null, { initialValue: '' });
    const { value: price } = useField('price', null, { initialValue: '' });
    const { value: estado } = useField('estado', null, { initialValue: '' });
    const { value: category } = useField('category', null, { initialValue: '', label: 'category' });
    const { value: toSend } = useField('toSend', null, { initialValue: '' });
    const { value: thumbnails } = useField('thumbnails', null, { initialValue: [] });
    const { categoryList, getCategoryList } = useCategories()
    const { product: productData,getEstadoList,estadoList, 
      getProduct, updateProduct, validationErrors, isLoading, 
      delProduct, getInterested, sellProduct, selectedProduct,
      usersInterested, selectedUserId, selectedUser, finalPrice} = useProducts()

    const product = ref({
        title,
        content,
        price,
        estado,
        category,
        toSend,
        thumbnails
    });
    onMounted(() =>{
        getCategoryList()
        getEstadoList()
    });
    watch ( selectedUserId, () => {
      if (selectedUserId != []){
        selectedBuyer.value = false;
      }else{ 
        selectedBuyer.value = true;
      }
    });
    watch( confirmationDelete, () => {
      if(confirmationDelete.value == randomNum.value){
        isBtnDelete.value = false;
        console.log('Esta permitido eliminar el Producto');
      }else{
        isBtnDelete.value = true;
      }
    });


    watchEffect(() => {
      console.log(' CHANGES ON DATA PRODUCT -->', productData);
      if (productData.value) {
        // Asignar valores del producto
        product.value.title = productData.value.title;
        product.value.content = productData.value.content;
        product.value.price = productData.value.price;
        product.value.estado = productData.value.estado?.id || '';
        product.value.category = productData.value.category?.id || '';
        product.value.toSend = productData.value.toSend;


        // Validar imágenes en resized_image o thumbnails
        if (productData.value.media && productData.value.media.length > 0) {
          product.value.thumbnails = productData.value.media.map((img) => ({
            img: img.original_url,
            file: null,
          }));
        } else if (productData.value.resized_image && Object.keys(productData.value.resized_image).length > 0) {
          // Convertir el objeto de imágenes a un array
          product.value.thumbnails = Object.values(productData.value.resized_image).map(img => ({
            img: img.original_url,
            file: null,
            id: img.uuid
          }));
        }
        else {
            product.value.thumbnails = [];
        }
        // Asegúrate de que haya suficientes slots para el máximo de imágenes
        while (product.value.thumbnails.length < 3) {
          product.value.thumbnails.push({ img: "", file: null });
        }


      }
    });




  const openSellProduct = async (producto) => {
    selectedProduct.value = producto; 
    visible.value = true;
    console.log('🔎 SELECTEDPRODUCT -->', selectedProduct);
    const usersInterestedIds = await getUsersIdInterested(producto.id);
    console.log('🔎 usersInterested -->', usersInterestedIds);
    getInterested(usersInterestedIds);



  };
  const openEditProduct = async (producto) => {
    selectedProduct.value = producto; 
    selectedUser.value = user;
    visibleEditProduct.value = true; 

    if (selectedProduct.value.resized_image && Object.keys(selectedProduct.value.resized_image).length > 0) {

      // Convertir el objeto de imágenes a un array
      productData.value.thumbnails = Object.values(selectedProduct.value.resized_image).map(img => ({
        img: img.original_url,
        file: null,
        id: img.uuid
      }));
    }
    else {
      product.value.thumbnails = [];
    }
    productData.value.title = selectedProduct.value.title;
    productData.value.content = selectedProduct.value.content;
    productData.value.price = selectedProduct.value.price;
    productData.value.estado = selectedProduct.value.estado.id;
    productData.value.category = selectedProduct.value.category.id;
    productData.value.toSend = selectedProduct.value.toSend === 1;
    console.log('PRODUCT -->', productData);
    console.log('🔎 SELECTEDPRODUCT -->', selectedProduct);
  };
  const openDeleteProduct = async (producto) => {
    selectedProduct.value = producto; 
    visibleDelete.value = true; 
    randomNum.value = Math.floor(1000 + Math.random() * 9000);
    confirmationDelete.value='';
    console.log('🔎 SELECTEDPRODUCT -->', selectedProduct);
  };

  

  const props = defineProps({
    productos: Array, // Recibe la lista de products
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


onMounted(async () => {
  try {
    // Suposem que ja tens carregat el producte en 'product.value'

    // Convertir les imatges del format del servidor al format del Dropzone
    if (product.value.resized_image) {
      // Converteix l'objecte d'imatges a un array
      const mediaArray = Object.values(product.value.resized_image);

      // Mapeja cada imatge al format que espera el Dropzone
      thumbnails.value = mediaArray.map(media => ({
        img: media.original_url, // URL de la imatge
        file: null, // No tenim l'arxiu original, només la URL
        id: media.uuid // Guardar el UUID per si necessitem eliminar-la després
      }));
    }
  } catch (error) {
    console.error('Error al carregar les imatges:', error);
  }
});
function submitEditUser() {
      const formData = new FormData();
      const productId = selectedProduct.value.id;
      formData.append('id', productId);
      formData.append('title', product.value.title);
      formData.append('content', product.value.content);
      formData.append('price', product.value.price);
      formData.append('estado_id', productData.value.estado);
      formData.append('category_id', productData.value.category);
      formData.append('toSend', product.value.toSend ? 1 : 0);
      if (productData.value.thumbnails && productData.value.thumbnails.length) {
        productData.value.thumbnails.forEach((item, index) => {
          console.log(`Thumbnail ${index}: ORDER = ${productData.value.thumbnails[index].order}, ID = ${productData.value.thumbnails[index].id}`);

          if (item instanceof File || (item.file && item.file instanceof File)) {
            const file = item instanceof File ? item : item.file;
            // Imagen insertada
            formData.append(`thumbnails[${index}]`, file);
            // Comprobamos si el id del thumbnails existe, si existe se agrega
            if(item.id) {
              formData.append(`thumbnails_previous_id[${index}]`, item.id);
            }
            // Adjuntamos el orden que tiene que tener
            formData.append(`thumbnails_order[${index}]`, item.order || index);
          }
        });
      }


      for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + (pair[1] instanceof File ? `File: ${pair[1].name}` : pair[1]));
      }

      validate().then((form) => {
        if (form.valid) {
          updateProduct(formData, productId)
        } else {
          // Desplegar los errores de validación si los datos no son válidos
          console.log("Errores de validación:", errors);
        }
      });
      window.location.reload();
      visibleEditProduct.value = false; 
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
