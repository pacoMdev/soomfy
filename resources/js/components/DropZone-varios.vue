<template>
  <div class="grid grid-cols-3 gap-5 w-100">
    <div
        v-for="(item, index) in thumbnails"
        :key="index"
        @click="activarInput"
        class="dropzone-container tamaño-imagen input-imagen"
        @dragover="dragover"
        @dragleave="dragleave"
        @drop="(e) => drop(e, index)"
        @mouseenter="() => hoverIndex = index"
        @mouseleave="() => hoverIndex = null"
    >
      <input
          type="file"
          class="hidden-input backgroundIcon"
          @change="(e) => onChange(e, index)"
          :ref="`refFiles${index}`"
          accept=".webp, .png, .jpg, .heic, .avif"
      />
      <div class="file-label" @click="() => triggerFileInput(index)">
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

// Función de arrastre
const dragover = (e) => {
  e.preventDefault();
};

const dragleave = () => {};

const drop = (e, index) => {
  e.preventDefault();
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

// Función para disparar el input cuando se hace clic
const triggerFileInput = (index) => {
  // Usar la referencia correcta para activar el input correspondiente
  document.querySelectorAll('input[type="file"]')[index].click();
};

// Función adicional para manejar clic en el contenedor (opcional)
const activarInput = () => {
  // Aquí puedes agregar lógica para cuando el contenedor sea clickeado
};
</script>

<style scoped>
input {
  display: none;
}

.tamaño-imagen {
  width: 31%;
}

.dropzone-container {
  min-height: 100px;
  height: 100px;
  border: 2px dashed #ccc;
  border-radius: 10px;
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
  position: relative;
  overflow: hidden;
  transition: filter 7s ease;
}

.dropzone-container:hover {
  background-color: rgba(227, 227, 227, 0.8);
}

.image-icon {
  font-size: 48px;
  color: #ccc;
}

.preview-wrapper {
  position: relative;
  width: 100%;
  height: 100%;
}

.preview-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 10px;
}

.overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 10px;
}

.edit-icon {
  color: #fff;
  font-size: 24px;
}

.hover-effect .preview-img {
  filter: brightness(0.7);
}

.placeholder {
  display: flex;
  justify-content: center;
  align-items: center;
  color: #ccc;
  font-size: 24px;
}
</style>