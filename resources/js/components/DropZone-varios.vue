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
import { defineProps, defineEmits } from "vue";

// Recibir `modelValue` desde el padre
const props = defineProps({
  modelValue: Array, // `post.thumbnails` del padre
});

const emit = defineEmits(["update:modelValue"]);

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

    // Clonar el array y actualizar el elemento
    const updatedThumbnails = [...props.modelValue];
    updatedThumbnails[index] = { img: URL.createObjectURL(file), file };

    // Emitir el nuevo array para actualizar el padre
    emit("update:modelValue", updatedThumbnails);
  }
};

// Manejar cambio de imagen desde el input
const onChange = (e, index) => {
  const file = e.target.files[0];
  if (file) {
    const updatedThumbnails = [...props.modelValue];
    updatedThumbnails[index] = { img: URL.createObjectURL(file), file };

    // Emitir el nuevo array
    emit("update:modelValue", updatedThumbnails);
  }
};

// Función para disparar el input cuando se hace clic
const triggerFileInput = (index) => {
  document.querySelectorAll('input[type="file"]')[index].click();
};
</script>



<script setup>
import { ref } from "vue";

// Usar ref en Vue para referirse a los inputs dinámicos
const thumbnails = ref([
  { img: "", file: null }, // Contenedor 1
  { img: "", file: null }, // Contenedor 2
  { img: "", file: null }  // Contenedor 3
]);

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
    thumbnails.value[index].img = URL.createObjectURL(file);
    thumbnails.value[index].file = file;
  }
};

// Manejar cambio de imagen desde el input
const onChange = (e, index) => {
  console.log('thumbnails -->', thumbnails);
  const file = e.target.files[0];
  if (file) {
    thumbnails.value[index].img = URL.createObjectURL(file);
    thumbnails.value[index].file = file;
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
  height: 200px;
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