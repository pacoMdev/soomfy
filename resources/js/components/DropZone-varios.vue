<template>
  <!-- Contenedor principal con row-cols para visualización horizontal -->
  <div class="row flex-nowrap overflow-auto py-2">
    <div
        v-for="(item, index) in thumbnails"
        :key="index"
        class="col-4 px-2"
    >
      <div 
        class="dropzone-container w-100"
        @dragover.prevent="(e) => handleDragover(e, index)"
        @dragleave="(e) => handleDragleave(e, index)"
        @drop="(e) => drop(e, index)"
        @mouseenter="() => hoverIndex = index"
        @mouseleave="() => hoverIndex = null"
      >
        <input
            type="file"
            class="hidden-input"
            @change="(e) => onChange(e, index)"
            :ref="`refFiles${index}`"
            accept=".webp, .png, .jpg, .heic, .avif"
        />
        <div class="file-label w-100 h-100" @click="() => triggerFileInput(index)">
          <!-- Si hay imagen -->
          <div v-if="item.img" class="preview-wrapper" :class="{ 'hover-effect': hoverIndex === index }">
            <img class="preview-img" :src="item.img" />
            <div v-if="hoverIndex === index" class="overlay">
              <i class="fas fa-edit edit-icon"></i>
            </div>
          </div>
          <!-- Si no hay imagen -->
          <div v-else class="preview-wrapper">
            <div class="overlay">
              <i class="fas fa-edit edit-icon"></i>
            </div>
            <div class="placeholder">
              <font-awesome-icon :icon="['fad', 'image']" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted} from "vue";

// Definimos las propiedades del componente
const props = defineProps({
  // La propiedad modelValue es un Array que se utiliza como valor para el binding
  // Se utiliza para recibir datos desde un componente padre y vincularlos al componente hijo, permitiendo actualizar el valor externo (utilizando v-model para la sincronización bidireccional).
  modelValue: {
    type: Array, // Tipo esperado: Array, ya que debe contener múltiples elementos (imágenes o datos relacionados).
    default: () => [] // Valor por defecto: un array vacío. Esto asegura que siempre haya una estructura inicial para trabajar dentro del componente.
  },
  maxImages: {  // Definimos un maximo de imagenes a subir
    type: Number,
    default: 3
  }
});

// Usar ref en Vue para referirse a los inputs dinámicos
const thumbnails = ref(
  Array(props.maxImages).fill(null).map((_,index) => ({
    img: "",
    file: null,
    id: null,
    order: index
  }))
);

const emit = defineEmits(['update:modelValue']);

// Watcher que sincroniza las imagenes recibidas desde el formulario Edit.vue hasta el Dropzone
// Rellenamos las imagenes
watch(
    () => props.modelValue,
    (newVal) => {
      const valores = Array.isArray(newVal) ? newVal : [];

      const filledThumbnails = Array(props.maxImages).fill(null).map((_, index) => {
        // Si ya existe un blob en thumbnails, lo mantenemos
        if (thumbnails.value[index]?.img?.startsWith('blob:')) {
          return {
            ...thumbnails.value[index],
            order: index

          }
        }

        // Si tenemos un valor existente en el modelValue
        if (valores[index]) {
          return {
            img: valores[index].img,
            file: valores[index].file,
            id: valores[index].id || null,
            order: index
          };
        }

        // Si no hay nada, retornamos un espacio vacío
        return {
          img: "",
          file: null,
          id: null,
          order: index
        };
      });

      thumbnails.value = filledThumbnails;
    },
    { immediate: true }  // Asegura que se ejecute inmediatamente
);

// Watcher para sincronizar datos desde este archivo a Create.vue
// Este watcher observa los cambios en la estructura de thumbnails.
// Su finalidad principal es detectar cualquier modificación en la propiedad `file` de los objetos
// que componen el array `thumbnails`.
watch(
    thumbnails,
    (archivosValidos) => {
      if (JSON.stringify(archivosValidos) !== JSON.stringify(props.modelValue)) {
        emit('update:modelValue', archivosValidos); // Emitir solo si hay diferencias
      }
    },
    {
      deep: true
    }
);

const hoverIndex = ref(null);
const isDragging = ref(Array(props.maxImages).fill(false));

