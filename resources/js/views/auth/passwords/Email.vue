<template>
    <div class="container vh-100 align-items-center d-grid">
        <div class="row justify-content-center my-5 align-items-center">
            <div class="col-md-6 align-items-center">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form @submit.prevent="submitForgotPassword">
                            <div class="text-center d-flex flex-column row-gap-2 py-5">
                                <!-- Email -->
                                <h3>Recuperación de contraseña:</h3>
                                <input v-model="forgotForm.email" placeholder="Email" id="email" type="email" class="form-control" required autofocus autocomplete="username">
                                <!-- Validation Errors -->
                                <div class="text-danger mt-1">
                                    <div v-for="message in validationErrors?.email">
                                        {{ message }}
                                    </div>
                                </div>
                                <!-- Buttons -->
                                <div class="flex items-center justify-content-center mt-4">
                                    <button class="secondary-button-2" :class="{ 'opacity-25': processing }" :disabled="processing">
                                        {{ $t('Enviar link de recuperación de contraseña') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import useAuth from '@/composables/auth'

const { forgotForm, validationErrors, processing, submitForgotPassword } = useAuth();
</script>

<style scoped>

</style>
