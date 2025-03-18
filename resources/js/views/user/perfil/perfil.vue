<template>
  <div class="show">

  </div>
  <main>
    <div class="">      
      <div class="d-flex flex-wrap gap-5 align-items-center justify-content-center py-5">
        <div v-if="user.email" class="container-info d-flex gap-5">
          <img v-if="user.media?.[0]" :src="user.media[0]['original_url']" :alt="user.media[0]['original_url']">
          <img v-else src="/images/GitHub.svg"  alt="default-image">

          <div class="container-info-profile d-flex flex-column gap-1">
            <h4 class="m-0">{{ user.name }} {{ user.surname1 }}</h4>
            <div class="d-flex gap-2 container-rating">
              <Rating v-model="mediaRating" readonly />
              <p>({{ reviews.length }})</p>
            </div>
            <p>Vendedor novel</p>
          </div>
        </div>

        <!-- Botones de acciones -->
        <div class="d-flex flex-column gap-3 w-auto h-100 container-extra-info">
          <div class="d-flex flex-column gap-3 mx-auto">
            <div class="d-flex gap-3">
              <Button @click="fetchProducts(`getPurchase`, user.id, 'purchases')" label="Compras" icon="pi pi-box" :badge="purchases.length" rounded />
              <Button @click="fetchProducts(`getSales`, user.id, 'sales')" label="Ventas" icon="pi pi-dollar" :badge="sales.length" rounded />
            </div>
            <Button :label="fullAddress?.results[0].formatted_address" icon="pi pi-map-marker" rounded />
          </div>
        </div>
        <div class="d-flex gap-3">
            <router-link class="" v-if="authStore().isAdmin" to="/admin">
              <Button label="Admin panel" rounded />
            </router-link>
            <Button label="Editar perfil" @click.stop="openEditProfile(user)" rounded />
            <Button label="Cerrar sesión" @click="logout" icon="pi pi-lock" rounded />
          </div>
      </div>
      <Dialog v-model:visible="visibleEditUser" modal :header="'Editando perfil'" style=" width: 350px; height: 500px;">
        <Tabs value="0">
          <TabList>
              <Tab appendTo=".show" value="0">Foto de Perfil 📸</Tab>
              <Tab appendTo=".show" value="1">Detalles del Perfil 📝</Tab>
          </TabList>
          <TabPanels>
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
                    <div class="flex flex-wrap justify-content-between align-items-center flex-1 gap-2">
                      <div class="flex gap-2">
                        <Button @click="chooseCallback()" icon="pi pi-images" rounded outlined></Button>
                        <Button @click="uploadEvent(uploadCallback, uploadedFiles)" icon="pi pi-cloud-upload" rounded outlined severity="success" :disabled="!files || files.length === 0"></Button>
                        <Button @click="clearCallback()" icon="pi pi-times" rounded outlined severity="danger" :disabled="!files || files.length === 0"></Button>
                      </div>
                      <p class="mt-4 mb-0">Drag and drop files to here to upload.</p>
                    </div>
                  </template>

                  <template #content="{ files, uploadedFiles, removeUploadedFileCallback, removeFileCallback }">
                    <img v-if=" files.length > 0" v-for="(file, index) of files" :key="file.name + file.type + file.size" role="presentation" :alt="file.name" :src="file.objectURL" class="object-fit-cover w-100 h-100 img-profile" />
                    <div v-else>
                      <img v-if="uploadedFiles.length > 0" :key="uploadedFiles[uploadedFiles.length-1].name + uploadedFiles[uploadedFiles.length-1].type + uploadedFiles[uploadedFiles.length-1].size" role="presentation" :alt="uploadedFiles[uploadedFiles.length-1].name" :src="uploadedFiles[uploadedFiles.length-1].objectURL" class="object-fit-cover w-100 h-100 img-profile" />
                    </div>
                  </template>

                  <template #empty>
                    <img v-if="user.avatar" :src=user.avatar alt="Avatar" class="object-fit-cover w-100 h-100 img-profile">
                    <img v-if="!user.avatar" src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Avatar Default" class="object-fit-cover w-100 h-100 img-profile">
                  </template>
                </FileUpload>
              </TabPanel>
              <TabPanel value="1">
                <form @submit.prevent="editUser" class="d-flex flex-column gap-5">
                  <div class="d-flex flex-column gap-5">
                    <div class="">
                      <FloatLabel>
                          <InputText appendTo=".show" v-model="userData.name" inputId="name-user" fluid id="name-user"/>
                          <label for="name-user">Nombre</label>
                      </FloatLabel>
                      <div class="text-danger mt-1">
                          <div v-for="message in validationErrors?.role_id">
                              {{ message }}
                          </div>
                      </div>
                    </div>
                    
                    <div class="d-flex gap-3 w-100">
                      <div class="">
                        <FloatLabel>
                            <InputText appendTo=".show" v-model="userData.surname1" inputId="surname1-user" fluid id="surname1-user"/>
                            <label for="surname1-user">Apellido 1</label>
                        </FloatLabel>
                        <div class="text-danger mt-1">
                          <div v-for="message in validationErrors?.role_id">
                              {{ message }}
                          </div>
                        </div>
                      </div>
                      <div class="">
                        <FloatLabel>
                            <InputText appendTo=".show" v-model="userData.surname2" inputId="surname2-user" fluid id="surname2-user"/>
                            <label for="surname2-user">Apellido 2</label>
                        </FloatLabel>
                        <div class="text-danger mt-1">
                          <div v-for="message in validationErrors?.role_id">
                              {{ message }}
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="">
                      <FloatLabel>
                          <InputText appendTo=".show" v-model="userData.email" inputId="email-user" fluid id="email-user"/>
                          <label for="email-user">Email</label>
                      </FloatLabel>
                      <div class="text-danger mt-1">
                          <div v-for="message in validationErrors?.role_id">
                              {{ message }}
                          </div>
                      </div>
                    </div>
                    <div class="">
                      <FloatLabel>
                          <Password appendTo=".show" v-model="userData.password" inputId="password-user" toggleMask fluid id="password-user"/>
                          <label for="password-user">Contraseña</label>
                      </FloatLabel>
                      <div class="text-danger mt-1">
                          <div v-for="message in validationErrors?.role_id">
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

      <div>
        <div class="d-flex gap-3 w-100 justify-content-center">
          <Button @click="fetchProducts(`getAllToSell`, user.id, 'activeProducts')" label="Mis Productos" icon="pi pi-shop" :badge="activeProducts.length" rounded />
          <Button @click="fetchProducts(`getValorations`, user.id, 'reviews')" label="Valoraciones" icon="pi pi-comment" :badge="reviews.length" rounded />
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
              <ProductoUser :productos="activeProducts" :actualizarProductos="fetchProducts" appendTo=".show" />
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
import { ref, onMounted, watch, reactive } from 'vue';
import axios from 'axios';
import useAuth from "@/composables/auth";
import { authStore } from "../../../store/auth";
import { Rating, Dialog, Password, Tabs, TabList, Tab, TabPanels, TabPanel } from 'primevue';
import ProductoUser from '../../../components/ProductoUser.vue';
import HistoricInfo from '../../../components/historicInfo.vue';
import ValorationInfo from '../../../components/valorationInfo.vue';
import { usePrimeVue } from 'primevue/config';

