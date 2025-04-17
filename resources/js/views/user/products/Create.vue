<template>
  <div class="show"></div>
  <div class="container-fluid py-5">
      <form @submit.prevent="submitForm" class="product-form">
          <div class="card">
              <Stepper value="1" class="w-100">
                  <StepList>
                      <Step value="1">Informacion Basica</Step>
                      <Step value="2">Informacion Adicional</Step>
                  </StepList>
                  <StepPanels>
                      <StepPanel v-slot="{ activateCallback }" value="1">
                          <div class="form-panel">
                              <!-- IMAGE PRODUCT -->
                              <div class="card-body mb-4">
                                  <h3 class="mb-3">Fotos del producto</h3>
                                  <div class="row">
                                      <div class="col-12">
                                          <DropZoneV v-model="product.thumbnails" class="d-flex flex-wrap"/>
                                      </div>
                                  </div>
                              </div>
                              <!-- TITLE -->
                              <div class="mb-4">
                                  <FloatLabel>
                                      <InputText v-model="product.title" id="product-title" class="w-100" />
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
                          <!-- BUTTONS OPTIONS -->
                          <div class="d-flex justify-content-end mt-4">
                              <Button label="Siguiente" icon="pi pi-arrow-right" iconPos="right" @click="activateCallback('2')" class="px-4" />
                          </div>
                      </StepPanel>
  
                      <StepPanel v-slot="{ activateCallback }" value="2">
                        <div class="form-panel">
                            <!-- CONTENT -->
                            <div class="mb-4">
                                <FloatLabel>
                                    <Textarea id="content" v-model="product.content" rows="5" class="w-100" />
                                    <label for="content">Descripción del producto</label>
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

                            <!-- CATEGORY & ESTADO -->
                            <div class="row mb-4">
                                <!-- CATEGORY -->
                                <div class="col-12 col-md-6 mb-3 mb-md-0">
                                    <FloatLabel>
                                        <MultiSelect
                                            v-model="product.categories"
                                            :options="categoryList"
                                            optionLabel="name"
                                            optionValue="id"
                                            :loading="isLoading"
                                            :disabled="isLoading"
                                            class="w-100"
                                            appendTo=".show"
                                        />
                                        <label>Categorías</label>
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
        
                                <!-- ESTADO -->
                                <div class="col-12 col-md-6">
                                    <FloatLabel>
                                    <Select
                                        v-model="product.estado"
                                        :options="estadoList"
                                        optionLabel="name"
                                        optionValue="id"
                                        :loading="isLoadingEstados"
                                        class="w-100"
                                        appendTo=".show"
                                    />
                                    <label for="estado">Estado</label>
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
                            </div>
    
                            <!-- PRICE -->
                            <div class="mb-4">
                                <FloatLabel>
                                    <InputNumber v-model="product.price" inputId="local-user" :minFractionDigits="2" class="w-100" id="price-product"/>
                                    <label for="price-product">Precio</label>
                                </FloatLabel>
                            </div>
    
                            <!-- SHIPPING TOGGLE -->
                            <div class="mb-4 card p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="m-0 fw-medium">¿Es un producto para enviar? 📦</p>
                                    <ToggleSwitch v-model="product.toSend" />
                                </div>
                            </div>
                            
                            <div v-if="product.toSend == 1" class="bg-light p-3 rounded mb-4">
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <FloatLabel>
                                            <Select
                                                v-model="product.weight"
                                                :options="pseoProducto"
                                                optionLabel="name"
                                                optionValue="total"
                                                :loading="isLoadingEstados"
                                                class="w-100"
                                                appendTo=".show"
                                            />
                                            <label for="estado">Peso</label>
                                        </FloatLabel>
                                        <div class="text-danger mt-1">
                                            <div v-for="message in validationErrors?.weight">
                                                {{ message }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                                        <FloatLabel>
                                            <InputNumber v-model="product.width" class="w-100" />
                                            <label>Ancho (cm)</label>
                                        </FloatLabel>
                                        <div class="text-danger mt-1">
                                            <div v-for="message in validationErrors?.width">
                                                {{ message }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <FloatLabel>
                                            <InputNumber v-model="product.heigth" class="w-100" />
                                            <label>Alto (cm)</label>
                                        </FloatLabel>
                                        <div class="text-danger mt-1">
                                            <div v-for="message in validationErrors?.heigth">
                                                {{ message }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- BUTTONS OPTIONS -->
                            <div class="d-flex flex-column flex-md-row justify-content-between mt-4">
                                <Button label="Atrás" severity="secondary" icon="pi pi-arrow-left" @click="activateCallback('1')" class="mb-3 mb-md-0" />
                                <Button :disabled="isLoading" class="p-button-primary">
                                    <div v-show="isLoading" class="spinner-border spinner-border-sm me-2" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                    <span v-if="isLoading">Procesando...</span>
                                    <span v-else>Publicar</span>
                                </Button>
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
.product-form {
    max-width: 1200px; /* Ampliado de 800px a 1200px para más espacio */
    margin: 0 auto;
}

.form-panel {
    padding: 1.5rem;
}

/* Small visual tweaks that no están en bootstrap */
.card {
    border-radius: 0.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

</style>