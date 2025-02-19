<template>
    <div class="container vh-100 d-grid align-items-center">
        <div class="row justify-content-center my-5">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <img src="../../../../public/images/logo.svg" alt="">
                        <form @submit.prevent="submitRegister">
                            <div class="text-center d-flex flex-column row-gap-2 py-5">
                                <h3 class="m-0">Registro de sesión</h3>
                                <div class="d-flex justify-content-center gap-2">
                                    <p class="h1-p">¿Ya tienes una cuenta?</p>
                                    <router-link to="/login" class="h1-p">Iniciar sesión!</router-link>
                                </div>
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
                                    <button class="w-100 secondary-button-2" :class="{ 'opacity-25': processing }" :disabled="processing">
                                        {{ $t('register') }}
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
