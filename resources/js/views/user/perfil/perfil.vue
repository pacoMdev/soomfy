<template>
  <main>
    <div class="">
      <!-- imagen de perfil -->
      <div class="">
        <div class="user text-center">
          <div class="profile">
            <img :src="imgProfile.avatar" alt="">
            <form @submit.prevent = "onFormSubmit ">
             <Dropzone v-model="imgProfile.thumbnail" />
              <button type="submit" class="secondary-button-2">Cambiar imagen</button>
            </form>>
          </div>
        </div>
      </div>
      <div class="d-flex allign-items-center justify-content-center py-5">
        <div class="container-info-profile">
          <p>Nombre de usuario</p>
          <div class="d-flex gap-2 container-rating">
            <Rating v-model="value" readonly />
            <p>(8)</p>
          </div>
          <p>Aspirante a gran vendedor</p>
        </div>
        <div class="d-flex flex-wrap gap-3 w-100 container container-contador">
          <button class="secondary-button-2"><i class="pi pi-box" style="font-size: 1.5rem"></i>Compras</button>
          <button class="secondary-button-2"><i class="pi pi-dollar" style="font-size: 1.5rem"></i>Ventas</button>
          <button class="secondary-button-2"><i class="pi pi-comment" style="font-size: 1.5rem"></i>Valoaraciones</button>
        </div>
      </div>
      <div>
        <div class="d-flex gap-3 w-100 justify-content-center">
          <button class="secondary-button-2"><i class="pi pi-box" style="font-size: 1.5rem"></i>Compras</button>
          <button class="secondary-button-2"><i class="pi pi-dollar" style="font-size: 1.5rem"></i>Ventas</button>
          <button class="secondary-button-2"><i class="pi pi-comment" style="font-size: 1.5rem"></i>Valoaraciones</button>
        </div>
        <div class="container w-100 d-flex gap-5">
          <ProductoNew :productos="productos" />
        </div>
      </div>
    </div>
  </main>
</template>


<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Dropzone from '../../../components/Dropzone.vue';
import * as yum from "yup";
import { es } from "yup-locales";
import useProfile from "@/composables/profile.js";
import authStore from "@/composables/auth.js";

// Importo imgProfile de profile.js
const { router,validationErrors
  ,imgProfile, profile, getProfile, updateProfile,updateImgProfile } = useProfile()

// Asignamos mensajes de error en español

yum.setLocale(es);

const toast = useToast();

// Creamos variable para almacenar los errores
const errors = ref({});

// Variable de validación datos con yup
const schema = yum.object({
  id: yup.number().required(),
  thumbnail: yum.string().required()
})

// Obtener datos
onMounted(async()=> {
  try {
    // Obtengo los datos del usuario autenticado
    const response = await axios.get('/api/profile');
    // Modificamos el valor de la variable ref de profile.js
    imgProfile.value = response.data.data;
    console.log(imgProfile.value);
  } catch (error) {
    console.error("Error al guardar el usuario: ",error);
    toast.add({
      severity:'error',
      summary:'Error',
      detail:'Error al guardar el usuario',
      life: 3000
    });
  }
})

const onFormSubmit = () => {
  schema.validate(imgProfile.value)
      .then(() => {
        updateImgProfile(imgProfile.value)
      })
      .catch(error => {
        errors.value = {};  // Reiniciem els errors
        if (error.inner) {
          error.inner.forEach(err => {
            errors.value[err.path] = err.message;
          });
        } else {
          errors.value = { general: error.message };
        }

        toast.add({
          severity: 'error',
          summary: 'Error',
          detail: 'Si us plau, revisa els camps del formulari',
          life: 3000
        });
      });
}


import 'primeicons/primeicons.css';
import Rating from 'primevue/rating';
import ProductoNew from '../../../components/ProductoNew.vue';
import {useRoute} from "vue-router";
import {useToast} from "primevue/usetoast";
import * as yup from "yup";

const value = ref(4);
const productos = ref([]);
onMounted(()=>{
  obtenerProductos();
})

// llamada a api
const obtenerProductos = async () => {
  const respuesta = await axios.get('/api/get-posts'); // Asegúrate de que esta URL sea la correcta
  productos.value = respuesta.data.data; // Guardamos los datos en products
  console.log(respuesta);
};

</script>

<style scoped>
.container-info-profile{
  width: auto;
  max-height: 350px;
}
.container-info-profile fieldset{
  width: 100%;
  max-height: 350px;
  object-fit: contain;
  position: fixed;
  top: 200px;
}
.container-info-profile fieldset img{
  width: 50% !important;
  max-height: 350px;
  object-fit: contain;
}
.container-info-profile legend{
  height: 50px;
  width: 50px;
  border: solid 1px black;
  top: 10%;
  right: 25%;

}
.profile{
  width: 200px;
}
</style>