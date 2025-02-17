<template>
    <form @submit.prevent="submitForm">
        <div class="container-fluid">
            <div class="row my-5 gap-5 justify-content-center">
                <div class="col-md-8 col-lg-10">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h3>Fotos</h3>
                                <DropZoneV v-model="post.thumbnails" class="imagenes"/>
                               
                                
                            
                        </div>
                    </div>
                

                
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h3>Información del producto</h3>
                            <div class="d-flex gap-5">
                                <!-- Title -->
                            <div class="mb-3 input-nombre">
                                <input v-model="post.title" id="post-title" type="text" class="form-control" placeholder="Nombre del producto">
                                <div class="text-danger mt-1">
                                    {{ errors.title }}
                                </div>
                                <div class="text-danger mt-1">
                                    <div v-for="message in validationErrors?.title">
                                        {{ message }}
                                    </div>
                                </div>
                            </div>
                            <!-- Category -->
                            <div class="mb-3 input-categorias">
                                <MultiSelect v-model="post.categories" :options="categoryList" optionLabel="name" modelValue="id" optionValue="id" filter placeholder="Selecciona categorias" :maxSelectedLabels="3" class="w-full md:w-80" />
                                <div class="text-danger mt-1">
                                    {{ errors.categories }}
                                </div>
                                <div class="text-danger mt-1">
                                    <div v-for="message in validationErrors?.categories">
                                        {{ message }}
                                    </div>
                                </div>
                            </div>
                            </div>
                            <!-- Content -->
                            <div class="mb-3">
                                <label for="post-content" class="form-label">
                                    Content
                                </label>

                                <Editor v-model="post.content" editorStyle="height: 320px" />
                                <div class="text-danger mt-1">
                                    {{ errors.content }}
                                </div>
                                <div class="text-danger mt-1">
                                    <div v-for="message in validationErrors?.content">
                                        {{ message }}
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 input-estado">
                                <label>Estado:</label>
                                <MultiSelect 
                                    v-model="post.estado" 
                                    :options="[{ label: 'Nuevo', value: 'Nuevo' }, { label: 'Usado', value: 'Usado' }]" 
                                    optionLabel="label" 
                                    optionValue="value"
                                    placeholder="Selecciona estado" 
                                    :maxSelectedLabels="1" 
                                    class="w-full md:w-80" 
                                />
                                
                                <div class="text-danger mt-1">
                                    {{ errors.estado }}
                                </div>
                                <div class="text-danger mt-1">
                                    <div v-for="message in validationErrors?.estado">
                                        {{ message }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 text-center">
                            <button :disabled="isLoading" class="btn btn-primary">
                                <div v-show="isLoading" class=""></div>
                                <span v-if="isLoading">Processing...</span>
                                <span v-else>Publish</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>
<script setup>
import {onMounted, reactive, ref} from "vue";
import DropZone from "@/components/DropZone.vue";
import DropZoneV from "@/components/DropZone-varios.vue";
import useCategories from "@/composables/categories";
import usePosts from "@/composables/posts";
import {useForm, useField, defineRule} from "vee-validate";
import {required, min} from "@/validation/rules"
import { MultiSelect } from "primevue";

defineRule('required', required)
defineRule('min', min);

const dropZoneActive = ref(true)

// Define a validation schema
const schema = {
    title: 'required|min:5',
    content: 'required|min:50',
    categories: 'required'
}
// Create a form context with the validation schema
const {validate, errors} = useForm({validationSchema: schema})
// Define actual fields for validation
const {value: title} = useField('title', null, {initialValue: ''});
const {value: content} = useField('content', null, {initialValue: ''});
const {value: categories} = useField('categories', null, {initialValue: '', label: 'category'});
const {categoryList, getCategoryList} = useCategories()
const {storePost, validationErrors, isLoading} = usePosts()
const post = reactive({
    title,
    content,
    categories,
    thumbnail: ''
})

const thefile = ref('')

function submitForm() {
    validate().then(form => {
        if (form.valid) storePost(post)
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