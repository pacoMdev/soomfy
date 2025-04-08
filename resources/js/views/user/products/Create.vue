
<template>
  <div class="show"></div>
  <div class="py-5">
      <form @submit.prevent="submitForm" style=" width: min(90vw, 800px); height: min(90vh, 550px); " class="mx-auto">
          <div class="card flex justify-center">
              <Stepper value="1" class="basis-[50rem]">
                  <StepList>
                      <Step value="1">Informacion Basica</Step>
                      <Step value="2">Informacion Adicional</Step>
                  </StepList>
                  <StepPanels>
                      <StepPanel v-slot="{ activateCallback }" value="1">
                          <div class="d-flex flex-column gap-5">
                              <!-- IMAGE PRODUCT ---------------------------------------------------- -->
                              <div class="card-body">
                                  <h3>Fotos</h3>
                                      <DropZoneV v-model="product.thumbnails" class="imagenes"/>
                              </div>
                              <!-- TITLE ---------------------------------------------------- -->
                              <div class="mb-3 input-nombre">
                                  <FloatLabel>
                                      <InputText v-model="product.title" id="product-title" />
                                      <label for="product-title">Nombre del producto</label>
                                  </FloatLabel>
                                  <div class="text-danger mt-1">
                                      {{ errors.title }}
                                  </div>
                                  <div class="text-danger mt-1">
                                      <div v-for="message in validationErrors?.title">
                                          {{ message }}
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <!-- BUTTONS OPTIONS ---------------------------------------------------- -->
                          <div class="flex pt-6 justify-end">
                              <Button label="Next" icon="pi pi-arrow-right" iconPos="right" @click="activateCallback('2')" />
                          </div>
                      </StepPanel>
  
                      <StepPanel v-slot="{ activateCallback }" value="2">
                        <div class="d-flex flex-column gap-3">
                            <!-- CONTENT ---------------------------------------------------- -->
                            <div class="mb-3 w-50">
                                <FloatLabel>
                                    <Textarea id="content" v-model="product.content" rows="5" cols="45" />
                                    <label for="content">Content</label>
                                </FloatLabel>
                                <div class="text-danger mt-1">
                                    {{ errors.content }}
                                </div>
                                <div class="text-danger mt-1">
                                    <div v-for="message in validationErrors?.content">
                                        {{ message }}
                                    </div>
                                </div>
                            </div>
                            <!-- CATEGORY ---------------------------------------------------- -->
                            <div class="mb-3 input-categorias w-50">
                                <FloatLabel>
                                    <MultiSelect
                                        v-model="product.categories"
                                        :options="categoryList"
                                        optionLabel="name"
                                        optionValue="id"
                                        :loading="isLoading"
                                        :disabled="isLoading"
                                        class="w-full md:w-80"
                                        appendTo=".show"
                                    />
                                    <label>Selecciona categorías</label>
                                </FloatLabel>
                                <div class="text-danger mt-1">
                                    {{ errors.categories }}
                                </div>
                                <div class="text-danger mt-1">
                                    <div v-for="message in validationErrors?.categories">
                                        {{ message }}
                                    </div>
                                </div>
                            </div>
    
                            <!-- ESTADO ---------------------------------------------------- -->
                            <div class="mb-3 input-estado w-50">
                                <FloatLabel>
                                <Select
                                    v-model="product.estado"
                                    :options="estadoList"
                                    optionLabel="name"
                                    optionValue="id"
                                    :loading="isLoadingEstados"
                                    class="w-full md:w-80"
                                    appendTo=".show"
                                />
                                <label for="estado">Selecciona estado</label>
                                </FloatLabel>
    
                                <div class="text-danger mt-1">
                                {{ errors.estado_id }}
                                </div>
                                <div class="text-danger mt-1">
                                <div v-for="message in validationErrors?.estado_id">
                                    {{ message }}
                                </div>
                                </div>
                            </div>
    
                            <!-- PRICE ---------------------------------------------------- -->
                            <div class="mb-3 input-price w-50">
                                <FloatLabel>
                                    <InputNumber v-model="product.price" inputId="local-user" :minFractionDigits="2" fluid id="price-product"/>
                                    <label for="price-product">Precio</label>
                                </FloatLabel>
                            </div>
    
                            <!-- PRICE ---------------------------------------------------- -->
                            
                            <div class="mb-3 input-toSend card d-flex flex-row gap-3 p-3 p-0 m-0 w-50 justify-content-between">
                                <p class="m-0">Para enviar 📦</p>
                                <ToggleSwitch v-model="product.toSend" />
                            </div>
                            
                            <div v-if="product.toSend == 1" class="d-flex flex-column gap-3 py-3 ">
                                <div class="mb-3 input-estado">
                                    <FloatLabel>
                                        <Select
                                            v-model="product.weight"
                                            :options="pseoProducto"
                                            optionLabel="name"
                                            optionValue="total"
                                            :loading="isLoadingEstados"
                                            class="w-full md:w-80"
                                            appendTo=".show"
                                        />
                                        <label for="estado">Selecciona el peso</label>
                                        </FloatLabel>
    
                                        <div class="text-danger mt-1">
                                        {{ errors.estado_id }}
                                        </div>
                                        <div class="text-danger mt-1">
                                        <div v-for="message in validationErrors?.weight">
                                            {{ message }}
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3 input-ancho">
                                    <FloatLabel>
                                        <InputNumber v-model="product.width" />
                                        <label for="estado">Introduce el ancho</label>
                                        </FloatLabel>
    
                                        <div class="text-danger mt-1">
                                        {{ errors.estado_id }}
                                        </div>
                                        <div class="text-danger mt-1">
                                        <div v-for="message in validationErrors?.width">
                                            {{ message }}
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3 input-alto">
                                    <FloatLabel>
                                        <InputNumber v-model="product.heigth" />
    
                                        <label for="estado">Introduce la altura</label>
                                        </FloatLabel>
    
                                        <div class="text-danger mt-1">
                                        {{ errors.estado_id }}
                                        </div>
                                        <div class="text-danger mt-1">
                                        <div v-for="message in validationErrors?.heigth">
                                            {{ message }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- BUTTONS OPTIONS ---------------------------------------------------- -->
                            <div class="d-flex gap-4 pt-6 justify-between">
                                <Button label="Back" severity="secondary" icon="pi pi-arrow-left" @click="activateCallback('1')" />
                                <Button :disabled="isLoading" class="p-button secondary">
                                    <div v-show="isLoading" class=""></div>
                                    <span v-if="isLoading">Processing...</span>
                                    <span v-else>Publish</span>
                                </Button>
                                <!-- <Button label="Next" icon="pi pi-arrow-right" iconPos="right" @click="activateCallback('3')" /> -->
                            </div>
                        </div>
                      </StepPanel>
                  </StepPanels>
              </Stepper>
          </div>
      </form>
  </div>
</template>


<script setup>
import {onMounted, reactive, ref, watch} from "vue";
import DropZoneV from "@/components/DropZone-varios.vue";
import useCategories from "@/composables/categories";
import useProducts from "@/composables/products.js";
import {useForm, useField, defineRule} from "vee-validate";
import {required, min} from "@/validation/rules"
import { FloatLabel, MultiSelect, Textarea } from "primevue";


import Stepper from 'primevue/stepper';
import StepList from 'primevue/steplist';
import StepPanels from 'primevue/steppanels';
import StepItem from 'primevue/stepitem';
import Step from 'primevue/step';
import StepPanel from 'primevue/steppanel';


defineRule('required', required)
defineRule('min', min);

const dropZoneActive = ref(true)

// Define a validation schema
const schema = {
    title: 'required|min:5',
    content: 'required|min:5',
    categories: 'required',
    price: 'required',
    estado: 'required',
}
// Create a form context with the validation schema
const {validate, errors} = useForm({validationSchema: schema})

// Define actual fields for validation
const {value: title} = useField('title', null, {initialValue: ''});
const {value: content} = useField('content', null, {initialValue: ''});
const {value: categories} = useField('categories', null, {initialValue: '', label: 'categories'});
const {value: price} = useField('price', null, {initialValue: 0});
const {value: estados} = useField('estado', null, {initialValue: ''});
const {categoryList, getCategoryList} = useCategories()
const {storeUserProduct, getEstadoList, estadoList, validationErrors, isLoading} = useProducts()

const product = ref({
    title,
    content,
    categories: categories,
    thumbnails: [
        { img: "", file: null }, // Contenedor 1
        { img: "", file: null }, // Contenedor 2
        { img: "", file: null }  // Contenedor 3
    ],
    price,
    estado: estados,
})



function submitForm() {
  console.log('Iniciando validación del formulario...');
  // Solo tomar las imágenes que realmente tienen un archivo
  const imagenes = product.value.thumbnails.filter(imagen => imagen.file !== null);
  console.log('Imágenes filtradas:', imagenes);

  // Verificar si hay al menos una imagen
  if (imagenes.length === 0) {
    console.error('Error: Se requiere al menos una imagen');
    errors.thumbnails = 'Se requiere al menos una imagen';
    return;
  }

  validate().then(form => {
    if (form.valid) {
      const formData = new FormData();

      // Añadir campos básicos
      formData.append('title', product.value.title);
      formData.append('content', product.value.content);
      formData.append('price', product.value.price);
      formData.append('estado_id', product.value.estado);
      if (Array.isArray(product.value.categories)) {
          product.value.categories.forEach((category) => {
            formData.append('categories[]', category);
          });
        }

      imagenes.forEach((imagen, index) => {
        if (imagen.file) {
          // Usar simplemente thumbnails[] en lugar de thumbnails[index]
          formData.append(`thumbnails[${index}]`, imagen.file);
        }
      });

      // Verificar lo que se está enviando
      for (let pair of formData.entries()) {
        console.log('Enviando:', pair[0], pair[1]);
      }

      storeUserProduct(formData);
    }
  });
}


onMounted(() => {
    getCategoryList();
    getEstadoList();
    console.log('Categorías cargadas:', categoryList.value);
    console.log('Estados cargados:', estadoList.value);
})

</script>


<style scoped>

.imagenes {
    width: 33%;
}
.input-nombre {
    width: 70%;
}
.input-categorias {
    width: 25%;
}
.input-estado {
    width: 25%;
}
.input-price{
    width: 25%;
}


</style>