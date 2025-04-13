<template>
  <div class="show">

  </div>
  <main>
    <div class="fondo-perfil">
      <div class="d-flex flex-wrap gap-5 align-items-center justify-content-center 8 header-perfil">
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
            <div class="d-flex gap-2 container-rating py-2">
              <Rating v-model="mediaRating" readonly />
            </div>
            <Tag icon="pi pi-map-marker" severity="secondary" :value="fullAddress?.results && fullAddress.results.length > 0 ? fullAddress.results[0].formatted_address : 'Ubicación no disponible'" rounded></Tag>
          </div>
        </div>

        <!-- Botones de acciones -->
        <div class="d-flex flex-column gap-3 w-auto h-100 container-extra-info align-items-center justify-content-center">
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
                      <div class="map-container">
                        <div id="map" class="google-map"></div>
                        <div class="map-controls">
                          <FloatLabel>
                            <InputText v-model="partialAddress" id="address-input" @keyup.enter="buscarUbicacion"/>
                            <label for="address-input">Introduce una dirección</label>
                          </FloatLabel>
                          <Button @click="buscarUbicacion" label="Buscar" class="mt-2" />
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

      <div>
        <div class="d-flex gap-3 w-100 justify-content-center flex-wrap">
          <div class="d-flex gap-3">
            <Button @click="seleccionarTab('activeProducts')" label="Mis Productos" class="" icon="pi pi-shop" :badge="activeProducts.length" rounded />
            <Button @click="seleccionarTab('purchases')" label="Compras" icon="pi pi-box" :badge="purchases.length" rounded />
          </div>
          <div class="d-flex gap-3">
            <Button @click="seleccionarTab('sales')" label="Ventas" icon="pi pi-dollar" :badge="sales.length" rounded />
            <Button @click="seleccionarTab('reviews')" label="Valoraciones" icon="pi pi-comment" :badge="reviews.length" rounded />
          </div>
        </div>

        <div class="container w-100 d-flex gap-5">
          <!-- <div v-if="loading">Cargando...</div> -->
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
          <div v-if="selectedTab === 'activeProducts'" class="w-100">
            <div v-if="activeProducts.length > 0" class="py-4">
              <h3 class="text-center my-6 "><b><u>Mis Productos</u></b></h3>
              <ProductoUser :productos="activeProducts" :actualizarProductos="fetchProducts" />
            </div>
            <div v-else class="container-else my-5">
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
import useAuth from "@/composables/auth";
import { authStore } from "../../../store/auth";
import { Rating, Dialog, Password, Tabs, TabList, Tab, TabPanels, TabPanel, Tag } from 'primevue';
import ProductoUser from '../../../components/ProductoUser.vue';
import HistoricInfo from '@/components/HistoricInfo.vue';
import ValorationInfo from '@/components/ValorationInfo.vue';
import { usePrimeVue } from 'primevue/config';
import Skeleton from 'primevue/skeleton';
import usePerfil from "@/composables/perfil";
import useMaps from "@/composables/Maps";

// Importar hooks y utilidades
import useUsers from "@/composables/users";
import { useForm, useField, defineRule } from "vee-validate";
import { required, min } from "@/validation/rules";

// Definir reglas de validación
defineRule('required', required);
defineRule('min', min);

// Composables
const { updateUser, validationErrors, isLoading } = useUsers();
const { logout } = useAuth();
const { 
  user, 
  activeProducts, 
  purchases,
  sales,
  reviews,
  loading, 
  error,
  mediaRating,
  fullAddress,
  getDataProfile,
  fetchProducts,
  calcularMediaRating,
  getGeoLocation
} = usePerfil();

const { 
  latitude, 
  longitude, 
  partialAddress,
  initMap, 
  getGeoPartialAddress
} = useMaps();

// Variables locales
const selectedUser = ref(null);
const selectedTab = ref('activeProducts'); // Establecer un tab por defecto
const visibleEditUser = ref(false);

// Variables para imagen
const totalSize = ref(0);
const totalSizePercent = ref(0);
const files = ref([]);
const $primevue = usePrimeVue();

// Variables para el formulario de edición
const { value: id } = useField('id', null, { initialValue: '' });
const { value: name } = useField('name', null, { initialValue: '' });
const { value: surname1 } = useField('surname1', null, { initialValue: '' });
const { value: surname2 } = useField('surname2', null, { initialValue: '' });
const { value: email } = useField('email', null, { initialValue: '' });
const { value: password } = useField('password', null, { initialValue: '' });

