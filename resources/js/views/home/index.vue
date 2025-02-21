<template>
    <div class="separacion-general">
        <div class="d-none d-md-block">
            <div class="separacion-general-web">
                <div class="fondoBienvenida d-flex justify-content-center">
                    <div class="d-flex flex-column align-items-center justify-content-center contenidoBienvenida text-center">
                        <h1 class="tamañoH1">¡Compra y vende artículos de segunda mano sin salir de casa!</h1>
                        <h2 class="pb-6 m-0">¡Todo a solo un clic de distancia!</h2>
                        <div class="d-flex w-100 justify-content-center"> 
                            <input type="text" class="buscadorProductos">
                            <button class="secondary-button-2 no-borders-left"><i class="fas fa-search"></i> Search</button>
                        </div>
                    </div>
                </div>
                <div class="centrar-categories">
                    <div class="categories">
                        <div class="d-flex flex-column text-center gap-2">
                            <img src="images/home/categories/telefono.webp" alt="">
                            <p>Moviles</p>
                        </div>
                        <div class="d-flex flex-column text-center gap-2">
                            <img src="images/home/categories/tecnologia.webp" alt="">
                            <p>Tecnologia</p>
                        </div>
                        <div class="d-flex flex-column text-center gap-2">
                            <img src="images/home/categories/car.webp" alt="">
                            <p>Coches</p>
                        </div>
                        <div class="d-flex flex-column text-center gap-2">
                            <img src="images/home/categories/moto.webp" alt="">
                            <p>Motos</p>
                        </div>
                        <div class="d-flex flex-column text-center gap-2">
                            <img src="images/home/categories/inmuebles.webp" alt="">
                            <p>Inmuebles</p>
                        </div>
                        <div class="d-flex flex-column text-center gap-2">
                            <img src="images/home/categories/herramientas.webp" alt="">
                            <p>Herramientas</p>
                        </div>
                        <div class="d-flex flex-column text-center gap-2">
                            <img src="images/home/categories/moda.webp" alt="">
                            <p>Moda</p>
                        </div>
                        <div class="d-flex flex-column text-center gap-2">
                            <img src="images/home/categories/services.webp" alt="">
                            <p>Servicios</p>
                        </div>
                        <div class="d-flex flex-column text-center gap-2">
                            <img src="images/home/categories/muebles.webp" alt="">
                            <p>Muebles</p>
                        </div>
                        <div class="d-flex flex-column text-center gap-2">
                            <img src="images/home/categories/jobs.webp" alt="">
                            <p>Empleo</p>
                        </div>
                        <div class="d-flex flex-column text-center gap-2">
                            <img src="images/home/categories/jardineria.webp" alt="">
                            <p>Jardineria</p>
                        </div>
                        <div class="d-flex flex-column text-center gap-2">
                            <img src="images/home/categories/deporte.webp" alt="">
                            <p>Deporte</p>
                        </div>
                        <div class="d-flex flex-column text-center gap-2">
                            <img src="images/home/categories/construccion.webp" alt="">
                            <p>Construccion</p>
                        </div>
                        <div class="d-flex flex-column text-center gap-2">
                            <img src="images/home/categories/others.webp" alt="">
                            <p>Otros...</p>
                        </div>
                    </div>
                </div>

                <div class="centrar-productos">
                  <div class="productos">
                    <Producto />
                  </div>
                </div>

                <div class="d-flex justify-content-center">
                  <button class="secondary-button-2">¡Ver mas!</button>
                </div>

                <div class="apartado-mensaje">
                  <h1>Dale una nueva vida a lo que ya no usas!</h1>
                  <h2 class="m-0">¡¡Compra, vende y haz la diferencia!</h2>

                  <img src="images/cascos-decoracion.webp" alt="Cascos"class="cascos">
                  <img src="images/pelota-decoracion.webp" alt="Pelota" class="pelota">
                  <img src="images/reloj-decoracion.webp" alt="Reloj" class="reloj">
                  <img src="images/zapato-decoracion.webp" alt="Zapatilla" class="zapato">
                </div>
            </div>
        </div>
    </div>
    <div class="d-block d-md-none">
        <div>
            <div class="contenedor-informativo">

            </div>
            <div class="">
                <!-- falta ajustar el responsive -->
                <div class="productos">
                    <Producto />
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <button class="secondary-button-2">¡Ver mas!</button>
            </div>
        </div>
    </div>
</template>



<script setup>

import '../../../css/home/home.css';
import Producto from '../../components/Producto.vue';

</script>