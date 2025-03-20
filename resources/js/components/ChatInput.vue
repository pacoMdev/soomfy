<template>
  <div class="chat-input">
    <form @submit.prevent="handleSubmit">
      <div class="input-group">
        <input
            v-model="message"
            :disabled="disabled"
            placeholder="Escribe un mensaje..."
            type="text"
            ref="messageInput"
        />
        <button type="submit" :disabled="!canSend || disabled">
          Enviar
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  disabled: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['send-message']);
const message = ref('');
const messageInput = ref(null);

// Calcular si se puede enviar el mensaje
const canSend = computed(() => message.value.trim().length > 0);

// Manejar el envío del formulario
const handleSubmit = () => {
  if (!canSend.value || props.disabled) return;

  emit('send-message', message.value.trim());
  message.value = '';

  // Enfocar el input después de enviar
  if (messageInput.value) {
    messageInput.value.focus();
  }
};
</script>

<style scoped>
.chat-input {
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 1px solid #eee;
}

.input-group {
  display: flex;
  gap: 8px;
}

input {
  flex: 1;
  padding: 12px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
}

button {
  padding: 0 1.5rem;
  background-color: #3498db;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: bold;
  transition: background-color 0.2s;
}

button:hover {
  background-color: #2980b9;
}

button:disabled {
  background-color: #bdc3c7;
  cursor: not-allowed;
}
</style>