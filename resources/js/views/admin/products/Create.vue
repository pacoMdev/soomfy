<template>
  <form @submit.prevent="submitForm">
    <div class="row my-5">
      <div class="col-md-8">
        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <!-- IMAGE PRODUCT -->
            <div class="mb-3">
              <h6>Fotos</h6>
              <DropZoneV v-model="product.thumbnails"/>
              <div class="text-danger mt-1">
                <div v-for="message in validationErrors?.thumbnails">
                  {{ message }}
                </div>
              </div>
            </div>

            <!-- Title -->
            <div class="mb-3">
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

            <!-- Content -->
            <div class="mb-3">
              <FloatLabel>
                <Textarea id="content" v-model="product.content" rows="5" cols="50" />
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
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <h6>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-square" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm8.5 2.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V4.5z"/>
              </svg> Action
            </h6>
            <div class="mt-3 text-center">
              <button :disabled="isLoading" class="btn btn-primary">
                <div v-show="isLoading" class=""></div>
                <span v-if="isLoading">Processing...</span>
                <span v-else>Publish</span>
              </button>
            </div>

            <!-- Category -->
            <h6 class="mt-3">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-square" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm8.5 2.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V4.5z"/>
              </svg> Category
            </h6>
            <div class="mb-3">
              <FloatLabel>
                <Select
                    v-model="product.category_id"
                    :options="categoryList"
                    optionLabel="name"
                    optionValue="id"
                    :loading="isLoading"
                    class="w-full md:w-80"
                />
                <label>Selecciona categoría</label>
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

            <!-- Estado -->
            <h6 class="mt-3">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-square" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm8.5 2.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V4.5z"/>
              </svg> Estado
            </h6>
            <div class="mb-3">
              <FloatLabel>
                <Select
                    v-model="product.estado_id"
                    :options="estadoList"
                    optionLabel="name"
                    optionValue="id"
                    :loading="isLoadingEstados"
                    class="w-full md:w-80"
                />
                <label>Selecciona estado</label>
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

            <!-- Price -->
            <h6 class="mt-3">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-square" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm8.5 2.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V4.5z"/>
              </svg> Precio
            </h6>
            <div class="mb-3">
              <FloatLabel>
                <InputNumber v-model="product.price" inputId="local-user" :minFractionDigits="2" fluid />
                <label>Precio</label>
              </FloatLabel>
              <div class="text-danger mt-1">
                {{ errors.price }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</template>

<script setup>
import {onMounted, reactive, ref, watch} from "vue";
import DropZoneV from "@/components/DropZone-varios.vue";
import useCategories from "@/composables/categories";
import useProducts from "@/composables/products.js";
import {useForm, useField, defineRule} from "vee-validate";
import {required, min} from "@/validation/rules"
import { FloatLabel, Select, Textarea, InputNumber } from "primevue";

defineRule('required', required)
defineRule('min', min);

// Define a validation schema
const schema = {
  title: 'required|min:5',
  content: 'required|min:5',
  category_id: 'required',
  price: 'required',
  estado_id: 'required',
  thumbnails: 'required'
}

// Create a form context with the validation schema
const {validate, errors} = useForm({validationSchema: schema})

// Define actual fields for validation
const {value: title} = useField('title', null, {initialValue: ''});
const {value: content} = useField('content', null, {initialValue: ''});
const {value: category_id} = useField('category_id', null, {initialValue: ''});
const {value: price} = useField('price', null, {initialValue: 0});
const {value: estado_id} = useField('estado_id', null, {initialValue: ''});
const {value: thumbnails} = useField('thumbnails', null, {initialValue: []});

const {categoryList, getCategoryList} = useCategories()
const {storeUserProduct, getEstadoList, estadoList, validationErrors, isLoading} = useProducts()

const product = reactive({
  title,
  content,
  category_id,
  thumbnails: [
    { img: "", file: null },
    { img: "", file: null },
    { img: "", file: null }
  ],
  price,
  estado_id,
})

watch(() => categoryList.value, (newValue) => {
  console.log('Lista de categorías actualizada:', newValue);
}, { deep: true });

watch(() => product.category_id, (newValue) => {
  console.log('Categoría seleccionada:', newValue);
});

function submitForm() {
  console.log('Iniciando validación del formulario...');
  const imagenes = product.thumbnails.filter(imagen => imagen.file !== null);
  console.log('Imágenes filtradas:', imagenes);

  if (imagenes.length === 0) {
    console.error('Error: Se requiere al menos una imagen');
    errors.thumbnails = 'Se requiere al menos una imagen';
    return;
  }

  validate().then(form => {
    if (form.valid) {
      const formData = new FormData();

      formData.append('title', product.title);
      formData.append('content', product.content);
      formData.append('price', product.price);
      formData.append('estado_id', product.estado_id);
      formData.append('category_id', product.category_id);

      let imageIndex = 0;
      imagenes.forEach(imagen => {
        if (imagen.file) {
          formData.append('thumbnails[]', imagen.file);
          imageIndex++;
        }
      });

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