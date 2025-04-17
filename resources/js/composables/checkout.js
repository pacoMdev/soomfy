
import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'
import { useRoute } from 'vue-router';
import { loadStripe } from '@stripe/stripe-js';
import { usePurchaseStore } from '@/store/purchaseStore';



const purchaseStore = usePurchaseStore();


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
        if (selectedMethod.value == 0){
            swal({
                icon: 'success',
                title: 'Mensaje enviado',
                showConfirmButton: false,
                timer: 1500,
            });
            
            router.push({ name: 'home' });
        }else{
            try {
                // guarda en storage de pinia con persist
                purchaseStore.setPurchase({
                    product_id: parseInt(productId),
                    userSeller_id: userProduct.value[0]?.user?.id ?? null,
                    price: userProduct.value[0]?.price ?? 0,
                    isToSend: parseInt(selectedMethod.value),
                    shippingAddress: {
                      newAddress: newAddress.value,
                      newCity: newCity.value,
                      newCp: newCp.value,
                      newCountry: newCountry.value
                    },
                    selectedStablishment: selectedStablishment.value ? { ...selectedStablishment.value } : null,
                    selectedMethod: selectedMethod.value
                  });
                // guarda en localStorage
                localStorage.setItem('purchaseData', JSON.stringify(purchaseStore.purchaseData));
                console.log('🔎 purchase_data', purchaseStore.purchaseData);
    
                const stripe = await loadStripe(import.meta.env.VITE_STRIPE_PUBLIC_KEY);
                const response = await fetch('/api/create-checkout-session', {
                  method: 'POST',
                  headers: {
                    'Content-Type': 'application/json',
                  },
                  body: JSON.stringify({ 
                    title: userProduct.value[0].title,
                    image: userProduct.value[0].original_image,
                    amount: userProduct.value[0].price,
                    product_id: parseInt(productId)
                 }),
                });
              
                const session = await response.json();
                // Redirigir a Stripe al final, cuando ya se guardó la compra
                await stripe.redirectToCheckout({ sessionId: session.id });
            } catch (error) {
            console.error('Error en el proceso de compra:', error);
            swal({
                icon: 'error',
                title: 'Error en el pago',
                text: 'Por favor, intenta nuevamente.',
            });
            }
        }
    };
    const saveAddress = () => {
        showDialog.value = false;
    };
    const saveShippingAddress = () => {
        console.log('saveShippingAddress');
        showSelectCenter.value = false;

    }
    const registerTransaction = async () => {
        const sessionId = route.query.session_id;

        // datos guardados de store por pinia
        const data = purchaseStore.purchaseData;
        // recupera de localStorage y guarda en purchaseStore
        const saved = localStorage.getItem('purchaseData');
        if (saved && !purchaseStore.purchaseData) {
            purchaseStore.setPurchase(JSON.parse(saved));
        }


        // Registrar la compra en tu backend
        console.log('🔎 data', purchaseStore.purchaseData);
        try{
            const purchaseResponse = await axios.post('/api/fakePurchaseProduct', {
                purchaseData: purchaseStore.purchaseData,
                sessionId: sessionId,
            })
            .then(res => {
                console.log('Producto comprado', res.data);
                console.log('👌 Status:', res.status);
                
                router.push({ name: 'home' });
                swal({
                    icon: 'success',
                    title: 'Compra realizada',
                    showConfirmButton: false,
                    timer: 1500,
                }); 
                
            });
        }catch(error){
            console.error('Error en el proceso de compra:', error);
            swal({
                icon: 'error',
                title: 'Error en la transaccion',
                text: 'Por favor, intenta nuevamente.',
            });
        }
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
        showSelectCenter,
        errorMessage,
        selectedStablishment,
        getUserProduct,
        submitPurchaseForm,
        saveAddress,
        saveShippingAddress,
        registerTransaction,
    }
}