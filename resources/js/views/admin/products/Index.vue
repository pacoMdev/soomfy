<template>
  <div class="row justify-content-center my-2">
    <div class="col-md-12">
      <div class="card border-0">
        <div class="card-header bg-transparent">
          <h5 class="float-start">Products</h5>
          <router-link v-if="can('product-create')" :to="{ name: 'products.create' }" class="btn btn-primary btn-sm float-end">
            Create Product
          </router-link>
        </div>
        <div class="card-body shadow-sm">
          <div class="mb-4">
            <input v-model="search_global" type="text" placeholder="Search..." class="form-control w-25">
          </div>
          <div class="table-responsive">
            <table class="table">
              <thead>
              <tr>
                <th class="px-6 py-3 bg-gray-50 text-left">
                  <input v-model="search_id" type="text" class="inline-block mt-1 form-control" placeholder="Filter by ID">
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left">
                  <input v-model="search_title" type="text" class="inline-block mt-1 form-control" placeholder="Filter by Title">
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left">
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left">
                  <input v-model="search_category" type="text" class="inline-block mt-1 form-control" placeholder="Filter by Category">
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left">
                  <input v-model="search_estado" type="text" class="inline-block mt-1 form-control" placeholder="Filter by Estado">
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left">
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left">
                  <input v-model="search_content" type="text" class="inline-block mt-1 form-control" placeholder="Filter by Content">
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left">
                </th>
                <th class="px-6 py-3 text-start"></th>
                <th class="px-6 py-3 text-start"></th>
              </tr>
              <tr>
                <th class="px-6 py-3 text-start">
                  <div class="flex flex-row" @click="updateOrdering('id')">
                    <div class="font-medium text-uppercase" :class="{ 'font-bold text-blue-600': orderColumn === 'id' }">
                      ID
                    </div>
                    <div class="select-none">
                      <span :class="{
                        'text-blue-600': orderDirection === 'asc' && orderColumn === 'id',
                        'hidden': orderDirection !== '' && orderDirection !== 'asc' && orderColumn === 'id',
                      }">&uarr;</span>
                      <span :class="{
                        'text-blue-600': orderDirection === 'desc' && orderColumn === 'id',
                        'hidden': orderDirection !== '' && orderDirection !== 'desc' && orderColumn === 'id',
                      }">&darr;</span>
                    </div>
                  </div>
                </th>
                <th class="px-6 py-3 text-left">
                  <div class="flex flex-row" @click="updateOrdering('title')">
                    <div class="font-medium text-uppercase" :class="{ 'font-bold text-blue-600': orderColumn === 'title' }">
                      Title
                    </div>
                    <div class="select-none">
                      <span :class="{
                        'text-blue-600': orderDirection === 'asc' && orderColumn === 'title',
                        'hidden': orderDirection !== '' && orderDirection !== 'asc' && orderColumn === 'title',
                      }">&uarr;</span>
                      <span :class="{
                        'text-blue-600': orderDirection === 'desc' && orderColumn === 'title',
                        'hidden': orderDirection !== '' && orderDirection !== 'desc' && orderColumn === 'title',
                      }">&darr;</span>
                    </div>
                  </div>
                </th>
                <th class="px-6 py-3 text-left">
                  <div class="flex flex-row">
                    <div class="font-medium text-uppercase">
                      Thumbnail
                    </div>
                  </div>
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left">
                  <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Category</span>
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left">
                  <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">State</span>
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left">
                  <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Content</span>
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left">
                  <div class="flex flex-row items-center justify-between cursor-pointer" @click="updateOrdering('created_at')">
                    <div class="leading-4 font-medium text-gray-500 uppercase tracking-wider" :class="{ 'font-bold text-blue-600': orderColumn === 'created_at' }">
                      Created at
                    </div>
                    <div class="select-none">
                      <span :class="{
                        'text-blue-600': orderDirection === 'asc' && orderColumn === 'created_at',
                        'hidden': orderDirection !== '' && orderDirection !== 'asc' && orderColumn === 'created_at',
                      }">&uarr;</span>
                      <span :class="{
                        'text-blue-600': orderDirection === 'desc' && orderColumn === 'created_at',
                        'hidden': orderDirection !== '' && orderDirection !== 'desc' && orderColumn === 'created_at',
                      }">&darr;</span>
                    </div>
                  </div>
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left">
                  Actions
                </th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="product in products.data" :key="product.id">
                <td class="px-6 py-4 text-sm" width="20">
                  {{ product.id }}
                </td>
                <td class="px-6 py-4 text-sm">
                  {{ product.title }}
                </td>
                <td class="px-6 py-4 text-sm">
                  <Galleria
                      :value="getProductImages(product.resized_image)"
                      :responsiveOptions="responsiveOptions"
                      :numVisible="5"
                      :circular="true"
                      containerStyle="height: 150px; width: 200px; border-radius: 10px;"
                      :showItemNavigators="true"
                      :showThumbnails="false"
                  >
                    <template #item="slotProps">
                      <img :src="slotProps.item.original_url" :alt="slotProps.item.name" style="width: 150px; object-fit: contain; display: block; margin: auto;" />
                    </template>
                  </Galleria>
                </td>
                <td class="px-6 py-4 text-sm">
                  {{ product?.category?.name || 'Sin categoría' }}
                </td>
                <td class="px-6 py-4 text-sm">
                  {{ product?.estado?.name || 'Sin estado' }}
                </td>
                <td class="px-6 py-4 text-sm">
                  <div v-html="product.content.slice(0, 100) + '...'"></div>
                </td>
                <td class="px-6 py-4 text-sm">
                  {{ product.created_at }}
                </td>
                <td class="px-6 py-4 text-sm">
                  <router-link v-if="can('product-edit')" :to="{ name: 'products.edit', params: { id: product.id } }" class="primary-button">Edit</router-link>
                  <a href="#" v-if="can('product-delete')" @click.prevent="deleteProduct(product.id)" class="ms-2 badge bg-danger">Delete</a>
                </td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer">
        </div>
      </div>
    </div>
  </div>
