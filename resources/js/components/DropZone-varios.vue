<template>
    <div class="grid grid-cols-3 gap-4">
      <div
        v-for="(item, index) in containers"
        :key="index"
        class="dropzone-container"
        @dragover="dragover"
        @dragleave="dragleave"
        @drop="(e) => drop(e, index)"
      >
        <input
          type="file"
          class="hidden-input"
          @change="(e) => onChange(e, index)"
          :ref="'refFiles' + index"
          accept=".gif,.webp,.jpg,.jpeg,.png"
        />
        <div class="file-label text-center">
          <img
            v-if="item.img"
            class="preview-img"
            :src="item.img"
          />
          <div v-else class="placeholder">
            Drop or click here
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
  }
  
  .preview-img {
    max-height: 100%;
    max-width: 100%;
    border-radius: 10px;
  }
  
  .placeholder {
    color: #888;
  }
  </style>
  