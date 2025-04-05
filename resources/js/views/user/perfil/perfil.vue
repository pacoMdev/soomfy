<template>
  <div class="show">

  </div>
  <main>
    <div class="">
      <div class="d-flex flex-wrap gap-5 align-items-center justify-content-center py-5">
        <div v-if="user.email" class="container-info d-flex gap-5">
          <div v-if="isLoading">
            <Skeleton shape="circle" size="5rem"></Skeleton>
          </div>
          <div v-else>
            <img v-if="user.media?.[0]" :src="user.media[0]['original_url']" :alt="user.media[0]['original_url']">
            <Skeleton v-else shape="circle" size="5rem"></Skeleton>
          </div>

          <div class="container-info-profile d-flex flex-column gap-1">
            <h4 class="m-0">{{ user.name }} {{ user.surname1 }}</h4>
            <div class="d-flex gap-2 container-rating">
              <Rating v-model="mediaRating" readonly />
              <p>({{ reviews.length }})</p>
            </div>
            <Tag icon="pi pi-map-marker" severity="secondary" :value="fullAddress?.results && fullAddress.results.length > 0 ? fullAddress.results[0].formatted_address
    : 'Direccion no disponible 🚫'" rounded></Tag>

          </div>
        </div>

        <!-- Botones de acciones -->
        <div class="d-flex flex-column gap-3 w-auto h-100 container-extra-info">
          <div class="d-flex flex-column gap-3 mx-auto">
            <router-link class="" v-if="authStore().isAdmin" to="/admin">
              <Button label="Admin panel" class="w-100" rounded />
            </router-link>
            <Button label="Editar perfil" @click.stop="openEditProfile(user)" class="w-100" rounded />
            <Button label="Cerrar sesión" @click="logout" icon="pi pi-lock" severity="danger" variant="outlined" class="w-100 p-button closeSession" rounded />
          </div>
        </div>
      </div>
      <Dialog v-model:visible="visibleEditUser" modal :header="'Editando perfil'" style=" width: min(90vw, 500px); height: min(90vh, 550px); " appendTo=".show" >
        <Tabs value="0">
          <TabList>
              <Tab appendTo=".show" value="0">Foto de Perfil 📸</Tab>
              <Tab appendTo=".show" value="1">Detalles del Perfil 📝</Tab>
          </TabList>
          <TabPanels class="w-100">
              <TabPanel value="0">
                <FileUpload
                    name="picture"
                    url="/api/users/updateimg"
                    @before-upload="onBeforeUpload"
                    @upload="onTemplatedUpload($event)"
                    accept="image/*"
                    :maxFileSize="1500000"
                    @select="onSelectedFiles"
                    pt:content:class="fu-content"
                    pt:buttonbar:class="fu-header"
                    pt:root:class="fu"
                    class="fu"
                >
                  <template #header="{ chooseCallback, uploadCallback, clearCallback, files, uploadedFiles }">
                    <div class="flex flex-wrap justify-content-center align-items-center flex-1 gap-2 w-50 mx-auto">
                      <div class="flex gap-2">
                        <Button @click="chooseCallback()" icon="pi pi-images" rounded outlined></Button>
                        <Button @click="uploadEvent(uploadCallback, uploadedFiles)" icon="pi pi-cloud-upload" rounded outlined severity="success" :disabled="!files || files.length === 0"></Button>
                        <Button @click="clearCallback()" icon="pi pi-times" rounded outlined severity="danger" :disabled="!files || files.length === 0"></Button>
                      </div>
                    </div>
                  </template>

                  <template #content="{ files, uploadedFiles, removeUploadedFileCallback, removeFileCallback }">
                    <img v-if=" files.length > 0" v-for="(file, index) of files" :key="file.name + file.type + file.size" role="presentation" :alt="file.name" :src="file.objectURL" class="object-fit-cover w-50 h-50 img-profile" />
                    <div v-else>
                      <img v-if="uploadedFiles.length > 0" :key="uploadedFiles[uploadedFiles.length-1].name + uploadedFiles[uploadedFiles.length-1].type + uploadedFiles[uploadedFiles.length-1].size" role="presentation" :alt="uploadedFiles[uploadedFiles.length-1].name" :src="uploadedFiles[uploadedFiles.length-1].objectURL" class="object-fit-cover w-50 h-50 img-profile" />
                    </div>
                  </template>

                  <template #empty>
                    <img v-if="user.avatar" :src=user.avatar alt="Avatar" class="object-fit-cover w-50 h-50 img-profile">
                    <img v-if="!user.avatar" src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Avatar Default" class="object-fit-cover w-50 h-50 img-profile">
                  </template>
                </FileUpload>
              </TabPanel>
              <TabPanel value="1">

                <form @submit.prevent="editUser" class="d-flex flex-column gap-5 py-4">
                  <div class="d-flex flex-column gap-5">
                    <div class="">
                      <FloatLabel>
                          <InputText appendTo=".show" v-model="userData.name" inputId="name-user" fluid id="name"/>
                          <label for="name-user">Nombre</label>
                      </FloatLabel>
                      <div class="text-danger mt-1">{{ errors.name }}</div>
                      <div class="text-danger mt-1">
                          <div v-for="message in validationErrors?.name">
                              {{ message }}
                          </div>
                      </div>
                    </div>

                    <div class="d-flex gap-3 w-100">
                      <div class="">
                        <FloatLabel>
                            <InputText appendTo=".show" v-model="userData.surname1" inputId="surname1-user" fluid id="surname1"/>
                            <label for="surname1-user">Apellido 1</label>
                        </FloatLabel>
                        <div class="text-danger mt-1">{{ errors.surname1 }}</div>
                        <div class="text-danger mt-1">
                          <div v-for="message in validationErrors?.surname1">
                              {{ message }}
                          </div>
                        </div>
                      </div>
                      <div class="">
                        <FloatLabel>
                            <InputText appendTo=".show" v-model="userData.surname2" inputId="surname2-user" fluid id="surname2"/>
                            <label for="surname2-user">Apellido 2</label>
                        </FloatLabel>
                        <div class="text-danger mt-1">{{ errors.surname2 }}</div>
                        <div class="text-danger mt-1">
                          <div v-for="message in validationErrors?.surname2">
                              {{ message }}
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="">
                      <FloatLabel>
                          <InputText appendTo=".show" v-model="userData.email" inputId="email-user" fluid id="email" disabled=""/>
                          <label for="email-user">Email</label>
                      </FloatLabel>
                      <div class="text-danger mt-1">{{ errors.email }}</div>
                      <div class="text-danger mt-1">
                          <div v-for="message in validationErrors?.email">
                              {{ message }}
                          </div>
                      </div>
                    </div>
                    <div class="">
                      <FloatLabel>
                          <Password appendTo=".show" v-model="userData.password" inputId="password-user" toggleMask fluid id="password"/>
                          <label for="password-user">Contraseña</label>
                      </FloatLabel>
                      <div class="text-danger mt-1">{{ errors.password }}</div>
                      <div class="text-danger mt-1">
                          <div v-for="message in validationErrors?.password">
                              {{ message }}
                          </div>
                      </div>
                    </div>
                    <div class="">
                      <FloatLabel>
                        <Button placeholder="Buscar" label="iniciar mapa" class="btn btn-primary" @click="startMap"/>
                        <div class="my-3">
                          <FloatLabel>
                            <InputText v-model="partialAddress" id="address-input" @keyup.enter="buscarUbicacio"/>
                            <label for="address-input">Intoduce una direccion</label>
                          </FloatLabel>
                          <Button @click="getGeoPartialAddress" label="Buscar" class="mt-2" />
                        </div>
                        <div id="map" class="google-map"></div>
                      </FloatLabel>
                    </div>

                  </div>
                  <Button type="submit" label="Actualizar" class="w-100" appendTo=".show" outlined severity="secondary" autofocus />
                </form>
              </TabPanel>
          </TabPanels>
        </Tabs>
      </Dialog>

      <div>
        <div class="d-flex gap-3 w-100 justify-content-center flex-wrap">
          <div class="d-flex gap-3">
            <Button @click="fetchProducts(`getAllToSell`, user.id, 'activeProducts')" label="Mis Productos" class="" icon="pi pi-shop" :badge="activeProducts.length" rounded />
            <Button @click="fetchProducts(`getPurchase`, user.id, 'purchases')" label="Compras" icon="pi pi-box" :badge="purchases.length" rounded />
          </div>
          <div class="d-flex gap-3">
            <Button @click="fetchProducts(`getSales`, user.id, 'sales')" label="Ventas" icon="pi pi-dollar" :badge="sales.length" rounded />
            <Button @click="fetchProducts(`getValorations`, user.id, 'reviews')" label="Valoraciones" icon="pi pi-comment" :badge="reviews.length" rounded />
          </div>
        </div>

        <div class="container w-100 d-flex gap-5">
          <div v-if="loading">Cargando...</div>
          <div v-if="error" style="color: red;">{{ error }}</div>

           <!-- COMPRAS -------------------------------------------------------------------------------------------- -->
          <div v-if="selectedTab === 'purchases'" class="w-100">
            <div v-if="purchases.length > 0">
              <h4>Historial de Compras</h4>
              <HistoricInfo :historic="purchases" appendTo=".show"/>
            </div>
            <div v-else class="container-else">
              <h1>Parece que no hay compras</h1>
              <img src="/images/undraw_file-search_cbur.svg" alt="Imagen compras" class="image-else">
            </div>
          </div>
          <!-- VENTAS -------------------------------------------------------------------------------------------- -->
          <div v-if="selectedTab === 'sales'" class="w-100">
            <div v-if="sales.length > 0">
              <h4>Historial de Ventas</h4>
              <HistoricInfo :historic="sales" />
            </div>
            <div v-else class="container-else">
              <h1>Parece que no hay ventas</h1>
              <img src="/images/undraw_file-search_cbur.svg" alt="Imagen ventas" class="image-else">
            </div>
          </div>
          <!-- PRODUCTOS -------------------------------------------------------------------------------------------- -->
          <div v-if="selectedTab === 'activeProducts'">
            <div v-if="activeProducts.length > 0">
              <h4>Mis Productos</h4>
              <ProductoUser :productos="activeProducts" :actualizarProductos="fetchProducts" />
            </div>
            <div v-else class="container-else">
              <h1>Parece que aun no hay productos</h1>
              <img src="/images/undraw_file-search_cbur.svg" alt="Imagen productos" class="image-else">
            </div>
          </div>
          <!-- OPINIONES -------------------------------------------------------------------------------------------- -->
          <div v-if="selectedTab === 'reviews'" class="w-100">
            <div v-if="reviews.length > 0">
              <h4>Valoraciones</h4>
              <ValorationInfo :reviews="reviews" appendTo=".show"/>
            </div>
            <div v-else class="container-else">
              <h1>Parece que no hay valoraciones</h1>
              <img src="/images/undraw_public-discussion_693m.svg" alt="Imagen valoracion" class="image-else">
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>


