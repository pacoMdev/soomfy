<template>
    <div class="container vh-100 d-grid align-items-center">
        <div class="row justify-content-center my-5">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form @submit.prevent="submitLogin">
                            <div class="text-center d-flex flex-column row-gap-2 py-5">
                                <h3 class="m-0">Iniciar sesión</h3>
                                <div class="d-flex justify-content-center gap-2">
                                    <p class="h1-p">¿No tienes cuenta?</p>
                                    <router-link to="/register" class="h1-p">Registrate!</router-link>
                                </div>
                                <!-- Email -->
                                <div class="mb-3">
                                    <FloatLabel>
                                        <InputText id="email" type="email" v-model="loginForm.email" class="form-control" autocomplete="username" :invalid="!email" />
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
                                        <InputText id="password" type="password" v-model="registerForm.password" autocomplete="password" :invalid="!password" />
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
                                <div class="form-check text-start">
                                    <input class="form-check-input" type="checkbox" name="remember" v-model="loginForm.remember" id="flexCheckIndeterminate">
                                    <label class="form-check-label" for="flexCheckIndeterminate">
                                        {{ $t('remember_me') }}
                                    </label>
                                </div>

                                <!-- Buttons -->
                                <div class="flex items-center justify-end mt-4">
                                    <button class="w-100 secondary-button-2" :class="{ 'opacity-25': processing }" :disabled="processing">
                                        {{ $t('Entrar') }}
                                    </button>
                                </div>
                                <router-link :to="{name: 'auth.forgot-password'}">{{ $t('forgot_password')}}</router-link>
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
import { ref } from 'vue'

const email = ref('');
const password = ref('');

const { loginForm, validationErrors, processing, submitLogin } = useAuth();

</script>
