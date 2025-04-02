import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'

export default function useUsers() {
    const users = ref([])
    const user = ref({
        name: ''
    })

    const router = useRouter()
    const validationErrors = ref({})
    const isLoading = ref(false)
    const swal = inject('$swal')
    const favoritos = ref([]);


    const getUsers = async (
        page = 1,
        search_id = '',
        search_title = '',
        search_global = '',
        order_column = 'created_at',
        order_direction = 'desc'
    ) => {
        axios.get('/api/users?page=' + page +
            '&search_id=' + search_id +
            '&search_title=' + search_title +
            '&search_global=' + search_global +
            '&order_column=' + order_column +
            '&order_direction=' + order_direction)
            .then(response => {
                users.value = response.data;
            })
    }

    const getUsersWithTasks = async () => {
        axios.get('/api/userswithtasks')
            .then(response => {
                users.value = response.data;
            })
    }

    const getUser = async (id) => {
        try {
            const response = await axios.get('/api/users/' + id);
            user.value = response.data.data;
            return user.value;
        } catch (error) {
            console.error("Error al obtener el producto", error);
            return null;  // En caso de error, retornamos `null` o un valor predeterminado
        }
    }

    const createUserDB = async (id) => {
        return axios.put('/api/users/db/create/' + id);
    }

    const deleteUserDB = async (id) => {
        return axios.put('/api/users/db/delete/' + id);
    }

    const changeUserPasswordDB = async (id) => {
        return axios.put('/api/users/db/password/' + id);
    }

    const createUserProceduredDB = async (id) => {
        return axios.put('/api/users/db/procedure/' + id);
    }
    const storeUser = async (user) => {
        if (isLoading.value) return;

        isLoading.value = true
        validationErrors.value = {}

        let serializedPost = new FormData()
        for (let item in user) {
            if (user.hasOwnProperty(item)) {
                serializedPost.append(item, user[item])
            }
        }

        axios.post('/api/users', serializedPost)
            .then(response => {
                router.push({name: 'users.index'})
                swal({
                    icon: 'success',
                    title: 'User saved successfully'
                })
            })
            .catch(error => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => isLoading.value = false)
    }

    const updateUser = async (user) => {

        console.log('abcabc abc abc', user);
        if (isLoading.value) return;

        isLoading.value = true
        validationErrors.value = {}
        axios.put('/api/users/' + user.id, user)
            .then(response => {
                //router.push({name: 'users.index'})

                swal({
                    icon: 'success',
                    title: 'User updated successfully'
                })
            })
            .catch(error => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => isLoading.value = false)
    }

    const deleteUser = async (id, index) => {
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
                    axios.delete('/api/users/' + id)
                        .then(response => {
                            users.value.data.splice(index, 1);

                            //getUsers()
                            //router.push({name: 'users.index'})
                            swal({
                                icon: 'success',
                                title: 'User deleted successfully'
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
    const obtenerFavoritos = async () => {
        try {
          const respuesta = await axios.get('/api/get-favorite-products');
          favoritos.value = respuesta.data.data;
        } catch (error) {
          console.error("Error al obtener favoritos:", error);
        }
      }

    return {
        users,
        user,
        validationErrors,
        isLoading,
        favoritos,
        getUsers,
        getUsersWithTasks,
        getUser,
        createUserDB,
        deleteUserDB,
        changeUserPasswordDB,
        createUserProceduredDB,
        storeUser,
        updateUser,
        deleteUser,
        obtenerFavoritos,
    }
}


