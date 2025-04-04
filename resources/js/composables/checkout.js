
import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'
import { useRoute } from 'vue-router';

export default function useCheckout() {

    const checkout = ref([]);
    const address = [
        'newAddress',
        'newCity',
        'newCp',
        'newCountry'
    ];

    const router = useRouter()
    const swal = inject('$swal')
    const route = useRoute();

    const productId = route.query.productId;
    const userProduct = ref({});
    const selectedMethod = ref('1');
    const showDialog = ref(false);
    const newAddress = ref('');
    const newCity = ref('');
    const newCp = ref('');
    const newCountry = ref('');
    const error = ref(false);
    const typeShippment = ref([ 'EXPRESS', 'STANDARD' ]);






    const getUserProduct = async () => {
        try {
            const respuesta = await axios.get('/api/products/'+productId);
            userProduct.value[0] = respuesta.data.data || {};
            console.log('UserProduct -->', userProduct);
        } catch (error) {
            console.error("Error al valorar -->", error);
        }
    };
    const submitPurchaseForm = async () => {
        // check direccion de envio
        if (selectedMethod.value === '1'){
            if (!newAddress.value || !newCity.value || !newCp.value || !newCountry.value){
                error.value = true;
            return;
            }
        }
        // check ok
        error.value = false;
        try {
            const respuesta = await axios.post('/api/fakePurchaseProduct', {
                product_id: parseInt(productId),
                userSeller_id: userProduct.value[0].user.id,
                price: userProduct.value[0].price,
                isToSend: parseInt(selectedMethod.value),
                shippingAddress: {
                    'newAddress': newAddress.value,
                    'newCity': newCity.value,
                    'newCp': newCp.value,
                    'newCountry': newCountry.value
                }
            }).then( async response =>{
                console.log('Producto comprado', response);
                console.log('👌 Status:', response.status);
                if(response.status == 200){    // compra realizada con exito
                    swal({
                        icon: 'success',
                        title: 'Compra realizada',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    router.push({ name: 'home' });
                }
            });
        } catch (error) {
            console.error("Error al valorar -->", error);
        }
    };
    const saveAddress = () => {
        showDialog.value = false;
    };




    return {
        checkout,
        address,
        productId,
        userProduct,
        selectedMethod,
        showDialog,
        newAddress,
        newCity,
        newCp,
        newCountry,
        error,
        typeShippment,
        getUserProduct,
        submitPurchaseForm,
        saveAddress,
    }
}