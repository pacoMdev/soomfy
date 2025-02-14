<template lang="">
    <div v-for="producto in productos" :key="producto.id" class="producto">
        <h1>Detalle poducto</h1>
        <div class="container-left">
            <!-- <Galleria :value="getImage(post.resized_image)" :responsiveOptions="responsiveOptions" :numVisible="5" :circular="true" containerStyle="max-width: 640px"
            :showItemNavigators="true">
                <template #item="slotProps">
                    <img :src="slotProps.item.original_url" :alt="post.title" style="width: 100%; display: block;" />
                </template>
            </Galleria> -->
        </div>
        <div class="container-right">
            <div class="container-info-prod">
                <h3>Title product</h3>
                <h3>Description product for all </h3>
                <h3>State Product</h3>
                <div class="container-categories">
                    <p>category 1</p>
                    <p>category 2</p>
                    <p>category 3</p>
                </div>
            </div>
            <div>
                <!-- imagen de informacion se seguridad -->
                <img src="" alt="">
                <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Nostrum earum cumque voluptatibus beatae provident culpa eum, unde laborum pariatur, dicta adipisci exercitationem error dolores explicabo alias totam. Dolorem, dolore atque.
                </p>
            </div>
            <div class="info-profile-post">
                <img src="" alt="">
                <div>
                    <h3>Username</h3>
                    <Rating v-model="value" :stars="5" :value="3" />
                    <div>
                        <img src="" alt="">
                        <p>Calle falsa 123</p>
                    </div>
                    

                </div>
            </div>

        </div>
    </div>
</template>
<script setup>

    import { ref, onMounted } from 'vue';
    import Rating from 'primevue/rating';


    onMounted(() => {
        getPost();

    })


    const path = window.location.pathname; // obtiene url
    const segments = path.split('/');
    const id = segments.pop();  // obtiene ultimo elemento (id)

    const post = ref([]);


    const getPost = async () => {
        const respuesta = await axios.get('/api/get-post/'+id); // Asegúrate de que esta URL sea la correcta
        post.value = respuesta.data.data; // Guardamos los datos en productos
        console.log(respuesta);
    };
    
    function getImage(resized_image){
        return Object.value(resized_image || {});
    }


</script>
<style lang="">
    
</style>