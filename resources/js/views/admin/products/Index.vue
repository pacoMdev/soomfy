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
            <input v-model="search_global" type="text" placeholder="Search..."
                   class="form-control w-25">
          </div>
          <div class="table-responsive">
            <table class="table" v-if="products">
              <thead>
              <tr>
                <th class="px-6 py-3 bg-gray-50 text-left">
                  <input v-model="search_id" type="text"
                         class="inline-block mt-1 form-control"
                         placeholder="Filter by ID">
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left">
                  <input v-model="search_title" type="text"
                         class="inline-block mt-1 form-control"
                         placeholder="Filter by Title">
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left">
                  <!-- <v-select multiple v-model="search_category" :options="categoryList" :reduce="category => category.id" label="name" class="form-control w-100"/> -->
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left">
                  <input v-model="search_content" type="text"
                         class="inline-block mt-1 form-control"
                         placeholder="Filter by Content">
                </th>
                <th class="px-6 py-3 text-start"></th>
                <th class="px-6 py-3 text-start"></th>
              </tr>
              <tr>
                <th class="px-6 py-3 text-start">
                  <div class="flex flex-row"
                       @click="updateOrdering('id')">
                    <div class="font-medium text-uppercase"
                         :class="{ 'font-bold text-blue-600': orderColumn === 'id' }">
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
                  <div class="flex flex-row"
                       @click="updateOrdering('title')">
                    <div class="font-medium text-uppercase"
                         :class="{ 'font-bold text-blue-600': orderColumn === 'title' }">
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
                  <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Content</span>
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left">
                  <div class="flex flex-row items-center justify-between cursor-pointer"
                       @click="updateOrdering('created_at')">
                    <div class="leading-4 font-medium text-gray-500 uppercase tracking-wider"
                         :class="{ 'font-bold text-blue-600': orderColumn === 'created_at' }">
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
              <tr v-for="product in products?.data" :key="product?.id">
                <td class="px-6 py-4 text-sm" width="20">
                  {{ product?.id }}
                </td>
                <td class="px-6 py-4 text-sm">
                  {{ product?.title }}
                </td>
                <td class="px-6 py-4 text-sm">
                  <img v-if="product?.original_image" :src="product.original_image" alt="image" height="70">
                </td>
                <td class="px-6 py-4 text-sm">
                  <div v-for="category in product?.categories" :key="category?.id">
                    {{ category?.name }}
                  </div>
                </td>
                <td class="px-6 py-4 text-sm">
                  <div v-if="product?.content" v-html="product.content.slice(0, 100) + '...'"></div>
                </td>
                <td class="px-6 py-4 text-sm">
                  {{ product?.created_at }}
                </td>
                <td class="px-6 py-4 text-sm">
                  <router-link v-if="can('product-edit')"
                               :to="{ name: 'products.edit', params: { id: product.id } }" class="badge bg-primary">Edit
                  </router-link>
                  <a href="#" v-if="can('product-delete')" @click.prevent="deleteProduct(product.id)"
                     class="ms-2 badge bg-danger">Delete</a>
                </td>
              </tr>
              </tbody>
            </table>
            <div v-else class="text-center py-4">
              Carregant productes...
            </div>
          </div>
        </div>
        <div class="card-footer">
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import useProducts from "@/composables/products.js";
import useCategories from "@/composables/categories";
import { useAbility } from '@casl/vue';
import { debounce } from 'lodash';

// Inicialització de les variables reactives
const search_category = ref('')
const search_id = ref('')
const search_title = ref('')
const search_content = ref('')
const search_global = ref('')
const orderColumn = ref('created_at')
const orderDirection = ref('desc')

// Composables
const { products, getProducts, deleteProduct } = useProducts()
const { categoryList, getCategoryList } = useCategories()
const { can } = useAbility()

// Funcions
const updateOrdering = async (column) => {
  try {
    orderColumn.value = column
    orderDirection.value = (orderDirection.value === 'asc') ? 'desc' : 'asc'
    await getProducts(
        1,
        search_category.value,
        search_id.value,
        search_title.value,
        search_content.value,
        search_global.value,
        orderColumn.value,
        orderDirection.value
    )
  } catch (error) {
    console.error('Error updating ordering:', error)
  }
}

// Watchers amb gestió d'errors
const createWatcher = (searchRef, paramName) => {
  return watch(searchRef, async (current) => {
    try {
      await getProducts(
          1,
          paramName === 'category' ? current : search_category.value,
          paramName === 'id' ? current : search_id.value,
          paramName === 'title' ? current : search_title.value,
          paramName === 'content' ? current : search_content.value,
          paramName === 'global' ? current : search_global.value
      )
    } catch (error) {
      console.error(`Error en el watcher de ${paramName}:`, error)
    }
  })
}

// Configuració dels watchers
createWatcher(search_category, 'category')
createWatcher(search_id, 'id')
createWatcher(search_title, 'title')
createWatcher(search_content, 'content')

// Watcher per search_global amb debounce
watch(search_global, debounce(async (current) => {
  try {
    await getProducts(
        1,
        search_category.value,
        search_id.value,
        search_title.value,
        search_content.value,
        current
    )
  } catch (error) {
    console.error('Error en el watcher de search_global:', error)
  }
}, 200))

// Inicialització
onMounted(async () => {
  try {
    await Promise.all([
      getProducts(),
      getCategoryList()
    ])
  } catch (error) {
    console.error('Error durant la inicialització:', error)
  }
})
</script>