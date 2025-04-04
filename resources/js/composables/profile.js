import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'
import useAuth from '@/composables/auth';

export default function useProfile() {
    const { auth, logout} = useAuth();
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

    const mediaRating = ref(0);
    const activeProducts = ref([]);
    const purchases = ref([]);
    const sales = ref([]);
    const reviews = ref([]);
    const loading = ref(false);
    const error = ref(null);
    const user = ref({});
    const selectedTab = ref(null);
    const fullAddress = ref(null);
    const path = window.location.pathname; // obtiene url
    const segments = path.split('/');
    const id = segments.pop();  // obtiene ultimo elemento (id)



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
     
  const fetchProducts = async (endpoint, id, type) => {
    loading.value = true;
    error.value = null;
    selectedTab.value = type; // guardamos que se esta viendo
  
    try {
      const response = await axios.post(`/api/${endpoint}`, {
        userId: id,
      });
      
      // guarda info segun seccion
      if (type === 'purchases') purchases.value = response.data.data || [];
      else if (type === 'sales') sales.value = response.data.data || [];
      else if (type === 'activeProducts') activeProducts.value = response.data.data || [];
      else if (type === 'reviews') {reviews.value = response.data.data || []; calcularMediaRating();};
      console.log('purchase -->', purchases);
      console.log('sales -->', sales);
      console.log('activeProducts -->', activeProducts);
      console.log('reviews -->', reviews);
  
    } catch (err) {
      error.value = "Error al obtener los datos.";
    } finally {
      loading.value = false;
    }
  };
  
  const getDataProfile = async () => {
    try {
      const respuesta = await axios.get('/api/profile/'+id);
      user.value = respuesta.data.data || {};
      console.log('user --->', user);
      console.log(user.value.id);
    } catch (error) {
      console.error("Error al obtener usuario:", error);
    }
  };
  const calcularMediaRating = () => {
    if (reviews.value.length === 0) {
      mediaRating.value = 0;
      return;
    }
    const total = reviews.value.reduce((sum, review) => sum + review.calification, 0);
    mediaRating.value = total / reviews.value.length;
  };
    const getGeoLocation = async () => {
        try{
            const latitude = user.value.latitude;
            const longitude = user.value.longitude;

            const respuesta = await axios.get('/api/geoLocation', {
                params: {latitude, longitude}
            });
            fullAddress.value = respuesta.data || {};
            console.log('FULLADDRESS -->', fullAddress);

        }catch(err){
            console.log('Falla en API: ', err);
        }
    };
    return {
        profile,
        imgProfile,
        validationErrors,
        isLoading,
        mediaRating,
        activeProducts,
        purchases,
        loading,
        error,
        user,
        selectedTab,
        fullAddress,
        logout,
        path,
        segments,
        id,
        reviews,
        sales,
        getProfile,
        updateProfile,
        updateImgProfile,
        fetchProducts,
        getDataProfile,
        calcularMediaRating,
        getGeoLocation,
    }
}