<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import useAuth from "@/composables/auth";
import { authStore } from "../../../store/auth";
import { Rating, Dialog, Password, Tabs, TabList, Tab, TabPanels, TabPanel, Tag } from 'primevue';
import ProductoUser from '../../../components/ProductoUser.vue';
import HistoricInfo from '@/components/historicInfo.vue';
import ValorationInfo from '@/components/valorationInfo.vue';
import { usePrimeVue } from 'primevue/config';
import Skeleton from 'primevue/skeleton';

import useUsers from "@/composables/users";
const { updateUser, validationErrorsm, isLoading } = useUsers();
import { useForm, useField, defineRule } from "vee-validate";
import { required, min } from "@/validation/rules";
import {map} from "lodash";
defineRule('required', required);
defineRule('min', min);
const address = ref('');

const mediaRating = ref(0);
const activeProducts = ref([]);
const selectedUser = ref(null);
const purchases = ref([]);
const sales = ref([]);
const reviews = ref([]);
const loading = ref(false);
const error = ref(null);
const { logout } = useAuth();
const user = ref({});
const selectedTab = ref(null);
//variables imagen
const totalSize = ref(0);
const totalSizePercent = ref(0);
const files = ref([]);
const $primevue = usePrimeVue();
const fullAddress = ref(null);
const partialAddress = ref('');
// DIALOGS
const visibleEditUser = ref(false);
// variables para el edit user
const { value: id } = useField('id', null, { initialValue: selectedUser.id });
const { value: name } = useField('name', null, { initialValue: selectedUser.name });
const { value: surname1 } = useField('surname1', null, { initialValue: selectedUser.surname1 });
const { value: surname2 } = useField('surname2', null, { initialValue: selectedUser.surname2 });
const { value: email } = useField('email', null, { initialValue: selectedUser.email });
const { value: password } = useField('password', null, { initialValue: '' });
// const { value: latitude } = useField('latitude', null, { initialValue: '41.38740000' });
// const { value: longitude } = useField('longitude', null, { initialValue: '2.16860000' });
const latitude = ref(40.4165);
const longitude = ref(-3.70256);
const schema = {
}
const { validate, errors } = useForm({ validationSchema: schema })

