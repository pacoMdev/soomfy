
import { ref } from 'vue'
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
    const home = ref({
        icon: 'pi pi-home', route: '/'
    });
    const breadcrumbs = ref([
        { label: 'Products', route: '/' }, 
        { label: 'product.title' }, 
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
        const respuesta = await axios.get('/api/products');
        relatedPost.value = respuesta.data;
        console.log('RELATED PRODUCTS:', respuesta.data);
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


    function getImage(resized_image){
        return Object.values(resized_image || {});
    }

    const isYourOwnProduct = (productId) => {
        return productId === auth.user.id;
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
        getProduct,
        getRelatedProducts,
        getGeoLocation,
        getImage,
        isYourOwnProduct,
        handleChatCreation,
    }
}