const schema = {};
const { validate, errors } = useForm({ validationSchema: schema });

const userData = ref({
  id: '',
  name: '',
  surname1: '',
  surname2: '',
  email: '',
  password: '',
  latitude: null,
  longitude: null
});

// Métodos para el componente
const startMap = () => {
  setTimeout(() => {
    // Utiliza las coordenadas del usuario para inicializar el mapa
    if (user.value?.latitude && user.value?.longitude) {
      latitude.value = parseFloat(user.value.latitude);
      longitude.value = parseFloat(user.value.longitude);
    }
    
    initMap("map", latitude.value, longitude.value);
    
    // Si hay una dirección formateada disponible, mostrarla en el campo
    if (fullAddress.value?.results && fullAddress.value.results.length > 0) {
      partialAddress.value = fullAddress.value.results[0].formatted_address;
    }
  }, 500);
};

const buscarUbicacion = async () => {
  await getGeoPartialAddress();
};

const seleccionarTab = (tab) => {
  selectedTab.value = tab;
  
  // Si ya tenemos el ID del usuario, refrescamos los datos del tab seleccionado
  if (user.value && user.value.id) {
    if (tab === 'purchases') fetchProducts('getPurchase', user.value.id, 'purchases');
    else if (tab === 'sales') fetchProducts('getSales', user.value.id, 'sales');
    else if (tab === 'activeProducts') fetchProducts('getAllToSell', user.value.id, 'activeProducts');
    else if (tab === 'reviews') fetchProducts('getValorations', user.value.id, 'reviews');
  }
};

// Funciones para subida de imagen
const onBeforeUpload = (event) => {
  event.formData.append('id', user.value.id);
};

const uploadEvent = async (callback, uploadedFiles) => {
  totalSizePercent.value = totalSize.value / 10;
  await callback();
  visibleEditUser.value = false;
  window.location.reload();
};

const onSelectedFiles = (event) => {
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

const openEditProfile = async (userProfile) => {
  selectedUser.value = userProfile;
  userData.value.id = userProfile.id;
  userData.value.name = userProfile.name;
  userData.value.surname1 = userProfile.surname1;
  userData.value.surname2 = userProfile.surname2;
  userData.value.email = userProfile.email;
  userData.value.password = '';
  visibleEditUser.value = true;
  
  // Actualizar la posición del mapa basado en la ubicación del usuario
  if (userProfile.latitude && userProfile.longitude) {
    latitude.value = Number(userProfile.latitude);
    longitude.value = Number(userProfile.longitude);
  }
  
  // Inicializar el mapa después de que esté visible el diálogo
  setTimeout(() => {
    startMap();
  }, 500);
};

function editUser() {
  // Actualizar las coordenadas en el formulario antes de enviar
  userData.value.latitude = latitude.value;
  userData.value.longitude = longitude.value;
  
  validate().then(form => { 
    if (form.valid) {
      updateUser(userData.value);
      visibleEditUser.value = false;
    }
  });
};

// Hooks del ciclo de vida
onMounted(async () => {
  await getDataProfile();
  selectedTab.value = 'activeProducts'; // Establecer un tab por defecto
});

// Watchers
watch(user, (newUser) => {
  if (newUser && newUser.id) {
    fetchProducts('getPurchase', newUser.id, 'purchases');
    fetchProducts('getSales', newUser.id, 'sales');
    fetchProducts('getValorations', newUser.id, 'reviews');
    fetchProducts('getAllToSell', newUser.id, 'activeProducts');
    
    if (newUser.latitude && newUser.longitude) {
      getGeoLocation(newUser.latitude, newUser.longitude)
        .then(() => {
          // Cuando tengamos la dirección, podemos actualizar el campo de búsqueda
          if (fullAddress.value?.results && fullAddress.value.results.length > 0 && visibleEditUser.value) {
            partialAddress.value = fullAddress.value.results[0].formatted_address;
          }
        });
    }
  }
}, { deep: true });

// Observar cambios en las coordenadas del mapa
watch([latitude, longitude], ([newLat, newLng]) => {
  userData.value.latitude = newLat;
  userData.value.longitude = newLng;
});

// Observar cambios en la dirección formateada
watch(() => fullAddress.value, (newAddress) => {
  if (newAddress?.results && newAddress.results.length > 0 && visibleEditUser.value) {
    partialAddress.value = newAddress.results[0].formatted_address;
  }
}, { deep: true });
</script>

<!-- Importar el archivo CSS externo -->
<style src="./perfil.css" scoped></style>