// Función mejorada para manejar el dragover
const handleDragover = (e, index) => {
  e.preventDefault();
  e.dataTransfer.dropEffect = 'copy'; // Explícitamente establecer el efecto como copia
  isDragging.value[index] = true;
};

// Función mejorada para manejar el dragleave
const handleDragleave = (e, index) => {
  e.preventDefault();
  isDragging.value[index] = false;
};

// Función de drop actualizada
const drop = (e, index) => {
  e.preventDefault();
  isDragging.value[index] = false;
  const files = e.dataTransfer.files;
  if (files.length > 0) {
    const file = files[0];

    // Si hay una URL de blob anterior, la revocamos
    if(thumbnails.value[index]?.img?.startsWith('blob:')) {
      URL.revokeObjectURL(thumbnails.value[index].img);
    }

    if (thumbnails.value[index]?.img) {
      URL.revokeObjectURL(thumbnails.value[index].img);
    }

    thumbnails.value[index] = {
      img: URL.createObjectURL(file),
      file: file,
      id:  thumbnails.value[index]?.id || null,
      order: index
    };

    // Envia las imagenes al padre (Al formulario de creacion)
    emit("update:modelValue",thumbnails.value);
  }
};

// Manejar cambio de imagen desde el input
const onChange = (e, index) => {
  const file = e.target.files[0];
  if (file) {
    /// Revocar URL anterior si existe
    if (thumbnails.value[index]?.img?.startsWith('blob:')) {
      URL.revokeObjectURL(thumbnails.value[index].img);
    }

    if (thumbnails.value[index]?.img) {
      URL.revokeObjectURL(thumbnails.value[index].img);
    }

    thumbnails.value[index] = {
      img: URL.createObjectURL(file),
      file: file,
      id:  thumbnails.value[index]?.id || null,
      order: index
    };

    // Envia las imagenes al padre (Al formulario de creacion)
    emit("update:modelValue",thumbnails.value);
  }
};

// Función modificada para disparar el input cuando se hace clic
const triggerFileInput = (index) => {
  const fileInput = document.querySelectorAll('input[type="file"]')[index];
  // Aseguramos que no hay efectos secundarios al hacer clic
  if (fileInput) {
    // Técnica para evitar que el navegador cambie el cursor
    setTimeout(() => {
      fileInput.click();
    }, 0);
  }
};
</script>

<style scoped>
input {
  display: none;
  pointer-events: none; /* Evita que el input interfiera con eventos del mouse */
}

.dropzone-container {
  min-height: 160px;
  height: 200px;
  border: 2px dashed #ccc;
  border-radius: 10px;
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer !important; /* Forzar cursor pointer */
  position: relative;
  overflow: hidden; 
  transition: background-color 0.2s ease;
  -webkit-user-select: none; /* Safari */
  -moz-user-select: none; /* Firefox */
  -ms-user-select: none; /* IE/Edge */
  user-select: none; /* Standard */
}

.dropzone-container:hover,
.dropzone-container:active,
.dropzone-container:focus {
  background-color: rgba(255, 255, 255, 0.8);
  border-color: #999;
  cursor: pointer !important; /* Forzar cursor pointer */
}

/* Clase explícita para cuando se está arrastrando */
.dragging {
  background-color: rgba(200, 200, 200, 0.5);
  border-color: #666;
  cursor: copy !important;
}

/* Prevenir cambio de cursor en todos los posibles estados */
.dropzone-container *,
.file-label,
.preview-wrapper,
.placeholder {
  cursor: pointer !important;
  -webkit-user-drag: none;
  
}

/* Resto del código sin cambios */
.preview-wrapper {
  position: relative;
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}

.preview-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 8px;
}

.overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 8px;
}

.edit-icon {
  /* Aplicar filtro de opacidad directamente al elemento */
  color: var(--primary-color);
  opacity: 0.7; /* Ajusta este valor entre 0 y 1 para controlar la transparencia */
  font-size: 24px;
}

.hover-effect .preview-img {
  filter: brightness(0.7);
}

.placeholder {
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 24px;
  width: 100%;
  height: 100%;
  background-color: transparent !important;

}

/* Ajustes de altura responsive con media queries */
@media (max-width: 576px) {
  .dropzone-container {
    min-height: 200px;
  }
}
</style>