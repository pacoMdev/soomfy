<template>
  <div class="row justify-content-center my-5">
    <div class="col-12 col-md-10 col-lg-8">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <form @submit.prevent="submitForm">
            <div class="row">
              <div class="mb-3 col-md-6">
                <label for="ts-transactions_id" class="form-label">Transactions_id</label>
                <input v-model="ts.transactions_id" id="ts-transactions_id" type="text" class="form-control">
                <div class="text-danger mt-1">{{ errors.transactions_id }}</div>
                <div class="text-danger mt-1" v-for="message in validationErrors?.transactions_id" :key="message">{{ message }}</div>
              </div>
  
              <div class="mb-3 col-md-6">
                <label for="ts-shipping_address_id" class="form-label">Shipping_address_id</label>
                <input v-model.number="ts.shipping_address_id" id="ts-shipping_address_id" type="number" class="form-control">
                <div class="text-danger mt-1">{{ errors.shipping_address_id }}</div>
                <div class="text-danger mt-1" v-for="message in validationErrors?.shipping_address_id" :key="message">{{ message }}</div>
              </div>
            </div>
            <div class="row">
              <div class="mb-3 col-md-6">
                <label for="ts-status" class="form-label">Status</label>
                <Dropdown v-model="ts.status" :options="statusOptions" optionLabel="label" optionValue="value" placeholder="Select Status" class="w-full" inputId="ts-status" />
                <div class="text-danger mt-1">{{ errors.status }}</div>
                <div class="text-danger mt-1" v-for="message in validationErrors?.status" :key="message">{{ message }}</div>
              </div>
  
              <div class="mb-3 col-md-6">
                <label for="ts-tracking_number" class="form-label">Tracking_number</label>
                <input v-model="ts.tracking_number" id="ts-tracking_number" type="text" class="form-control">
                <div class="text-danger mt-1">{{ errors.tracking_number }}</div>
                <div class="text-danger mt-1" v-for="message in validationErrors?.tracking_number" :key="message">{{ message }}</div>
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
import { reactive } from "vue";
import { useForm, useField, defineRule } from "vee-validate";
import { required, min } from "@/validation/rules"
import useTS from "../../../composables/shippingTransaction";
import Dropdown from 'primevue/dropdown';
defineRule('required', required)
defineRule('min', min);


const {
  storeTs,
  validationErrors,
  isLoading
} = useTS();

const schema = {
  transactions_id: 'required',
  shipping_address_id: 'required',
  status: 'required',
  tracking_number: 'required',
}

const { validate, errors } = useForm({ 
  validationSchema: schema,
  validateOnMount: false,
})

const { value: transactions_id } = useField('transactions_id', null, { initialValue: '' });
const { value: shipping_address_id } = useField('shipping_address_id', null, { initialValue: '' });
const { value: status } = useField('status', null, { initialValue: '' });
const { value: tracking_number } = useField('tracking_number', null, { initialValue: '' });

const statusOptions = [
  { label: 'Ordered', value: 'ordered' },
  { label: 'Processed', value: 'processed' },
  { label: 'To Pick', value: 'to_pick' },
  { label: 'Finished', value: 'finished' },
];

const ts = reactive({
  transactions_id,
  shipping_address_id,
  status,
  tracking_number,
})

function submitForm() {
  console.log('Archivo Create.vue' +
      'ts:', ts);

  validate().then(form => { if (form.valid) storeTs(ts) })
}
</script>