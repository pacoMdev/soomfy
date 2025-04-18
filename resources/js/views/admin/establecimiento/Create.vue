<template>
  <div class="row justify-content-center my-5">
    <div class="col-md-6">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <form @submit.prevent="submitForm">
            <div class="row">
              <div class="mb-3 col-md-6">
                <label for="establecimiento-address" class="form-label">Address</label>
                <input v-model="establecimiento.address" id="establecimiento-address" type="text" class="form-control">
                <div class="text-danger mt-1">{{ errors.address }}</div>
                <div class="text-danger mt-1" v-for="message in validationErrors?.address" :key="message">{{ message }}</div>
              </div>
  
              <div class="mb-3 col-md-6">
                <label for="establecimiento-cp" class="form-label">CP</label>
                <input v-model.number="establecimiento.cp" id="establecimiento-cp" type="number" class="form-control">
                <div class="text-danger mt-1">{{ errors.cp }}</div>
                <div class="text-danger mt-1" v-for="message in validationErrors?.cp" :key="message">{{ message }}</div>
              </div>
            </div>
            <div class="row">
              <div class="mb-3 col-md-6">
                <label for="establecimiento-country" class="form-label">Country</label>
                <input v-model="establecimiento.country" id="establecimiento-country" type="text" class="form-control">
                <div class="text-danger mt-1">{{ errors.country }}</div>
                <div class="text-danger mt-1" v-for="message in validationErrors?.country" :key="message">{{ message }}</div>
              </div>
  
              <div class="mb-3 col-md-6">
                <label for="establecimiento-city" class="form-label">City</label>
                <input v-model="establecimiento.city" id="establecimiento-city" type="text" class="form-control">
                <div class="text-danger mt-1">{{ errors.city }}</div>
                <div class="text-danger mt-1" v-for="message in validationErrors?.city" :key="message">{{ message }}</div>
              </div>
            </div>

            <div class="mb-3">
              <label for="establecimiento-role-address" class="form-label">Role Address</label>
              <input v-model.number="establecimiento.role_address" id="establecimiento-role-address" type="number" class="form-control">
              <div class="text-danger mt-1">{{ errors.role_address }}</div>
              <div class="text-danger mt-1" v-for="message in validationErrors?.role_address" :key="message">{{ message }}</div>
            </div>

            <div class="mb-3">
              <label for="establecimiento-notes" class="form-label">Notes</label>
              <textarea v-model="establecimiento.notes" id="establecimiento-notes" class="form-control"></textarea>
              <div class="text-danger mt-1">{{ errors.notes }}</div>
              <div class="text-danger mt-1" v-for="message in validationErrors?.notes" :key="message">{{ message }}</div>
            </div>

            <div class="mb-3">
              <label for="establecimiento-contact-name" class="form-label">Contact Name</label>
              <input v-model="establecimiento.contact_name" id="establecimiento-contact-name" type="text" class="form-control">
              <div class="text-danger mt-1">{{ errors.contact_name }}</div>
              <div class="text-danger mt-1" v-for="message in validationErrors?.contact_name" :key="message">{{ message }}</div>
            </div>

            <div class="mb-3">
              <label for="establecimiento-contact-phone" class="form-label">Contact Phone</label>
              <input v-model="establecimiento.contact_phone" id="establecimiento-contact-phone" type="text" class="form-control">
              <div class="text-danger mt-1">{{ errors.contact_phone }}</div>
              <div class="text-danger mt-1" v-for="message in validationErrors?.contact_phone" :key="message">{{ message }}</div>
            </div>

            <div class="mb-3">
              <label for="establecimiento-contact-email" class="form-label">Contact Email</label>
              <input v-model="establecimiento.contact_email" id="establecimiento-contact-email" type="email" class="form-control">
              <div class="text-danger mt-1">{{ errors.contact_email }}</div>
              <div class="text-danger mt-1" v-for="message in validationErrors?.contact_email" :key="message">{{ message }}</div>
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
import { reactive } from "vue";
import { useForm, useField, defineRule } from "vee-validate";
import { required, min } from "@/validation/rules"
import useEstablecimiento from "../../../composables/establecimiento";
defineRule('required', required)
defineRule('min', min);


const {
  storeEstablecimiento,
  validationErrors,
  isLoading
} = useEstablecimiento();

const schema = {
  address: 'required|min:3',
  cp: 'required',
  country: 'required|min:2',
  city: 'required|min:2',
  role_address: 'required',
  notes: 'required|min:3',
  contact_name: 'required|min:3',
  contact_phone: 'required|min:7',
  contact_email: 'required'
}

const { validate, errors } = useForm({ 
  validationSchema: schema,
  validateOnMount: false,
})

const { value: address } = useField('address', null, { initialValue: '' });
const { value: cp } = useField('cp', null, { initialValue: '' });
const { value: country } = useField('country', null, { initialValue: '' });
const { value: city } = useField('city', null, { initialValue: '' });
const { value: role_address } = useField('role_address', null, { initialValue: '' });
const { value: notes } = useField('notes', null, { initialValue: '' });
const { value: contact_name } = useField('contact_name', null, { initialValue: '' });
const { value: contact_phone } = useField('contact_phone', null, { initialValue: '' });
const { value: contact_email } = useField('contact_email', null, { initialValue: '' });


const establecimiento = reactive({
  address,
  cp,
  country,
  city,
  role_address,
  notes,
  contact_name,
  contact_phone,
  contact_email
})

function submitForm() {
  console.log('Archivo Create.vue' +
      'Categoria:', establecimiento);

  validate().then(form => { if (form.valid) storeEstablecimiento(establecimiento) })
}
</script>