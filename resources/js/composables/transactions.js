
import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'

export default function useTransactions() {

    const transactions = ref([])
    const transaction = ref({
        name: ''
    })

    const router = useRouter()
    const validationErrors = ref({})
    const isLoading = ref(false)
    const swal = inject('$swal')

    const getTransactions = async (
        page = 1,
        search_id = '',
        search_title = '',
        search_global = '',
        order_column = 'created_at',
        order_direction = 'desc'
    ) => {
        axios.get('/api/transactions?page=' + page +
            '&search_id=' + search_id +
            '&search_title=' + search_title +
            '&search_global=' + search_global +
            '&order_column=' + order_column +
            '&order_direction=' + order_direction)
            .then(response => {
                transactions.value = response.data;
            })
    }

    return {
        transactions,
        transaction,
        getTransactions,
    }
}