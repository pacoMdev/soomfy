
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
    const showSelectCenter = ref(false);
    const newAddress = ref('');
    const newCity = ref('');
    const newCp = ref('');
    const newCountry = ref('');
    const error = ref(false);
    const errorMessage = ref('ERROR');
    const selectedStablishment = ref(undefined);
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
            if (!address.newAddress || !address.newCity || !address.newCp || !address.newCountry){
                error.value = true;
                errorMessage.value = 'Por favor, completa los campos de la dirección de envío';
            return;
            }
        }else if (selectedMethod.value ==='2'){
            if (selectedStablishment.value == undefined){
                error.value = true;
                errorMessage.value = 'Por favor selecciona un centro de recogida';
                return;
            }
        }
        // check ok
        error.value = false;
        errorMessage.value = '';
        try {
            // await axios.post('/api/fakePurchaseProduct', {
            //     product_id: parseInt(productId),
            //     userSeller_id: userProduct.value[0].user.id,
            //     price: userProduct.value[0].price,
            //     isToSend: parseInt(selectedMethod.value),
            //     shippingAddress: {
            //         'newAddress': address.newAddress,
            //         'newCity': address.newCity,
            //         'newCp': address.newCp,
            //         'newCountry': address.newCountry
            //     },
            //     selectedStablishment: { ...selectedStablishment.value },
            //     selectedMethod: selectedMethod.value,
            // })
            await axios.post('/api/checkout')
            .then( async response =>{
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
    const saveShippingAddress = () => {
        console.log('saveShippingAddress');
        showSelectCenter.value = false;

    }




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
        showSelectCenter,
        errorMessage,
        selectedStablishment,
        getUserProduct,
        submitPurchaseForm,
        saveAddress,
        saveShippingAddress,
    }
}