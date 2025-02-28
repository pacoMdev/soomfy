<template>
  <div class="user-avatar">
    <FileUpload
      name="picture"
      url="/api/profile/updateimg"
      @before-upload="onBeforeUpload"
      @upload="onTemplatedUpload($event)"
      accept="image/*"
      :maxFileSize="1500000"
      @select="onSelectedFiles"
      pt:content:class="fu-content"
      pt:buttonbar:class="fu-header"
      pt:root:class="fu"
      class="fu">

    <!-- Template para los botones -->
    <template #header="{ chooseCallback, uploadCallback, clearCallback, files, uploadedFiles }">
      <div class="flex gap-2">
        <Button @click="chooseCallback()" icon="pi pi-images" rounded outlined></Button>
        <Button @click="uploadEvent(uploadCallback, uploadedFiles)"
                icon="pi pi-cloud-upload"
                rounded outlined
                severity="success"
                :disabled="!files || files.length === 0">
        </Button>
        <Button @click="clearCallback()"
                icon="pi pi-times"
                rounded outlined
                severity="danger"
                :disabled="!files || files.length === 0">
        </Button>
      </div>
    </template>

    <!-- Añade este template para el contenido -->
    <template #content="{ files, uploadedFiles, removeUploadedFileCallback, removeFileCallback }">
      <img v-if="files.length > 0"
           v-for="(file, index) of files"
           :key="file.name + file.type + file.size"
           role="presentation"
           :alt="file.name"
           :src="file.objectURL"
           class="object-fit-cover w-100 h-100 img-profile" />
      <div v-else>
        <img v-if="uploadedFiles.length > 0"
             :key="uploadedFiles[uploadedFiles.length-1].name + uploadedFiles[uploadedFiles.length-1].type + uploadedFiles[uploadedFiles.length-1].size"
             role="presentation"
             :alt="uploadedFiles[uploadedFiles.length-1].name"
             :src="uploadedFiles[uploadedFiles.length-1].objectURL"
             class="object-fit-cover w-100 h-100 img-profile" />
      </div>
    </template>

    <!-- Template para mostrar la imagen -->
    <template #empty>
      <img v-if="profile.avatar" :src="profile.avatar" alt="Avatar" class="object-fit-cover w-100 h-100 img-profile">
      <img v-else src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Avatar Default" class="object-fit-cover w-100 h-100 img-profile">
    </template>
  </FileUpload>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import useProfile from '@/composables/profile';
import { useToast } from 'primevue/usetoast';
import FileUpload from 'primevue/fileupload';
import Button from 'primevue/button';

const toast = useToast();
const { profile, updateProfile, validationErrors, isLoading } = useProfile();

// Variables para el manejo de archivos
const totalSize = ref(0);
const totalSizePercent = ref(0);
const files = ref([]);

// Funciones para el manejo de la subida
const onBeforeUpload = (event) => {
  event.formData.append('id', profile.id);
};

const onSelectedFiles = (event) => {
  files.value = event.files;
  if (event.files.length > 1) {
    event.files = event.files.splice(0, 1);
  }
  totalSize.value = parseInt(formatSize(files.value[0].size));
};

const formatSize = (bytes) => {
  const k = 1024;
  const dm = 3;
  const sizes = $primevue.config.locale.fileSizeTypes;

  if (bytes === 0) {
    return `0 ${sizes[0]}`;
  }

  const i = Math.floor(Math.log(bytes) / Math.log(k));
  const formattedSize = parseFloat((bytes / Math.pow(k, i)).toFixed(dm));

  return `${formattedSize} ${sizes[i]}`;
};
const uploadEvent = async (callback, uploadedFiles) => {
  try {
    totalSizePercent.value = totalSize.value / 10;
    await callback();
    console.log('Subida completada');
  } catch (error) {
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'Error al subir la imagen',
      life: 3000
    });
  }
};

const onTemplatedUpload = (event) => {
  if (event.xhr.status === 200) {
    toast.add({
      severity: 'success',
      summary: 'Éxito',
      detail: 'Imagen de perfil actualizada',
      life: 3000
    });
  }
};
</script>

<style scoped>
.user-avatar {
  width: 100%;
  max-width: 300px;
  margin: 0 auto;
  position: relative;
}
.fu-content {
  padding: 0px !important;
  border: 0px !important;
  border-radius: 6px;
}

.fu-header {
  border: 0px !important;
  border-radius: 6px;
}

.fu {
  display: flex;
  flex-direction: column-reverse;
  border-radius: 6px;
  border: 1px solid #e2e8f0;
}


.img-profile {
  border-top-right-radius: 6px;
  border-top-left-radius: 6px;
  aspect-ratio: 1/1;
}

.form-group {
  margin-bottom: 1rem;
}

label {
  margin-bottom: 0.3rem;
}
</style>