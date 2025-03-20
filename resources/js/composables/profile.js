import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'
import useAuth from '@/composables/auth';

export default function useProfile() {
    const auth = useAuth();
    const profile = ref({
        name: '',
        surname1: '',
        surname2: '',
        email: '',
        thumbnail: ''
    })

    const imgProfile = ref({
        avatar: null
    })

    const router = useRouter()
    const validationErrors = ref({})
    const isLoading = ref(false)
    const swal = inject('$swal')

    const getProfile = async () => {
        isLoading.value = true;
        try {
            const response = await axios.get('/api/profile');
            imgProfile.value = response.data.data;
            return response.data;
        } catch (e) {
            error.value = 'Error al obtener el perfil';
            throw e;
        } finally {
            isLoading.value = false;
        }

    }

    const updateProfile = async (profile) => {
        if (isLoading.value) return;

        isLoading.value = true
        validationErrors.value = {}

        axios.put('/api/user', profile)
            .then(({data}) => {
                if (data.success) {
                    auth.user.value=data.data
                    swal({
                        icon: 'success',
                        title: 'Profile updated successfully'
                    })
                }
            })
            .catch(error => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => isLoading.value = false)
    }
    const updateImgProfile = async () => {
        if (isLoading.value) return;

        isLoading.value = true
        validationErrors.value = {}
        // Crear formData para enviar la imagen
        const formData = new FormData();
        formData.append('avatar', imgProfile.value.avatar);

        axios.post('/api/profile/updateimg', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(({imagen}) => {
                if (imagen.success) {
                    auth.user.value.avatar=imagen.data.data.thumbnail
                    swal({
                        icon: 'success',
                        title: 'Profile updated successfully'
                    })
                }
            })
            .catch(error => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => isLoading.value = false)
    }
    return {
        profile,
        imgProfile,
        getProfile,
        updateProfile,
        updateImgProfile,
        validationErrors,
        isLoading
    }
}
