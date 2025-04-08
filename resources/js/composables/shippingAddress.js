
import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'
import { useRoute } from 'vue-router';

export default function useShippingAddress() {

    const stablishments = ref([]);
    const stablishment = [
        
    ];
    const cpCercano = ref('');

    const router = useRouter()
    const swal = inject('$swal')
    const route = useRoute();

    const getDistributionsCenters = async () => {
        try {
          const response = await axios.get('/api/getDistributionsCenters')
          .then(response => {
            stablishments.value = response.data.data;
        console.log('Centros de distribucion', stablishments.value);
          })
        } catch (err) {
          error.value = "Error al obtener los datos.";
        }
      };

    const getProximityCenters = async () => {
        try{
            const response = await axios.get('/api/getProximityCenters', {
                cpCercano: cpCercano.value,
            });
            stablishments.value = response.data;
            console.log('Centros de proximidad', response.data);
        }catch (err){
            error.value = "Error al obtener los datos.";
        }
    }

    //   obtener las direcciones mas cercanas introducidas





    return {
        router,
        swal,
        route,
        stablishments,
        stablishment,
        cpCercano,
        getDistributionsCenters,
        getProximityCenters,
    }
}