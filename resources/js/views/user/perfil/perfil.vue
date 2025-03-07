<template>
  <main>
    <div class="">
      <!-- imagen de perfil -->
      <div class="">
        <div class="user text-center">
          <div class="profile">
            <img :src="imgProfile.avatar" alt="">
            <Avatar :image="authStore().user?.avatar" class="mr-3" shape="circle" />
            <FileUpload
              name="image"
              url="/api/profile/updateimg"
              @before-upload="onBeforeUpload"
              @upload="onTemplateUpload($event)"
              accept="image/webp"
              :maxFileSize="1500000"
              @select="onSelectedFiles"

            >
              <!-- Botones de control -->
              <template #header="{ chooseCallback, uploadCallback, clearCallback, files }">
                <div class="flex gap-2">
                  <Button @click="chooseCallback()"
                          class="secondary-button-2"
                          label="Seleccionar"
                          rounded
                          outlined />
                  <Button @click="uploadCallback()"
                          class="secondary-button-2"
                          label="Actualizar"
                          rounded
                          outlined
                          severity="success"
                          :disabled="!files || files.length === 0" />
                  <Button @click="clearCallback()"
                          class="secondary-button-2"
                          label="Eliminar"
                          rounded
                          outlined
                          severity="danger"
                          :disabled="!files || files.length === 0" />
                </div>
              </template>

              <template #content="{ files, uploadedFiles, removeUploadedFileCallback, removeFileCallback }">
                <img v-if=" files.length > 0" v-for="(file, index) of files" :key="file.name + file.type + file.size" role="presentation" :alt="file.name" :src="file.objectURL" class="object-fit-cover w-100 h-100 img-profile" />
                <div v-else>
                  <img v-if="uploadedFiles.length > 0" :key="uploadedFiles[uploadedFiles.length-1].name + uploadedFiles[uploadedFiles.length-1].type + uploadedFiles[uploadedFiles.length-1].size" role="presentation" :alt="uploadedFiles[uploadedFiles.length-1].name" :src="uploadedFiles[uploadedFiles.length-1].objectURL" class="object-fit-cover w-100 h-100 img-profile" />
                </div>
              </template>



            </FileUpload>
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


const toast = useToast();
const files = ref([]);

const onBeforeUpload = (event) => {
  event.formData.append('id', user.id)
}

const onSelectedFiles = (event) => {

}
const onTemplateUpload = (event) => {

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