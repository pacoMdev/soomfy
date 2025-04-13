import { computed, ref } from 'vue'
import { useRouter } from 'vue-router'
import useFirebase from "@/composables/firebase.js";
import { authStore } from "@/store/auth.js";

export default function useProductDetail() {
    const path = window.location.pathname; // obtiene url
    const segments = path.split('/');
    const id = segments.pop();  // obtiene ultimo elemento (id)
    const product = ref([]);
    const relatedPost = ref([]);
    const images = ref();
    const address = ref('null');
    const fullAddress = ref(null);
    const position = ref('left');
    const compradorId = ref(null);
    const auth = authStore();
    const router = useRouter();
    const { chatExists } = useFirebase();
    const products = ref();
    const chatData = ref();
    const userProducts = ref([]);
    const relatedProducts = ref([]);
    const home = ref({
        icon: 'pi pi-home', route: '/'
    });
    const breadcrumbs = computed(() => [
        { label: 'Products', route: '/' },
        { label: product.value?.title || 'Cargando...', route: null }
    ]);
    const responsiveOptions = ref([
        {
            breakpoint: '1300px',
            numVisible: 4
        },
        {
            breakpoint: '575px',
            numVisible: 1
        }
    ]);
    const responsiveOptions2 = ref([
        {
            breakpoint: '1400px',
            numVisible: 2,
            numScroll: 1
        },
        {
            breakpoint: '1199px',
            numVisible: 3,
            numScroll: 1
        },
        {
            breakpoint: '767px',
            numVisible: 2,
            numScroll: 1
        },
        {
            breakpoint: '575px',
            numVisible: 2,
            numScroll: 1
        }
    ]);






    const getProduct = async () => {
        console.log(id);
        const respuesta = await axios.get('/api/products/'+id); // Asegúrate de que esta URL sea la correcta
        product.value = respuesta.data.data || {}; // Guardamos los datos en productos
        console.log('PRODUCT:', respuesta.data.data);
    };
    const getRelatedProducts = async () => {
        try {
            // Asumiendo que el producto tiene categorías y tomamos la primera
            if (product.value?.categories?.[0]?.id) {
                const categoryId = product.value.categories[0].id;
                const response = await axios.get(`/api/get-category-products/${categoryId}`);
                relatedProducts.value = response.data;
                console.log('RELATED PRODUCTS:', response.data);
            }
        } catch (error) {
            console.error('Error fetching related products:', error);
        }
    };


    const getGeoLocation = async () => {
        try{
            const latitude = product.value.user.latitude;
            const longitude = product.value.user.longitude;

            const respuesta = await axios.get('/api/geoLocation', {
                params: {latitude, longitude}
            });
            fullAddress.value = respuesta.data || {};
            console.log('FULLADDRESS -->', fullAddress);

        }catch(err){
            console.log('Falla en API: ', err);
        }
    };


    function getImage(media) {
        // Si no hay media, devolver array vacío
        if (!media) return [];
        
        // Si media ya es un array y tiene la estructura correcta, devolverlo directamente
        if (Array.isArray(media) && media[0]?.original_url) {
            return media;
        }

        // Si es un objeto con resized_image (formato antiguo)
        if (media.resized_image) {
            return Object.values(media.resized_image);
        }

        // Si es un objeto plano, convertirlo a array
        return Object.values(media);
    }

    const isYourOwnProduct = (productId) => {
        return auth.user && auth.user.id === productId;
    }
  
    const handleChatCreation = async () => {
        try {
            if (!auth.user) {
            // Redirigeix a login o mostra un missatge d'error si l'usuari no està autenticat
            console.error("L'usuari ha d'estar autenticat per utilitzar el xat");
            return;
            }

            const chatData = await chatExists(
                product.value.id,
                auth.user.id,
                product.value.user.id
            )
            console.log("✅ Xat verificat o creat:", chatData);

            await router.push({
            path: '/chat',
            query: {
                chatData: JSON.stringify(chatData)
            }
            });

        } catch (error) {
            console.error("❌ Error al verificar o crear el xat:", error);
        }
    }

    const getUserProducts = async (userId) => {
        try {
            const response = await axios.get(`/api/get-user-products/${userId}`);
            userProducts.value = response.data;
            console.log('USER PRODUCTS:', response.data);
        } catch (error) {
            console.error('Error fetching user products:', error);
        }
    };
      

    return {
        path,
        segments,
        id,
        product,
        relatedPost,
        images,
        address,
        fullAddress,
        position,
        compradorId,
        auth,
        chatData,
        router,
        home,
        breadcrumbs,
        products,
        responsiveOptions,
        responsiveOptions2,
        userProducts,
        relatedProducts,
        getProduct,
        getRelatedProducts,
        getGeoLocation,
        getImage,
        isYourOwnProduct,
        handleChatCreation,
        getUserProducts,
    }
}