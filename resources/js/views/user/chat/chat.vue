<template>
  <div class="chat-container">
    <!-- Lista de contactos -->
    <div class="chat-sidebar">
      <h3>Contactos</h3>
      <input v-model="searchTerm" placeholder="Buscar" class="search-input">
      <div v-for="chat in activeChats" :key="chat.id" @click="selectChat(chat.id, chat.users, chat.productId)" class="chat-item bordes">
        <p>Producto ID: {{ chat.productId }}</p>
        <p>Producto Nombre: {{ chat.product?.title }}</p>
        <p v-if="chat.lastMessage">
          {{ chat.user?.name || "" }}: {{ chat.lastMessage[1] }}
        </p>
        <p>Chat ID: {{ chat.id }}</p>
        <p>Participantes: {{ chat.users.join(', ') }}</p>
      </div>
    </div>

    <!-- Área de mensajes -->
    <div class="chat-content">
      <div class="messages" ref="messagesContainer">
        <div v-for="msg in messages" :key="msg.id"
             :class="['msg', msg.userId === usuarioAutenticado ? 'outgoing' : 'incoming']">
          <small class="propietario">{{ msg.userId === auth.user.id ? auth.user.name : msg.userId }}</small>
          {{ msg.text }}
          <div class="d-flex gap-2">
            <small>{{ formatMessageTime(msg.timestamp) }}</small>
            <small v-html="msg.userId === auth.user.id && msg.read ? checkIconSVG : notCheckIconSVG"></small>
          </div>
        </div>
      </div>

      <div class="chat-input">
        <input type="text" v-model="newMessage" @keyup.enter="sendNewMessage" placeholder="Escribe tu mensaje">
        <button class="secondary-button-2" @click="sendNewMessage">Enviar</button>
      </div>
    </div>
  </div>
</template>

<script setup>
// Importaciones necesarias
import { onMounted, ref, onUnmounted, watch, nextTick, inject } from "vue";
import useFirebase from "../../../composables/firebase";
import useProducts from "../../../composables/products";
import { useRoute, useRouter } from "vue-router";
import { authStore } from "@/store/auth.js";

// Composables y constantes
const { sendMessage, getMessages, getUserChats, chatExists, activeChats, getOppositeMessages } = useFirebase();
const { product, getProduct } = useProducts();
const auth = authStore();
const route = useRoute();
const router = useRouter();

// Variables reactivas
const compradorData = ref({}); // Datos del comprador
const vendedorData = ref({}); // Datos del vendedor

const currentChat = ref(null); // Chat actual seleccionado
const searchTerm = ref(''); // Término de búsqueda en la lista de contactos
const productId = ref(null); // ID del producto asociado al chat
const compradorId = ref(null); // ID del comprador
const vendedorId = ref(null); // ID del vendedor
const usuarioAutenticado = auth.user.id; // ID del usuario autenticado
const newMessage = ref(''); // Nuevo mensaje a enviar
const messagesContainer = ref(null); // Contenedor de mensajes
const messages = ref([]); // Lista de mensajes del chat actual
let unsubscribeMessages = null; // Función para cancelar la suscripción a mensajes

// Iconos SVG
const checkIconSVG = `
<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="var(--secondary-color)">
  <path d="M268-240 42-466l57-56 170 170 56 56-57 56Zm226 0L268-466l56-57 170 170 368-368 56 57-424 424Zm0-226-57-56 198-198 57 56-198 198Z"/>
</svg>
`;

const notCheckIconSVG = `
<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="var(--neutral-color)">
  <path d="M268-240 42-466l57-56 170 170 56 56-57 56Zm226 0L268-466l56-57 170 170 368-368 56 57-424 424Zm0-226-57-56 198-198 57 56-198 198Z"/>
</svg>
`;

// Funciones

/**
 * Formatea la hora de un mensaje.
 * @param {Date|number} timestamp - Marca de tiempo del mensaje.
 * @returns {string} - Hora formateada.
 */
const formatMessageTime = (timestamp) => {
  if (!timestamp) return '';
  const date = timestamp instanceof Date ? timestamp : new Date(timestamp);
  return date.toLocaleTimeString(undefined, { hour: '2-digit', minute: '2-digit' });
};

/**
 * Desplaza automáticamente el contenedor de mensajes hacia abajo.
 */
const scrollToBottom = () => {
  if (messagesContainer.value) {
    nextTick(() => {
      messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
    });
  }
};

/**
 * Selecciona un chat y carga sus mensajes.
 * @param {string} chatId - ID del chat.
 * @param {Array} users - Usuarios participantes del chat.
 * @param {string} idProducto - ID del producto asociado al chat.
 */
