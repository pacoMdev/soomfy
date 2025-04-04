
import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'

export default function useEstados() {

    const estados = ref([])
    const estado = ref({
        name: '',
    })

    const router = useRouter()
    const validationErrors = ref({})
    const isLoading = ref(false)
    const swal = inject('$swal')

    const getEstados = async (
        page = 1,
        search_id = '',
        search_title = '',
        search_global = '',
        order_column = 'id',
        order_direction = 'desc'
    ) => {
        axios.get(
            '/api/estados?page=' + page +
            '&search_id=' + search_id +
            '&search_title=' + search_title +
            '&search_global=' + search_global +
            '&order_column=' + order_column +
            '&order_direction=' + order_direction
        ).then(response => {
                estados.value = response.data;
            })
    }
    const storeEstado = async (estado) => {
        console.log('inside the storeEstado')
        if (isLoading.value) return;

        isLoading.value = true
        validationErrors.value = {}

        let serializaedEstado = new FormData()
        for (let item in user) {
            console.log('dataEstado -->', item);
            if (user.hasOwnProperty(item)) {
                serializedTransaction.append(item, transaction[item])
            }
        }

        axios.post('/api/estado', serializedTransaction)
            .then(response => {
                router.push({name: 'state.index'})
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

    const deleteEstado = async (id, index) => {
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
                    axios.delete('/api/estado/' + id)
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
        estados,
        estado,
        getEstados,
        storeEstado,
        deleteEstado,
        validationErrors
    }
}