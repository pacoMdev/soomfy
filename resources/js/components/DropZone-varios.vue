<template>
  <div class="grid grid-cols-3 gap-4">
    <div
      v-for="(item, index) in containers"
      :key="index"
      class="dropzone-container"
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
        :ref="'refFiles' + index"
        accept=".webp"
      />
      <div class="file-label" @click="() => triggerFileInput(index)">
        <div v-if="item.img" class="preview-wrapper" :class="{ 'hover-effect': hoverIndex === index }">
          <img class="preview-img" :src="item.img" />
          <div v-if="hoverIndex === index" class="overlay">
            <i class="fas fa-edit edit-icon"></i>
          </div>
        </div>
        <div v-else class="placeholder">
          <i class="fas fa-image image-icon"></i>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";

const containers = ref([
  { img: "", file: null },
  { img: "", file: null },
  { img: "", file: null }
]);
const hoverIndex = ref(null);

const dragover = (e) => {
  e.preventDefault();
};

const dragleave = () => {};

const drop = (e, index) => {
  e.preventDefault();
  const files = e.dataTransfer.files;
  if (files.length > 0) {
    const file = files[0];
    containers.value[index].img = URL.createObjectURL(file);
    containers.value[index].file = file;
  }
};

const onChange = (e, index) => {
  const file = e.target.files[0];
  if (file) {
    containers.value[index].img = URL.createObjectURL(file);
    containers.value[index].file = file;
  }
};

const triggerFileInput = (index) => {
  document.querySelector(`[ref='refFiles${index}']`).click();
};
</script>

<style scoped>
.dropzone-container {
  height: 150px;
  border: 2px dashed #ccc;
  border-radius: 10px;
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}


.placeholder {
  color: #888;
  font-size: 24px;
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
</style>
