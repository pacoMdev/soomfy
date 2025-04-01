
import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'

export default function useOpinion() {

    const opinions = ref([])
    const opinion = ref({
        title: '',
    })

    const router = useRouter()
    const validationErrors = ref({})
    const isLoading = ref(false)
    const swal = inject('$swal')

    const getOpinion = async (
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
    const storeOpinion = async (transaction) => {
        if (isLoading.value) return;

        isLoading.value = true
        validationErrors.value = {}

        let serializedTransaction = new FormData()
        for (let item in user) {
            console.log('dataOpitinon -->', item);
            if (user.hasOwnProperty(item)) {
                serializedTransaction.append(item, transaction[item])
            }
        }

        axios.post('/api/opinions', serializedTransaction)
            .then(response => {
                router.push({name: 'transactions.index'})
                swal({
                    icon: 'success',
                    title: 'Transaction saved successfully'
                })
            })
            .catch(error => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => isLoading.value = false)
    }

    const deleteOpinion = async (id, index) => {
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
                    axios.delete('/api/opinions/' + id)
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

    return {
        transactions,
        transaction,
        getOpinions,
        storeOpinion,
        deleteOpinion,
        validationErrors
    }
}