const userData = ref({
  id,
  name,
  surname1,
  surname2,
  email,
  password,
  latitude,
  longitude
})

const getGeoPartialAddress = async ()=>{
  // realizar consulta a api GOOGLE
  // Recojer las coordenadas y modificar las default (latitude, longitude)
  console.log('✅ CHEKING ADDRESS');
  console.log('🏠 ADDRESS -->', partialAddress.value);
  console.log('📟 LATITUDE && longitude', latitude, longitude);

  try {
      const response = await axios.get('/api/geocode', {
          params: { address: partialAddress.value,}
      });
      const components = response.data;
      console.log('API -->', components.results[0]);

      if (components) {
        latitude.value = components.results[0].geometry.location.lat;
      longitude.value = components.results[0].geometry.location.lng;

      console.log('🏞️ API Response', components.results);
      console.log('📟 LATITUDE && longitude', latitude, longitude);
      }
  } catch (error) {
      console.error("Eror en api de GOOGLE -->", error);
  }
}

const getGeocodeData = async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/geocode', {
      params: {
        address: 'Barcelona'
      }
    });

    console.log(response.data);
  } catch (error) {
    console.error('Error durant la crida:', error);
  }
};

const startMap = async () => {
  const map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: latitude.value, lng: longitude.value },
    zoom: 13,
  });

  // Crear un marcador en el mapa
  const marker = new google.maps.Marker({
    // Coordenadas iniciales donde se colocara el marcador
    position: { lat: latitude.value, lng: longitude.value },
    map, // Indica que mapa
    draggable: true, // Permitimos que se pueda arrastrar por e l mapa
  });

  // Evento: al arrastrar el marcador
  marker.addListener("dragend", (event) => {
    const { lat, lng } = event.latLng.toJSON();
    latitude.value = lat;
    longitude.value = lng;
    console.log('🆕 POSITION');
    console.log('📟 latitude -->', latitude.value);
    console.log('📟 longitude -->', longitude.value);
  });

  // Evento: clic en el mapa
  map.addListener("click", (event) => {
    const { lat, lng } = event.latLng.toJSON();
    latitude.value = lat;
    longitude.value = lng;
    marker.setPosition({ lat, lng });
    console.log("Mapa clickeado en:", latitude.value, longitude.value);
  });
}


