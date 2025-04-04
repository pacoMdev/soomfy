<template>
    <div class="container vh-100 d-grid align-items-center">
        <div class="row justify-content-center my-5">
            <div class="col-md-8">
                <div class="border-0 shadow-sm m-0 p-0 rounded" style="background-color: white;">
                    <div class="d-flex" >
                        <div class="w-50 p-5">
                            <form @submit.prevent="submitLogin">
                                <div class="text-center d-flex flex-column row-gap-2 py-5">
                                    <h3 class="m-0">Iniciar sesión</h3>
                                    <div class="d-flex gap-2 mx-auto">
                                        <Button @click="googleAuth" icon="pi pi-google" rounded variant="outlined" aria-label="primary" />
                                        <!-- <Button icon="pi pi-apple" rounded variant="outlined" aria-label="Filter" /> -->
                                    </div>
                                    <p>o usa tu centa</p>
                                    
                                    <!-- Email -->
                                    <div class="mb-3">
                                        <FloatLabel>
                                            <InputText id="email" type="email" v-model="loginForm.email" class="form-control" autocomplete="username" />
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
                                            <Password id="password" type="password" v-model="loginForm.password" class="w-100" autocomplete="current-password" :feedback="false" toggleMask />
                                            <label for="password">Contraseña</label>
                                        </FloatLabel>
                                        <!-- <input v-model="loginForm.password" id="password" type="password" placeholder="Password"  class="form-control" required autocomplete="current-password"> -->
                                        <!-- Validation Errors -->
                                        <div class="text-danger-600 mt-1">
                                            <div v-for="message in validationErrors?.password">
                                                {{ message }}
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Remember me -->
                                    <!-- <div class="form-check text-start">
                                        <input class="form-check-input" type="checkbox" name="remember" v-model="loginForm.remember" id="flexCheckIndeterminate">
                                        <label class="form-check-label" for="flexCheckIndeterminate">
                                            {{ $t('remember_me') }}
                                        </label>
                                    </div> -->
    
                                    <router-link :to="{name: 'auth.forgot-password'}">{{ $t('forgot_password')}}</router-link>
                                    <!-- Buttons -->
                                    <div class="flex items-center justify-end mt-4">
                                        <button class="secondary-button-2 w-50 mx-auto" :class="{ 'opacity-25': processing }" :disabled="processing">
                                            {{ $t('SIGN IN') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="d-flex justify-content-center align-items-center vh-50 bg-bs-color-secondary rounded">
                            <div class="w-50 text-center d-flex flex-column gap-2">
                                <h3>Hey, mercader!</h3>
                                <p>Comienza a vender y gana dinero, tus ventas comienzan registrándote.</p>
                                <router-link to="/register" class="secondary-button-2 w-100 mx-auto">SIGN UP</router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>

import useAuth from '@/composables/auth'
import { ref } from 'vue'
import { Password } from 'primevue';

const email = ref('');
const password = ref('');

const { loginForm, validationErrors, processing, submitLogin, googleAuth } = useAuth();
// redireccina al usuario hacia google select user
const googleLogin = () => {
    window.location.href = '/google-auth/redirect';
};

</script>

<style>
    input{
        width: 100%;
    }
</style>
