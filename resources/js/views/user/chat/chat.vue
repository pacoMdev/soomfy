<template>
  <div class="chat-container">
    <h2>Chat en tiempo real</h2>

    <!-- Selector de sala de chat (opcional) -->
    <div class="room-selector">
      <select v-model="selectedRoom">
        <option value="general">General</option>
        <option value="soporte">Soporte</option>
        <!-- Más salas según necesites -->
      </select>
    </div>

    <!-- Ventana de chat -->
    <chat-window
        :messages="messages"
        :currentUser="currentUser"
        :loading="loading"
        :error="error"
    />

    <!-- Formulario para enviar mensajes -->
    <chat-input
        v-if="currentUser"
        @send-message="handleSendMessage"
        :disabled="loading"
    />
    <p v-else class="login-message">Inicia sesión para participar en el chat</p>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { useChat } from '@/composables/useChat';
import ChatWindow from '@/components/chat/ChatWindow.vue';
import ChatInput from '@/components/chat/ChatInput.vue';

// Props para recibir el usuario autenticado de tu sistema existente
const props = defineProps({
  currentUser: Object
});

// Estado para la sala seleccionada
const selectedRoom = ref('general');

// Usar el composable de chat con la sala seleccionada
const { messages, sendMessage, loading, error } = useChat(selectedRoom.value);

// Observar cambios en la sala seleccionada
watch(selectedRoom, (newRoom) => {
  // Recargar el chat con la nueva sala
  const { messages: newMessages, sendMessage: newSendMessage, loading: newLoading, error: newError } = useChat(newRoom);
  messages.value = newMessages.value;
  sendMessage.value = newSendMessage;
  loading.value = newLoading.value;
  error.value = newError.value;
});

// Función para manejar el envío de mensajes
const handleSendMessage = (text) => {
  if (props.currentUser) {
    sendMessage(text, {
      id: props.currentUser.id,
      name: props.currentUser.name,
      avatar: props.currentUser.avatar
    });
  }
};
</script>

<style scoped>
.chat-container {
  max-width: 800px;
  margin: 0 auto;
  padding: 1rem;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: column;
  height: 70vh;
}

.room-selector {
  margin-bottom: 1rem;
}

.room-selector select {
  width: 100%;
  padding: 8px;
  border-radius: 4px;
  border: 1px solid #ddd;
}

.login-message {
  text-align: center;
  color: #888;
  margin-top: 1rem;
}
</style>