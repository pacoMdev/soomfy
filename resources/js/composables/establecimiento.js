
import { ref, inject, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { number, string } from 'yup'

export default function useEstablecimiento() {

    const establecimientos = ref([])
    const establecimientoN = reactive({

    })

    const router = useRouter()
    const validationErrors = ref({})
    const isLoading = ref(false)
    const swal = inject('$swal')

    const getEstablecimientos = async (
        page = 1,
        search_id = '',
        search_title = '',
        search_global = '',
        order_column = 'id',
        order_direction = 'desc'
    ) => {
        axios.get(
            '/api/shippingAddress?page=' + page +
            '&search_id=' + search_id +
            '&search_title=' + search_title +
            '&search_global=' + search_global +
            '&order_column=' + order_column +
            '&order_direction=' + order_direction
        ).then(response => {
                establecimientos.value = response.data;
            })
    }
    const storeEstablecimiento = async (establecimiento) => {
        if (isLoading.value) return;

        isLoading.value = true
        validationErrors.value = {}

        let serializedEstablecimiento = new FormData()
        for (let item in establecimiento) {
            console.log('dataEstablecimiento -->', item);
            if (establecimiento.hasOwnProperty(item)) {
                serializedEstablecimiento.append(item, establecimiento[item])
            }
        }

        axios.post('/api/shippingAddress', serializedEstablecimiento)
            .then(response => {
                router.push({name: 'home'})
                swal({
                    icon: 'success',
                    title: 'Establecimiento saved successfully',
                    timer: 1500,
                })
            })
            .catch(error => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => isLoading.value = false)
    }

    const deleteEstablecimiento = async (id, index) => {
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
                    axios.delete('/api/establecimiento/' + id)
                        .then(response => {
                            users.value.data.splice(index, 1);

                            //getUsers()
                            //router.push({name: 'users.index'})
                            swal({
                                icon: 'success',
                                title: 'Establecimiento deleted successfully'
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
        establecimientos,
        establecimientoN,
        getEstablecimientos,
        storeEstablecimiento,
        deleteEstablecimiento,
        validationErrors,
        isLoading,
    }
}