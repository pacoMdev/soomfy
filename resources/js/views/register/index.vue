<template>
    <div class="container vh-100 d-grid align-items-center">
        <div class="row justify-content-center my-5">
            <div class="col-md-8">
                <div class="border-0 shadow-sm d-flex rounded">
                    <div class="d-flex justify-content-center align-items-center vh-50 bg-bs-color-secondary rounded">
                        <div class="w-50 text-center d-flex flex-column gap-2">
                            <h3>Hey, mercader!</h3>
                            <p>Ya tienes cuenta? pues inicia sesion y empieza a vender!</p>
                            <router-link to="/login" class="secondary-button-2 w-100 mx-auto">SIGN IN</router-link>
                        </div>
                    </div>
                    <div class="p-3 mx-auto w-50">
                        <form @submit.prevent="submitRegister">
                            <div class="text-center d-flex flex-column row-gap-2 py-5">
                                <h3 class="m-0 fw-bold">Crear Cuenta</h3>
                                <!-- Email -->
                                <div class="mb-3">
                                    <FloatLabel>
                                        <InputText id="name" v-model="registerForm.name" class="form-control" />
                                        <label for="username">Nombre de usuario</label>
                                    </FloatLabel>
                                    <!-- Validation Errors -->
                                    <div class="text-danger mt-1">
                                        <div class="" v-for="message in validationErrors?.name">
                                            {{ message }}
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <FloatLabel>
                                        <InputText id="email" type="email" v-model="registerForm.email" class="form-control" autocomplete="username" />
                                        <label for="email">Email</label>
                                    </FloatLabel>
                                    <!-- Validation Errors -->
                                    <div class="text-danger mt-1">
                                        <div v-for="message in validationErrors?.email">
                                            {{ message }}
                                        </div>
                                    </div>
                                </div>
                                <!-- Password -->
                                <div class="mb-4">
                                    <FloatLabel>
                                        <Password id="password" type="password" v-model="registerForm.password" class="w-100" autocomplete="current-password" toggleMask />
                                        <label for="password">Contraseña</label>
                                    </FloatLabel>
                                    <div class="text-danger-600 mt-1">
                                        <div v-for="message in validationErrors?.password">
                                            {{ message }}
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <FloatLabel>
                                        <Password id="password_confirmation" type="password" v-model="registerForm.password_confirmation" class="w-100" autocomplete="current-password" toggleMask />
                                        <label for="password_confirmation">Confirmar contraseña</label>
                                    </FloatLabel>
                                    <!-- Validation Errors -->
                                    <div class="text-danger-600 mt-1">
                                        <div v-for="message in validationErrors?.password_confirmation">
                                            {{ message }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Buttons -->
                                <div class="flex items-center justify-end mt-4">
                                    <button class="w-50 secondary-button-2 mx-auto" :class="{ 'opacity-25': processing }" :disabled="processing">
                                        {{ $t('SING UP') }}
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
import { Password } from 'primevue';
import { Text } from 'vue';

const { registerForm, validationErrors, processing, submitRegister } = useAuth();

</script>
<style>
    input{
        width: 100%;

    }
</style>
