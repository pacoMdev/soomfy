import axios from 'axios';
import { computed, ref} from "vue";
import { defineStore } from "pinia";
import swal from 'sweetalert';


export const authStore = defineStore("authStore", () => {

    let user = ref(null);
    let authenticated = ref(false);

    // Si es admin
    const isAdmin = computed(() => {
        if (!user.value?.roles) return false;
        return user.value.roles.some(role => role.name === 'admin');

    });

    async function login(data) {
        axios.get('/api/user').then(response => {
            user.value = response.data.data
            authenticated.value = true
        }).catch(error => {
            user.value = {}
            authenticated.value = false
        })
    }

    function checkAuthentication() {
        if (!authenticated.value) {
            // Mostrar SweetAlert
            swal({
                icon: "warning",
                title: "Debe iniciar sesión",
                text: "Por favor, inicie sesión para continuar.",
                button: "Ir al login", // El texto del botón
            }).then(() => {  // Aquí completamos el callback
                router.push("/login"); // Redirige después de que el swal termine
            });

            return false; // Indica que el usuario no está autenticado
        }

        return true; // Si el usuario está autenticado
    }


    async function getUser() {
        console.log('😅 GOOGLE_USER');
        try {
            const response = await axios.get('/api/user'); // Llamada a la API
            console.log('response', response);
            if (response.data?.data) {
                console.log('USUARIO: ', response.data.data); // Asegúrate de que estos datos existen
                user.value = response.data.data; // Asigna el usuario al estado
                authenticated.value = true; // Marca como autenticado
            } else {
                console.error('El valor recibido para el usuario es inválido', response.data);
                user.value = null; // Reinicia si no hay datos válidos
                authenticated.value = false;
            }
        } catch (error) {
            console.error('Error al obtener el usuario:', error);
            user.value = null;
            authenticated.value = false; // Reinicia en caso de error
        }

        console.log('Estado actual del usuario:', user.value); // Depuración adicional
    }
    function logout() {
        user.value = {}
        authenticated.value = false
    }

    return { user, authenticated, login, getUser, logout, isAdmin, checkAuthentication};
}, {persist: true});
