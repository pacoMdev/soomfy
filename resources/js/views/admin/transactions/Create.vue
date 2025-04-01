<template>
    <div class="row justify-content-center my-5">
        <div class="col-md-10">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form @submit.prevent="submitForm">
                        <div class="mb-3">
                            <label for="transaction-userBuyer_id" class="form-label">userBuyer_id</label>
                            <input v-model="transaction.userBuyer_id" id="transaction-userBuyer_id" type="number" class="form-control">
                            <div class="text-danger mt-1">
                                {{ errors.userBuyer_id }}
                            </div>
                            <div class="text-danger mt-1">
                                <div v-for="message in validationErrors?.userBuyer_id">
                                    {{ message }}
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="transaction-userSeller_id" class="form-label">userSeller_id</label>
                            <input v-model="transaction.userSeller_id" id="transaction-userSeller_id" type="number" class="form-control">
                            <div class="text-danger mt-1">
                                {{ errors.userSeller_id }}
                            </div>
                            <div class="text-danger mt-1">
                                <div v-for="message in validationErrors?.userSeller_id">
                                    {{ message }}
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="transaction-initialPrice" class="form-label">initialPrice</label>
                            <input v-model="transaction.initialPrice" id="transaction-initialPrice" type="number" class="form-control">
                            <div class="text-danger mt-1">
                                {{ errors.initialPrice }}
                            </div>
                            <div class="text-danger mt-1">
                                <div v-for="message in validationErrors?.initialPrice">
                                    {{ message }}
                                </div>
                            </div>
                        </div><div class="mb-3">
                            <label for="transaction-finalPrice" class="form-label">finalPrice</label>
                            <input v-model="transaction.finalPrice" id="transaction-finalPrice" type="number" class="form-control">
                            <div class="text-danger mt-1">
                                {{ errors.finalPrice }}
                            </div>
                            <div class="text-danger mt-1">
                                <div v-for="message in validationErrors?.finalPrice">
                                    {{ message }}
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex flex-column gap-2">
                                <label for="transaction-isRegated" class="form-label">isRegated</label>
                                <ToggleSwitch v-model="transaction.isRegated" id="transaction-isRegated" class="form-control" />
                            </div>
                            <div class="text-danger mt-1">
                                {{ errors.isRegated }}
                            </div>
                            <div class="text-danger mt-1">
                                <div v-for="message in validationErrors?.isRegated">
                                    {{ message }}
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex flex-column gap-2">
                                <label for="transaction-isToSend" class="form-label">isToSend</label>
                                <ToggleSwitch v-model="transaction.isToSend" id="transaction-isToSend" class="form-control" />
                            </div>
                            <div class="text-danger mt-1">
                                {{ errors.isToSend }}
                            </div>
                            <div class="text-danger mt-1">
                                <div v-for="message in validationErrors?.isToSend">
                                    {{ message }}
                                </div>
                            </div>
                        </div>
                        <!-- Buttons -->
                        <div class="mt-4">
                            <button type="submit" @click="submitForm()" :disabled="isLoading" class="btn btn-primary">
                                <div v-show="isLoading" class=""></div>
                                <span v-if="isLoading">Processing...</span>
                                <span v-else>Save transaction</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
    import { onMounted, reactive } from "vue";
    import useTransactions from "../../../composables/transactions";
    import { useForm, useField, defineRule } from "vee-validate";
    import { required } from "@/validation/rules";
    import { ToggleSwitch } from 'primevue';
    defineRule('required', required);
    const { storeTransaction, validationErrors, isLoading } = useTransactions();

    // Define a validation schema
    const schema = {
        userBuyer_id: 'required',
        userSeller_id: 'required',
        product_id: 'required',
        initialPrice: 'required',
        finalPrice: 'required',
        
    }
    // Create a form context with the validation schema
    const { validate, errors } = useForm({ validationSchema: schema })
    // Define actual fields for validation
    const { value: userSeller_id } = useField('userSeller_id', null, { initialValue: '' });
    const { value: userBuyer_id } = useField('userBuyer_id', null, { initialValue: '' });
    const { value: product_id } = useField('product_id', null, { initialValue: '' });
    const { value: initialPrice } = useField('initialPrice', null, { initialValue: ''});
    const { value: finalPrice } = useField('finalPrice', null, { initialValue: '' });
    const { value: isRegated } = useField('isRegated', null, { initialValue: false });
    const { value: isToSend } = useField('isToSend', null, { initialValue: false });

    const transaction = reactive({
        userBuyer_id,
        userSeller_id,
        product_id,
        initialPrice,
        finalPrice,
        isRegated,
        isToSend,
    })
    function submitForm() {
        validate().then(form => { if (form.valid) storeTransaction(post) })
    }
    onMounted(() => {

    })
</script>