</template>


<script setup>
import {ref, onMounted, watch, computed} from "vue";
    import useProducts from "@/composables/products.js";
    import useCategories from "@/composables/categories";
    import {useAbility} from '@casl/vue'

    const search_category = ref('')
    const search_id = ref('')
    const search_title = ref('')
    const search_content = ref('')
    const search_global = ref('')
    const orderColumn = ref('created_at')
    const orderDirection = ref('desc')
    const min_price = ref('')
    const max_price = ref('')
    const search_estado = ref('')
    const search_location = ref('')
    const search_latitude = ref('')
    const search_longitude = ref('')
    const search_radius = ref('')
    const order_price = ref('')

    const {products, getProducts, deleteProduct} = useProducts()
    const {categoryList, getCategoryList} = useCategories()
    const {can} = useAbility();

    const props = defineProps({
      product: {
        type: Object,
        required: true
      },
      category: {
        type: Object,
        required: true
      }

    });

    // Convertir las imágenes al formato que espera Galleria
    const getProductImages = (resizedImages) => {
      if (!resizedImages) return [];
      return Object.values(resizedImages);
    };

    // Obtenemos la categoria de cada uno,

// Opciones responsive para la galería
    const responsiveOptions = ref([
      {
        breakpoint: '1024px',
        numVisible: 3
      },
      {
        breakpoint: '768px',
        numVisible: 2
      },
      {
        breakpoint: '560px',
        numVisible: 1
      }
    ]);

    onMounted(() => {
        getProducts()
        getCategoryList()
    })
const updateOrdering = (column) => {
  orderColumn.value = column;
  orderDirection.value = (orderDirection.value === 'asc') ? 'desc' : 'asc';

  getProducts(
      1,
      search_category.value,
      search_id.value,
      search_title.value,
      min_price.value,
      max_price.value,
      search_estado.value,
      search_location.value,
      search_content.value,
      search_global.value,
      orderColumn.value,
      orderDirection.value,
      order_price.value,
      search_latitude.value,
      search_longitude.value,
      search_radius.value
  );
}

// Watches corregidos
watch(search_category, (current) => {
  getProducts(
      1,
      current,
      search_id.value,
      search_title.value,
      min_price.value,
      max_price.value,
      search_estado.value,
      search_location.value,
      search_content.value,
      search_global.value,
      orderColumn.value,
      orderDirection.value,
      order_price.value,
      search_latitude.value,
      search_longitude.value,
      search_radius.value
  );
});

watch(search_estado, (current) => {
  getProducts(
      1,
      search_category.value,
      search_id.value,
      search_title.value,
      min_price.value,
      max_price.value,
      current,
      search_location.value,
      search_content.value,
      search_global.value,
      orderColumn.value,
      orderDirection.value,
      order_price.value,
      search_latitude.value,
      search_longitude.value,
      search_radius.value
  );
});

watch(search_id, (current) => {
  getProducts(
      1,
      search_category.value,
      current,
      search_title.value,
      min_price.value,
      max_price.value,
      search_estado.value,
      search_location.value,
      search_content.value,
      search_global.value,
      orderColumn.value,
      orderDirection.value,
      order_price.value,
      search_latitude.value,
      search_longitude.value,
      search_radius.value
  );
});

watch(search_title, (current) => {
  getProducts(
      1,
      search_category.value,
      search_id.value,
      current,
      min_price.value,
      max_price.value,
      search_estado.value,
      search_location.value,
      search_content.value,
      search_global.value,
      orderColumn.value,
      orderDirection.value,
      order_price.value,
      search_latitude.value,
      search_longitude.value,
      search_radius.value
  );
});

watch(search_content, (current) => {
  getProducts(
      1,
      search_category.value,
      search_id.value,
      search_title.value,
      min_price.value,
      max_price.value,
      search_estado.value,
      search_location.value,
      current,
      search_global.value,
      orderColumn.value,
      orderDirection.value,
      order_price.value,
      search_latitude.value,
      search_longitude.value,
      search_radius.value
  );
});

watch(search_global, _.debounce((current) => {
  getProducts(
      1,
      search_category.value,
      search_id.value,
      search_title.value,
      min_price.value,
      max_price.value,
      search_estado.value,
      search_location.value,
      search_content.value,
      current,
      orderColumn.value,
      orderDirection.value,
      order_price.value,
      search_latitude.value,
      search_longitude.value,
      search_radius.value
  );
}, 200));

</script>