const buscarUbicacio = async () => {
  try {
    const response = await axios.get('/geocode', { params: { address: address.value } })
    const resultats = response.data.results

    if(resultats.length > 0) {
      const {lat, lng} = resultats[0].geometry.location
      actualitzarMapa(lat, lng)
    } else {
      alert("No s'han trobat resultats.")
    }

  } catch (error) {
    console.error(error)
    alert("Error en obtenir ubicació.")
  }
}




onMounted(async () => {
  await getGeocodeData();
  await getDataProfile();
  map.value = new google.maps.Map(document.getElementById("mapa"), {
    center: { lat: latitude, lng: longitude },
    zoom: 12,
  })

  marker.value = new google.maps.Marker({
    position: { lat: latitude, lng: longitude },
    map: map.value,
  })

  // ProductService.getProductsSmall().then((data) => (products.value = data));

});

const actualitzarMapa = (lat, lng) => {
  const position = {lat, lng}
  map.value.setCenter(position)
  marker.value.setPosition(position)
}


// ejecuta fetchProducts cuando userData este disponible
watch(user, (newUser) => {
  if (newUser.id) {
    fetchProducts(`getPurchase`, newUser.id, 'purchases');
    fetchProducts(`getSales`, newUser.id, 'sales');
    fetchProducts(`getValorations`, newUser.id, 'reviews');
    fetchProducts(`getAllToSell`, newUser.id, 'activeProducts');
    getGeoLocation();
    // getMediaRating(reviews);
  }
});

// Funciones de subida imagen
const onBeforeUpload = (event) => {
  // console.log('onBeforeUpload')
  event.formData.append('id', user.value.id)
};

