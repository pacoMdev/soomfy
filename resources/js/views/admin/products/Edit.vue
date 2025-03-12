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
                <option value="Nuevo">Nuevo</option>
                <option value="Usado">Usado</option>
              </select>
              <div class="text-danger mt-1">
                {{ errors.estado }}
              </div>
            </div>

            <!-- Categoría -->
            <div class="mb-3">
              <label for="product-category" class="form-label">Category</label>
              <select
                  v-model="product.category_id"
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
        title: 'required|min:5',
        content: 'required|min:5',
        category_id: null,
        price: null,
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
    const { value: category_id } = useField('category_id', null, { initialValue: '', label: 'category' });
    const { value: thumbnails } = useField('thumbnails', null, { initialValue: '' });
    const { categoryList, getCategoryList } = useCategories()
    const { product: productData, getProduct, updateProduct, validationErrors, isLoading } = useProducts()

    const product = reactive({
        title,
        content: "",
        price,
        estado,
        category_id,
        thumbnails: []
    })
    const route = useRoute()

    onMounted(() => {
        getProduct(route.params.id)
        getCategoryList()
    })

    const originalProduct = ref({});
    // Sincronizar
    // https://vuejs.org/api/reactivity-core.html#watcheffect
    watchEffect(() => {
      if (productData.value) {
        // Asignar valores del producto
        product.id = productData.value.id;
        product.title = productData.value.title;
        product.content = productData.value.content;
        product.price = productData.value.price;
        product.estado = productData.value.estado;

        // Validar imágenes en resized_image o thumbnails
        if (productData.value.resized_image) {
          product.thumbnails = Object.values(productData.value.resized_image).map((img) => ({
            img: img.original_url,
            file: null,
          }));
        } else if (productData.value.thumbnails) {
          product.thumbnails = productData.value.thumbnails; // Si thumbnails ya está procesado
        } else {
          product.thumbnails = [];
        }

        product.category_id = productData.value.category_id;

        // Guardar una copia inmutable del producto original
        originalProduct.value = { ...productData.value };

        // Depuración
        console.log('Thumbnails procesados:', JSON.parse(JSON.stringify(product.thumbnails)));

      }
    });

    function submitFormForUpdate() {
      validate().then((form) => {
        if (form.valid) {
          // Llamar a la función de actualización de producto con los valores reactivos
          updateProduct(product)
              .then(() => {
                swal({
                  icon: 'success',
                  title: 'Éxito',
                  text: 'El producto se ha actualizado correctamente.',
                });
              })
              .catch((error) => {
                console.error("Error al actualizar el producto:", error);
                swal({
                  icon: 'error',
                  title: 'Error',
                  text: 'Hubo un problema al actualizar el producto. Por favor, intente de nuevo.',
                });
              });
        } else {
          // Desplegar los errores de validación si los datos no son válidos
          console.log("Errores de validación:", errors);
        }
      });
    }
    

</script>
