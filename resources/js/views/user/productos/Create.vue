
<template>
    <form @submit.prevent="submitForm">
        <div class="card flex justify-center">
            <Stepper value="1" class="basis-[50rem]">
                <StepList>
                    <Step value="1">Informacion Basica</Step>
                    <Step value="2">Informacion Adicional</Step>
                </StepList>
                <StepPanels>
                    <StepPanel v-slot="{ activateCallback }" value="1">
                        <div class="display-flex flex-column gap-5">
                            <!-- IMAGE PRODUCT ---------------------------------------------------- -->
                            <div class="card-body">
                                <h3>Fotos</h3>
                                    <DropZoneV v-model="post.thumbnails" class="imagenes"/>
                            </div>
                            <!-- TITLE ---------------------------------------------------- -->
                            <div class="mb-3 input-nombre">
                                <FloatLabel>
                                    <InputText v-model="post.title" id="post-title" />
                                    <label for="post-title">Nombre del producto</label>
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
                        <!-- CONTENT ---------------------------------------------------- -->
                        <div class="mb-3">
                            <FloatLabel>
                                <Textarea id="content" v-model="post.content" rows="5" cols="50" />
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
                        <div class="mb-3 input-categorias">
                            <FloatLabel>
                                <MultiSelect v-model="post.categories" :options="categoryList" optionLabel="name" modelValue="id" optionValue="id" filter :maxSelectedLabels="3" class="w-full md:w-80" />
                                <label for="categorias">Selecciona categorias</label>
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
                        <div class="mb-3 input-estado">
                            <FloatLabel>
                                <Select 
                                    v-model="post.estado" 
                                    :options="[{ label: 'Nuevo', value: 'Nuevo' }, { label: 'Usado', value: 'Usado' }, { label: 'Desgastado', value: 'Desgastado' }]" 
                                    optionLabel="label" 
                                    optionValue="value"
                                    :maxSelectedLabels="1" 
                                    class="w-full md:w-80" 
                                />
                                <label for="estado">Selecciona estado</label>
                            </FloatLabel>
                            
                            <div class="text-danger mt-1">
                                {{ errors.estado }}
                            </div>
                            <div class="text-danger mt-1">
                                <div v-for="message in validationErrors?.estado">
                                    {{ message }}
                                </div>
                            </div>
                        </div>
                        <!-- PRICE ---------------------------------------------------- -->
                        <div class="mb-3 input-price">
                            <FloatLabel>
                                <InputNumber v-model="post.price" inputId="local-user" :minFractionDigits="2" fluid id="price-product"/>
                                <label for="price-product">Precio</label>
                            </FloatLabel>
                        </div>
                        <!-- BUTTONS OPTIONS ---------------------------------------------------- -->
                        <div class="flex pt-6 justify-between">
                            <Button label="Back" severity="secondary" icon="pi pi-arrow-left" @click="activateCallback('1')" />
                            <button :disabled="isLoading" class="btn btn-primary">
                                <div v-show="isLoading" class=""></div>
                                <span v-if="isLoading">Processing...</span>
                                <span v-else>Publish</span>
                            </button>
                            <!-- <Button label="Next" icon="pi pi-arrow-right" iconPos="right" @click="activateCallback('3')" /> -->
                        </div>
                    </StepPanel>
                </StepPanels>
            </Stepper>
        </div>
    </form>
</template>


<script setup>
import {onMounted, reactive, ref} from "vue";
import DropZoneV from "@/components/DropZone-varios.vue";
import DropZone from "@/components/DropZone.vue";
import useCategories from "@/composables/categories";
import usePosts from "@/composables/posts";
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
    // thumbnails: 'required'

}
// Create a form context with the validation schema
const {validate, errors} = useForm({validationSchema: schema})

// Define actual fields for validation
const {value: title} = useField('title', null, {initialValue: ''});
const {value: content} = useField('content', null, {initialValue: ''});
const {value: categories} = useField('categories', null, {initialValue: '', label: 'category'});
const {value: price} = useField('price', null, {initialValue: 0});
const {value: estado} = useField('estado', null, {initialValue: ''});
const {value: thumbnails} = useField('thumbnails', null, {initialValue: []});
const {categoryList, getCategoryList} = useCategories()
const {storeProduct, validationErrors, isLoading} = usePosts()
const post = reactive({
    title,
    content,
    categories,
    thumbnails: [
        { img: "", file: null }, // Contenedor 1
        { img: "", file: null }, // Contenedor 2
        { img: "", file: null }  // Contenedor 3
    ], 
    price,
    estado
})

function submitForm() {
    console.log('submitForm', post);
    console.log('thumbnails', post.thumbnails)
    validate().then(form => {
        console.log('validando informacion')
        if (form.valid) storeProduct(post)
    })
}

onMounted(() => {
    getCategoryList()
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


.p-select-label {
    display: flex;
    align-items: center;
    gap: calc(var(--p-multiselect-padding-y) / 2);
    white-space: nowrap;
    cursor: pointer;
    overflow: hidden;
    text-overflow: ellipsis;
    padding: var(--p-multiselect-padding-y) var(--p-multiselect-padding-x);
    color: var(--p-multiselect-color);
}

.p-select-label.p-placeholder {
    color: var(--p-multiselect-placeholder-color);
}
</style>