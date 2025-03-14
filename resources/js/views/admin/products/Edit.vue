<template>
    {{ product }}
  <form @submit.prevent="submitForm">
    <div class="row my-5">
      <!-- Información principal del producto -->
      <div class="col-md-8">
        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <div class="mb-3">
              <h3>Fotos</h3>
              <DropZoneV v-model="product.thumbnails" class="imagenes"/>
              <div class="text-danger mt-1">
                {{ errors.thumbnails }}
              </div>
              <div class="text-danger mt-1">
                <div v-for="message in validationErrors?.thumbnails">
                  {{ message }}
                </div>
              </div>
            </div>
            <!-- Título -->
            <div class="mb-3">
              <label for="product-title" class="form-label">Title</label>
              <input
                  v-model="product.title"
                  id="product-title"
                  type="text"
                  class="form-control"
                  placeholder="Enter the title of the product"
              />
              <div class="text-danger mt-1">
                {{ errors.title }}
              </div>
            </div>

            <!-- Contenido -->
            <div class="mb-3">
              <label for="product-content" class="form-label">Content</label>
              <input
                  v-model="product.content"
                  type="text"
                  id="product-content"
                  class="form-control"
                  placeholder="Describe your product here"
              />
              <div class="text-danger mt-1">
                {{ errors.content }}
              </div>
            </div>

            <!-- Precio -->
            <div class="mb-3">
              <label for="product-price" class="form-label">Price</label>
              <input
                  v-model="product.price"
                  id="product-price"
                  type="number"
                  min="0"
                  class="form-control"
                  placeholder="Enter the price of the product"
              />
              <div class="text-danger mt-1">
                {{ errors.price }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Estado y Categoría -->
      <div class="col-md-4">
        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <!-- Estado -->
            <div class="mb-3">
              <label for="product-estado" class="form-label">Estado</label>
              <select
                  v-model="product.estado"
                  id="product-estado"
                  class="form-select"
              >
                <option value="" disabled>Select a state</option>
                <option
                    v-for="estado in estadoList"
                    :key="estado.id"
                    :value="estado.id"
                >
                  {{ estado.name }}
                </option>
              </select>
              <div class="text-danger mt-1">
                {{ errors.estado }}
              </div>
            </div>

            <!-- Categoría -->
            <div class="mb-3">
              <label for="product-category" class="form-label">Category</label>
              <select
                  v-model="product.category"
                  id="product-category"
                  class="form-select"
              >
                <option value="" disabled>Select a category</option>
                <option
                    v-for="category in categoryList"
                    :key="category.id"
                    :value="category.id"
                >
                  {{ category.name }}
                </option>
              </select>
              <div class="text-danger mt-1">
                {{ errors.category_id }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Botones de acción -->
    <div class="text-center mt-4">
      <button
          :disabled="isLoading"
          class="btn btn-outline-primary me-2"
          type="button"
      >
        <span v-if="isLoading">Saving...</span>
        <span v-else>Save as Draft</span>
      </button>
      <button
          :disabled="isLoading"
          class="btn btn-primary"
          type="submit"
      >
        <span v-if="isLoading">Publishing...</span>
        <span v-else>Publish</span>
      </button>
    </div>
  </form>

</template>
<script setup>
import {onMounted, reactive, ref, watchEffect} from "vue";
    import { useRoute } from "vue-router";
    import useCategories from "@/composables/categories";
    import useProducts from "@/composables/products.js";
    import { useForm, useField, defineRule } from "vee-validate";
    import { required, min } from "@/validation/rules"
    import DropZone from "@/components/DropZone.vue";
    import DropZoneV from "@/components/DropZone-varios.vue";
    defineRule('required', required)
    defineRule('min', min);

    // Define a validation schema
    const schema = {
        title: 'required|min:8',
        content: 'required|min:25',
        category: null,
        price: 'required|min:1',
        estado: null,
        thumbnails: null
    }
    // Create a form context with the validation schema
    const { validate, errors, resetForm } = useForm({ validationSchema: schema })
    // Define actual fields for validation
    const { value: title } = useField('title', null, { initialValue: '' });
    const { value: content } = useField('content', null, { initialValue: '' });
    const { value: price } = useField('price', null, { initialValue: '' });
    const { value: estado } = useField('estado', null, { initialValue: '' });
    const { value: category } = useField('category', null, { initialValue: '', label: 'category' });
    const { value: thumbnails } = useField('thumbnails', null, { initialValue: [] });
    const { categoryList, getCategoryList } = useCategories()
    const { product: productData,getEstadoList,estadoList, getProduct, updateProduct, validationErrors, isLoading } = useProducts()

    const product = ref({
        title,
        content,
        price,
        estado,
        category,
        thumbnails
    })
    const route = useRoute()

    onMounted(() => {
        getProduct(route.params.id)
        getCategoryList()
        getEstadoList()
    })

    const originalProduct = ref({});
    // Sincronizar
    // https://vuejs.org/api/reactivity-core.html#watcheffect
    watchEffect(() => {
      if (productData.value) {
        // Asignar valores del producto
        product.value.id = productData.value.id;
        product.value.title = productData.value.title;
        product.value.content = productData.value.content;
        product.value.price = productData.value.price;
        product.value.estado = productData.value.estado?.id || '';
        product.value.category = productData.value.category?.id || '';

        // Validar imágenes en resized_image o thumbnails
        if (productData.value.media && productData.value.media.length > 0) {
          product.value.thumbnails = productData.value.media.map((img) => ({
            img: img.original_url,
            file: null,
          }));
        } else if (productData.value.resized_image && Object.keys(productData.value.resized_image).length > 0) {
          // Convertir el objeto de imágenes a un array
          product.value.thumbnails = Object.values(productData.value.resized_image).map(img => ({
            img: img.original_url,
            file: null,
            id: img.uuid
          }));
        }
        else {
            product.value.thumbnails = [];
        }
        // Asegúrate de que haya suficientes slots para el máximo de imágenes
        while (product.value.thumbnails.length < 3) {
          product.value.thumbnails.push({ img: "", file: null });
        }


      }
    });

    function submitForm() {
      const formData = new FormData();
      const productId = product.value.id;
      formData.append('id', productId);
      formData.append('title', product.value.title);
      formData.append('content', product.value.content);
      formData.append('price', product.value.price);
      formData.append('estado_id', product.value.estado);
      formData.append('category_id', product.value.category);
      if (product.value.thumbnails && product.value.thumbnails.length) {
        product.value.thumbnails.forEach((item, index) => {
          if (item instanceof File || (item.file && item.file instanceof File)) {
            const file = item instanceof File ? item : item.file;
            formData.append(`thumbnails[${index}]`, file);
            formData.append(`positions[${index}]`, index.toString());
          }
        });
      }


      for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + (pair[1] instanceof File ? `File: ${pair[1].name}` : pair[1]));
      }

      validate().then((form) => {
        if (form.valid) {
          updateProduct(formData, productId)
        } else {
          // Desplegar los errores de validación si los datos no son válidos
          console.log("Errores de validación:", errors);
        }
      });
    }

onMounted(async () => {
  try {
    // Suposem que ja tens carregat el producte en 'product.value'

    // Convertir les imatges del format del servidor al format del Dropzone
    if (product.value.resized_image) {
      // Converteix l'objecte d'imatges a un array
      const mediaArray = Object.values(product.value.resized_image);

      // Mapeja cada imatge al format que espera el Dropzone
      thumbnails.value = mediaArray.map(media => ({
        img: media.original_url, // URL de la imatge
        file: null, // No tenim l'arxiu original, només la URL
        id: media.uuid // Guardar el UUID per si necessitem eliminar-la després
      }));
    }
  } catch (error) {
    console.error('Error al carregar les imatges:', error);
  }
});



</script>