const selectChat = async (chatId, users, idProducto) => {
  productId.value = idProducto;
  compradorId.value = users[0];
  vendedorId.value = users[1];

  const chatData = await chatExists(productId.value, compradorId.value, vendedorId.value);
  currentChat.value = chatData;

  if (!compradorId.value || !vendedorId.value || !productId.value) {
    console.error("❌ Faltan IDs para crear el chat:", { compradorId: compradorId.value, vendedorId: vendedorId.value, productId: productId.value });
  }

  loadMessages(chatId);

  if (usuarioAutenticado === compradorId.value) {
    getOppositeMessages(chatId, vendedorId.value);
  } else {
    getOppositeMessages(chatId, compradorId.value);
  }
};

/**
 * Carga los mensajes de un chat.
 * @param {string} chatId - ID del chat.
 */
const loadMessages = (chatId) => {
  if (unsubscribeMessages) {
    unsubscribeMessages();
  }

  unsubscribeMessages = getMessages(chatId, (newMessages) => {
    messages.value = newMessages;
  });
};

/**
 * Obtiene los chats del usuario autenticado.
 */
const obtainUserChats = async () => {
  await getUserChats(usuarioAutenticado);
};

/**
 * Envía un nuevo mensaje en el chat actual.
 */
const sendNewMessage = async () => {
  if (!newMessage.value.trim() || !currentChat.value) return;

  try {
    await sendMessage(currentChat.value.id, usuarioAutenticado, newMessage.value);
    newMessage.value = "";
  } catch (error) {
    console.error("❌ Error enviando mensaje:", error);
  }
};

// Hooks

/**
 * Hook que se ejecuta al montar el componente.
 */
onMounted(async () => {
  try {
    await obtainUserChats();

    if (route.query.chatData) {
      currentChat.value = JSON.parse(route.query.chatData);
      productId.value = currentChat.value.productId;
      vendedorId.value = currentChat.value.users.vendedorId;
      compradorId.value = currentChat.value.users.compradorId;

      if (currentChat.value && currentChat.value.id) {
        loadMessages(currentChat.value.id);
      }
    }
  } catch (error) {
    console.error("❌ Error:", error);
  }
});

/**
 * Hook que se ejecuta al desmontar el componente.
 */
onUnmounted(() => {
  if (unsubscribeMessages) {
    unsubscribeMessages();
  }
});

// Watchers

/**
 * Observa los cambios en los mensajes y desplaza el contenedor hacia abajo.
 */
watch(messages, () => {
  scrollToBottom();
}, { deep: true });

/**
 * Observa los cambios en los chats activos.
 */
watch(activeChats, (newChats) => {
  console.log("Active chats updated:", newChats);
}, { deep: true });

</script>
<style scoped>
.chat-container {
  display: flex;
  height: 75vh;
  max-width: 900px;
  margin: auto;
  border: 1px solid #ddd;
  border-radius: 8px;
  overflow: hidden;
  font-family: Arial, sans-serif;
}

.chat-sidebar {
  width: 30%;
  background: #f7f9fc;
  border-right: 1px solid #ddd;
  padding: 10px;
  overflow-y: auto;
}

.search-input {
  width: 100%;
  padding: 5px;
  margin-bottom: 10px;
  border-radius: 5px;
  border: 1px solid #ddd;
}

.chat-contact {
  padding: 8px;
  cursor: pointer;
  border-radius: 5px;
}

.chat-contact.selected, .chat-contact:hover {
  background: #ddd;
}

.chat-content {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.messages {
  flex: 1;
  padding: 25px;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  row-gap: 30px;
}

.bordes {
  border: 0.15px solid grey;
  cursor: pointer;
  border-radius: 25px;
  background-color: white;
  text-align: center;
}


.msg {
  padding: 8px;
  margin-bottom: 8px;
  max-width: 60%;
  border-radius: 5px;
  font-size: 14px;
  position: relative;
}

.incoming {
  align-self: flex-start;
  background: var(--secondary-color);
  color: var(--primary-color);
}

.outgoing {
  align-self: flex-end;
  background: var(--primary-color);
  color: var(--secondary-color);
}

.msg small {
  position: absolute;
  bottom: -15px;
  right: 5px;
  font-size: 10px;
  color: #666;
}

.msg small.propietario {
  position: absolute;
  top: -13px;
  right: 5px;
}

.chat-input {
  padding: 10px;
  border-top: 1px solid #ddd;
  display: flex;
}

.chat-input input {
  flex: 1;
  padding: 8px;
  border-radius: 5px;
  border: 1px solid #ddd;
  outline: none;
}

</style>