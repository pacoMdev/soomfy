<template>
  <div class="chat-window" ref="chatContainer">
    <div v-if="loading" class="loading">
      Cargando mensajes...
    </div>

    <div v-else-if="error" class="error">
      Error: {{ error }}
    </div>

    <div v-else-if="messages.length === 0" class="empty-chat">
      No hay mensajes aún. ¡Sé el primero en escribir!
    </div>

    <div v-else class="messages">
      <div
          v-for="msg in messages"
          :key="msg.id"
          :class="['message', { 'my-message': isMyMessage(msg) }]"
      >
        <div class="avatar">
          <img
              v-if="msg.userPhoto"
              :src="msg.userPhoto"
              alt="Avatar"
          />
          <div v-else class="default-avatar">
            {{ getInitials(msg.userName) }}
          </div>
        </div>
        <div class="message-content">
          <div class="user-name">{{ msg.userName }}</div>
          <div class="message-text">{{ msg.text }}</div>
          <div class="timestamp">
            {{ formatTimestamp(msg.timestamp) }}
          </div>
        </div>
      </div>
      <div ref="bottomRef"></div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, nextTick } from 'vue';

const props = defineProps({
  messages: Array,
  currentUser: Object,
  loading: Boolean,
  error: String
});

const chatContainer = ref(null);
const bottomRef = ref(null);

// Función para verificar si el mensaje es del usuario actual
const isMyMessage = (message) => {
  return props.currentUser && message.userId === props.currentUser.id;
};

// Obtener iniciales para avatar por defecto
const getInitials = (name) => {
  if (!name) return '?';
  return name
      .split(' ')
      .map(word => word[0])
      .join('')
      .substring(0, 2)
      .toUpperCase();
};

// Formatear timestamp
const formatTimestamp = (timestamp) => {
  if (!timestamp || !timestamp.toDate) {
    return 'Ahora';
  }

  const date = timestamp.toDate();
  const now = new Date();

  // Si es hoy, mostrar solo la hora
  if (date.toDateString() === now.toDateString()) {
    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
  }

  // Si es este año, mostrar día y mes
  if (date.getFullYear() === now.getFullYear()) {
    return date.toLocaleDateString([], { day: 'numeric', month: 'short' }) +
        ' ' + date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
  }

  // Si es otro año, mostrar fecha completa
  return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};

// Hacer scroll al último mensaje
const scrollToBottom = async () => {
  await nextTick();
  if (bottomRef.value) {
    bottomRef.value.scrollIntoView({ behavior: 'smooth' });
  }
};

// Observar cambios en los mensajes para hacer scroll automático
watch(() => props.messages, () => {
  scrollToBottom();
}, { deep: true });

onMounted(() => {
  scrollToBottom();
});
</script>

<style scoped>
.chat-window {
  flex: 1;
  overflow-y: auto;
  padding: 1rem;
  background: #f9f9f9;
  border: 1px solid #eee;
  border-radius: 8px;
}

.loading, .error, .empty-chat {
  text-align: center;
  padding: 2rem 0;
  color: #888;
}

.error {
  color: #e74c3c;
}

.messages {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.message {
  display: flex;
  align-items: flex-start;
  max-width: 80%;
}

.my-message {
  margin-left: auto;
  flex-direction: row-reverse;
}

.avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  overflow: hidden;
  margin: 0 10px;
}

.avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.default-avatar {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #3498db;
  color: white;
  font-weight: bold;
}

.message-content {
  background-color: #fff;
  border-radius: 12px;
  padding: 8px 12px;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
  position: relative;
}

.my-message .message-content {
  background-color: #dcf8c6;
}

.user-name {
  font-weight: bold;
  font-size: 0.85rem;
  color: #555;
  margin-bottom: 4px;
}

.message-text {
  word-break: break-word;
}

.timestamp {
  font-size: 0.7rem;
  color: #999;
  text-align: right;
  margin-top: 4px;
}
</style>