import useUsers from "@/composables/users";
const { updateUser, validationErrors } = useUsers();
import { useForm, useField, defineRule } from "vee-validate";
import { required, min } from "@/validation/rules";
defineRule('required', required);
defineRule('min', min);



const mediaRating = ref(0);
const activeProducts = ref([]);
const visibleEditUser = ref(false);
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
// variables
const { value: name } = useField('name', null, { initialValue: selectedUser.name });
const { value: surname1 } = useField('surname1', null, { initialValue: selectedUser.surname1 });
const { value: surname2 } = useField('surname2', null, { initialValue: selectedUser.surname2 });
const { value: email } = useField('email', null, { initialValue: selectedUser.email });
const { value: password } = useField('password', null, { initialValue: '' });
const schema = {
  name: 'required|min:3',
  surname1: 'required|min:3',
  surname2: 'required|min:3',
  email: 'required',
  password: 'required|min:8',
}
const { validate, errors } = useForm({ validationSchema: schema })


const userData = reactive({
  name,
  surname1,
  surname2,
  email,
  password,
})




onMounted(async () => {
  await getDataProfile();
  // ProductService.getProductsSmall().then((data) => (products.value = data));

});

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
  event.formData.append('id', user.id)
};

const uploadEvent = async (callback, uploadedFiles) => {
  console.log('uploadEvent');
  totalSizePercent.value = totalSize.value / 10;
  await callback();
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
  userData.name = user.name;
  userData.surname1 = user.surname1;
  userData.surname2 = user.surname2;
  userData.email = user.email;
  visibleEditUser.value = true; // abre el Dialog
  console.log('🔎 SELECTED USER -->', selectedUser);
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
        validate().then(form => { if (form.valid) updateUser(userData.value) })
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
</style>