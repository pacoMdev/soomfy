<template>
  <div class="dropzone-container" @dragover="dragover" @dragleave="dragleave" @drop="drop">
    <input
        type="file"
        name="file"
        id="fileInput"
        class="hidden-input"
        @change="onChange"
        ref="refFiles"
        accept=".gif,.webp,.jpg,.jpeg,.png"
    />

    <div class="file-label text-center" v-if="shouldShowPreview">
      <div class="preview-card">
        <div>
          <img
              class="preview-img"
              :src="currentImageSrc"
              :alt="previewAltText"
          />
          <p v-if="thumbnail?.name" :title="thumbnail.name">
            {{ makeName(thumbnail.name) }}
          </p>
        </div>
        <div>
          <a
              href="javascript:void(0)"
              class="ml-2"
              type="button"
              @click="remove"
              title="Eliminar archivo"
          >
            <b>&times;</b>
          </a>
        </div>
      </div>
    </div>

    <label v-else for="fileInput" class="file-label text-center">
      <svg
          xmlns="http://www.w3.org/2000/svg"
          width="50"
          height="50"
          fill="currentColor"
          class="bi bi-image"
          viewBox="0 0 16 16"
      >
        <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
        <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
      </svg>
      <div v-if="isDragging">Suelta los archivos aquí</div>
      <div v-else>Arrastra archivos aquí o <u>haz clic aquí</u> para subir</div>
    </label>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";

const props = defineProps({
  modelValue: String,
  profileImage: {
    type: String,
    default: null
  }
});

const emit = defineEmits(['update:modelValue']);

const thumbnail = ref(null);
const isDragging = ref(false);
const refFiles = ref(null);
const previewImage = ref('');

// Computed properties
const shouldShowPreview = computed(() =>
    thumbnail.value || props.modelValue || props.profileImage
);

const currentImageSrc = computed(() =>
    props.profileImage || previewImage.value || props.modelValue
);

const previewAltText = computed(() =>
    thumbnail.value?.name || 'Vista previa de imagen'
);

// Methods
const onChange = () => {
  if (refFiles.value.files && refFiles.value.files[0]) {
    thumbnail.value = refFiles.value.files[0];
    previewImage.value = URL.createObjectURL(refFiles.value.files[0]);
    emit('update:modelValue', thumbnail.value);
  }
};

const makeName = (name) => {
  if (!name) return '';
  const nameParts = name.split('.');
  return `${nameParts[0].substring(0, 3)}...${nameParts[nameParts.length - 1]}`;
};

const remove = () => {
  thumbnail.value = null;
  previewImage.value = '';
  if (refFiles.value) {
    refFiles.value.value = '';
  }
  emit('update:modelValue', null);
};

const dragover = (e) => {
  e.preventDefault();
  isDragging.value = true;
};

const dragleave = () => {
  isDragging.value = false;
};

const drop = (e) => {
  e.preventDefault();
  if (e.dataTransfer.files.length) {
    refFiles.value.files = e.dataTransfer.files;
    onChange();
  }
  isDragging.value = false;
};
</script>

<style scoped>
.dropzone-container {
  padding: 0;
  background: #f7fafc;
  border: 1px solid #e2e8f0;
  border-radius: 4px;
  transition: border-color 0.3s ease;
}

.dropzone-container:hover {
  border-color: #4a5568;
}

.hidden-input {
  opacity: 0;
  overflow: hidden;
  position: absolute;
  width: 1px;
  height: 1px;
}

.file-label {
  font-size: 1.25rem;
  display: block;
  cursor: pointer;
  padding: 2rem;
  transition: all 0.3s ease;
}

.file-label:hover {
  background-color: #edf2f7;
}

.preview-card {
  display: flex;
  align-items: center;
  justify-content: space-between;
  border: 1px solid #e2e8f0;
  padding: 0.5rem;
  margin: 0.5rem;
  border-radius: 4px;
  background-color: white;
}

.preview-img {
  height: 150px;
  width: 150px;
  object-fit: cover;
  border-radius: 4px;
  border: 1px solid #e2e8f0;
  background-color: #f7fafc;
}

.preview-card a {
  color: #e53e3e;
  text-decoration: none;
  font-size: 1.5rem;
  padding: 0.5rem;
  transition: color 0.3s ease;
}

.preview-card a:hover {
  color: #c53030;
}

p {
  margin: 0.5rem 0;
  font-size: 0.875rem;
  color: #4a5568;
}
</style>