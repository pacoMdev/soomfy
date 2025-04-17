
import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'

export default function useTransactions() {

    const transactions = ref([])
    const transaction = ref({
        id: '',
        userBuyer_id: '',
        userSeller_id: '',
        product_id: '',
        initialPrice: '',
        finalPrice: '',
        isToSend: '',
        isRegated: '',
        isRegated: '',
    })

    const router = useRouter()
    const validationErrors = ref({})
    const isLoading = ref(false)
    const swal = inject('$swal')
    const historicMove = ref();

    const getTransactions = async (
        page = 1,
        search_id = '',
        search_title = '',
        search_global = '',
        order_column = 'created_at',
        order_direction = 'desc'
    ) => {
        axios.get(
            '/api/transactions?page=' + page +
            '&search_id=' + search_id +
            '&search_title=' + search_title +
            '&search_global=' + search_global +
            '&order_column=' + order_column +
            '&order_direction=' + order_direction
        ).then(response => {
                transactions.value = response.data;
            })
    }

    const deleteTransactions = async (id, index) => {
        swal({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this action!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            confirmButtonColor: '#ef4444',
            timer: 20000,
            timerProgressBar: true,
            reverseButtons: true
        })
            .then(result => {
                if (result.isConfirmed) {
                    axios.delete('/api/transactions/' + id)
                        .then(response => {
                            users.value.data.splice(index, 1);

                            //getUsers()
                            //router.push({name: 'users.index'})
                            swal({
                                icon: 'success',
                                title: 'Transaction deleted successfully'
                            })
                        })
                        .catch(error => {
                            swal({
                                icon: 'error',
                                title: 'Something went wrong'
                            })
                        })
                }
            })
    }
    const historicMovements = async (trakingNumber) => {
        try {
          await axios.post('/api/historicMovements', {
            trakingNumber: trakingNumber,
          })
          .then(response => {
            historicMove.value = response.data;
        console.log('📦 Historial de movimiento -->', historicMove.value);
          })
        } catch (err) {
          error.value = "Error al obtener los datos.";
        }
      };

    return {
        transactions,
        transaction,
        getTransactions,
        deleteTransactions,
        historicMove,
        historicMovements,
    }
}