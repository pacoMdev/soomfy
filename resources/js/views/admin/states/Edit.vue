<template>
    <div class="row justify-content-center my-5">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form @submit.prevent="submitForm">
                      <DropZone v-model="category.thumbnail" />
                      <div class="text-danger mt-1">
                        {{ errors.thumbnail }}
                      </div>
                      <div class="text-danger mt-1">
                        <div v-for="message in validationErrors?.thumbnail">
                          {{ message }}
                        </div>
                      </div>
                        <div class="mb-3">
                            <label for="post-title" class="form-label">
                                Title
                            </label>
                            <input v-model="category.name" id="post-title" type="text" class="form-control">
                            <div class="text-danger mt-1">
                                {{ errors.name }}
                            </div>
                            <div class="text-danger mt-1">
                                <div v-for="message in validationErrors?.name">
                                    {{ message }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button :disabled="isLoading" class="btn btn-primary">
                                <div v-show="isLoading" class=""></div>
                                <span v-if="isLoading">Processing...</span>
                                <span v-else>Save</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { onMounted, reactive, watchEffect } from "vue";
import { useRoute } from "vue-router";
import useCategories from "../../../composables/categories";
import { useForm, useField, defineRule } from "vee-validate";
import { required, min } from "@/validation/rules"
import DropZone from "@/components/DropZone.vue";
defineRule('required', required)
defineRule('min', min);

  const schema = {
      name: 'required|min:3'
  }

  const { validate, errors, resetForm } = useForm({ validationSchema: schema })
  const { value: name } = useField('name', null, { initialValue: '' });
  const { category: categoryData, getCategory, updateCategory, validationErrors, isLoading } = useCategories()
  const category = reactive({
      name,
      thumbnail: null
  })
  const route = useRoute()
  function submitForm() {
      validate().then(form => { if (form.valid) updateCategory(category) })
  }
  onMounted(() => {
      getCategory(route.params.id)
  })

  // https://vuejs.org/api/reactivity-core.html#watcheffect
  watchEffect(() => {
      category.id = categoryData.value.id
      category.name = categoryData.value.name
  })
</script>
