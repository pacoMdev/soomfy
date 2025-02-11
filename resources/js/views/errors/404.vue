<template>
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="text-center">
            <h1 class="display-1 fw-bold">404</h1>
            <p class="fs-3"> <span class="text-danger">Opps!</span> Pagina no encontrada.</p>
            <p class="lead">
                La pagina que buscas no existe!.
            </p>
            <router-link to="/" class="secondary-button-2">Volver a Soomfy</router-link>
        </div>
    </div>
</template>

<script>
export default {
    name: "404"
}
</script>

<style scoped>

</style>
