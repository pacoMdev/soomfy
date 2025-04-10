<template>
    <div v-if="valorationCheck.check == false" class="d-flex flex-wrap justify-content-center gap-6 w-100  p-6">
        <div class="shadow rounded p-6 gap-5 m-0 container-opinion">
            <h4>Valora tu experiencia con {{ userProduct.user?.[0].name }}</h4>
            <p>Cuantas mas estrellas</p>
            <form @submit.prevent="submitOpinionForm">
                <div class="d-flex flex-column gap-5">
                    <Rating v-model="rating" class="custom-rating mx-auto" />
                    <small v-if="errors.rating" class="text-red-500">{{ errors.rating }}</small>          
                    <FloatLabel>
                        <InputText v-model="title" inputId="title" fluid id="title" />
                        <label for="title">Titulo</label>
                    </FloatLabel>
                    <small v-if="errors.rating" class="text-red-500">{{ errors.title }}</small>
                    <FloatLabel>
                        <Textarea v-model="description" inputId="description" fluid id="description" rows="5" cols="50" />
                        <label for="description">Descripcion</label>
                    </FloatLabel>
                    <small v-if="errors.rating" class="text-red">{{ errors.description }}</small>
                </div>

                <Button label="Publicar" type="submit" class="w-full" />
            </form>
        </div>
        <div v-if="userProduct">
            <Producto :productos="userProduct" />
        </div>
        <div v-else class="shadow rounded p-6 gap-5 m-0 container-opinion">
            {{ userProduct }}
            <h1>nada</h1>
        </div>
    </div>
    <div v-else>
        <h1>Valoracion ya realizada</h1>
    </div>
</template>
<script setup>
import { Rating, Textarea, Button } from 'primevue';
import InputText from 'primevue/inputtext';
import { useRoute } from 'vue-router';
import { onMounted, ref } from 'vue';
import Producto from '../../../components/Producto.vue';



const route = useRoute();
const userIdSeller = route.query.userIdS;
const userIdBuyer = route.query.userIdB;
const productId = route.query.productId;
const token = route.query.token;
console.log('🔎 UserID seller -->', userIdSeller);
console.log('🔎 UserID BUYER -->', userIdBuyer);
console.log('🔎 productId -->', productId);
console.log('🔎 Token -->', token);

const userProduct = ref({});
const valorationCheck=ref([]);

const rating = ref(null);
const title = ref('');
const description = ref('');
const errors = ref({});

onMounted (() => {
    getUserProduct();
    checkReview();
});


const getUserProduct = async () => {
  try {
    const respuesta = await axios.get('/api/products/'+productId);
    userProduct.value[0] = respuesta.data.data || {};
    console.log('UserProduct -->', userProduct);
  } catch (error) {
    console.error("Error al valorar -->", error);
  }
};
const checkReview = async () => {
  try {
    const respuesta = await axios.get('/api/checkReview', {
        params: {token}
    });
    valorationCheck.value = respuesta.data || {};
    console.log('Valoration check -->', valorationCheck.value);
  } catch (error) {
    console.error("Error al checkear valoration -->", error);
  }
};
const submitOpinionForm = async () => {
    errors.value = {}; // Reset errors

    if (!rating.value) errors.value.rating = "La valoracion es obligatoria";
    if (!title.value.trim()) errors.value.title = "El titulo es obligatorio";
    if (!description.value.trim()) errors.value.description = "La descripcion es obligatoria";

    if (Object.keys(errors.value).length === 0) {
        // Enviar formulario si no hay errores
        console.log("Formulario enviado:", { rating: rating.value, title: title.value, description: description.value });
        try {
            const respuesta = await axios.post('/api/valorate', {
                title: title.value,
                description: description.value,
                rating: rating.value,
                userSeller: userIdSeller,
                userBuyer: userIdBuyer,
                productId: productId,
                token: token,
            })
            .then( async response =>{
                console.log('Opinion realizada', response);
                console.log('👌 Status:', response.status);
                if(response.status == 200){    // compra realizada con exito
                    swal({
                        icon: 'success',
                        title: 'Opinion realizada',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    router.push({ name: 'home' });
                }
            });
            console.log(respuesta);
        } catch (error) {
            console.error("Error al valorar -->", error);
        }
    }
  
};


</script>

<style scoped>
    .custom-rating{
        --p-rating-icon-font-size: 200px; /* Ajusta el tamaño */
    }
    .container-opinion{
        margin: 0px!important;
    }
</style>