const uploadEvent = async (callback, uploadedFiles) => {
  console.log('uploadEvent');
  totalSizePercent.value = totalSize.value / 10;
  console.log(totalSizePercent.value);
  await callback();
  visibleEditUser.value = false;
  window.location.reload();


  // if (uploadedFiles.length > 1) {
  //     uploadedFiles = uploadedFiles.splice(0, uploadedFiles.length - 1);
  // }
};

const onSelectedFiles = (event) => {
  console.log('onSelectedFiles');
  files.value = event.files;

  if (event.files.length > 1) {
    event.files = event.files.splice(0, event.files.length - 1);
  }

  files.value.forEach((file) => {
    totalSize.value += parseInt(formatSize(file.size));
  });
};
const formatSize = (bytes) => {
  const k = 1024;
  const dm = 3;
  const sizes = $primevue.config.locale.fileSizeTypes;

  if (bytes === 0) {
    return `0 ${sizes[0]}`;
  }

  const i = Math.floor(Math.log(bytes) / Math.log(k));
  const formattedSize = parseFloat((bytes / Math.pow(k, i)).toFixed(dm));

  return `${formattedSize} ${sizes[i]}`;
};

const getGeoLocation = async () => {
    try{
        const latitude = user.value.latitude;
        const longitude = user.value.longitude;

        const respuesta = await axios.get('/api/geoLocation', {
            params: {latitude, longitude}
        });
        fullAddress.value = respuesta.data || {};
        console.log('FULLADDRESS -->', fullAddress);

    }catch(err){
        console.log('Falla en API: ', err);
    }
};




const openEditProfile = async (user) => {
  selectedUser.value = user;
  userData.value.id = user.id;
  userData.value.name = user.name;
  userData.value.surname1 = user.surname1;
  userData.value.surname2 = user.surname2;
  userData.value.email = user.email;
  visibleEditUser.value = true; // abre el Dialog 
  console.log('🔎 USER ID SELECTED', userData.id)
  console.log('🔎 SELECTED USER -->', selectedUser);
  console.log('------------------------------');
  latitude.value = Number(user.latitude);
  longitude.value = Number(user.longitude);
  console.log('📟 latitude -->', latitude.value);
  console.log('📟 longitude -->', longitude.value);
};

const fetchProducts = async (endpoint, id, type) => {
  console.log('ID ---------->', id)
  loading.value = true;
  error.value = null;
  selectedTab.value = type; // guardamos que se esta viendo

  try {
    const response = await axios.post(`/api/${endpoint}`, {
      userId: id,
    });

    // guarda info segun seccion
    if (type === 'purchases') purchases.value = response.data.data || [];
    else if (type === 'sales') sales.value = response.data.data || [];
    else if (type === 'activeProducts') activeProducts.value = response.data.data || [];
    else if (type === 'reviews') {reviews.value = response.data.data || []; calcularMediaRating();};
    // console.log('purchase -->', purchases);
    // console.log('sales -->', sales);
    // console.log('activeProducts -->', activeProducts);
    // console.log('reviews -->', reviews);

  } catch (err) {
    error.value = "Error al obtener los datos.";
  } finally {
    loading.value = false;
  }
};

const getDataProfile = async () => {
  try {
    const respuesta = await axios.get('/api/user');
    user.value = respuesta.data.data || {};
  } catch (error) {
    console.error("Error al obtener usuario:", error);
  }
};
const calcularMediaRating = () => {
  if (reviews.value.length === 0) {
    mediaRating.value = 0;
    return;
  }
  const total = reviews.value.reduce((sum, review) => sum + review.calification, 0);
  mediaRating.value = total / reviews.value.length;
};

function editUser() {
  console.log('FORMULARIO USER -->', userData);
  validate().then(form => { if (form.valid) updateUser(userData.value) });
  visibleEditUser.value = false; // close el Dialog
  // window.location.reload();

}

</script>


<style scoped>
.container-info img {
  height: 100px;
  width: 100px;
  border-radius: 100px;
  background-color: var(--primary-color);
}
.container-else {
    width: 100%;
    text-align: center;
  }
  .image-else {
    width: auto;
    max-height: 200px;
  }

.google-map {
  height: 300px; /* Define la altura del mapa */
  width: 100%; /* Define el ancho del mapa */
  border: 1px solid #ccc; /* Opcional: bordes del mapa */
  border-radius: 8px; /* Opcional: bordes redondeados */
}
</style>