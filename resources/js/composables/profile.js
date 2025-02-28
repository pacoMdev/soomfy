import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'

export default function useProfile() {
    const profile = ref({
        name: '',
        surname1: '',
        surname2: '',
        email: '',
        thumbnail: ''
    })

    const imgProfile = ref({
        thumbnail: ''
    })

    const router = useRouter()
    const validationErrors = ref({})
    const isLoading = ref(false)
    const swal = inject('$swal')

    const getProfile = async () => {
        profile.value = auth.user.value;
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
    const updateImgProfile = async (profile) => {
        if (isLoading.value) return;

        isLoading.value = true
        validationErrors.value = {}
        // Crear formData para enviar la imagen
        const formData = new FormData();
        formData.append('thumbnail', imgProfile.value.thumbnail);

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
