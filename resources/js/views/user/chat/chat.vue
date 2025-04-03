<template>
  <div class="chat-container">
    <!-- Lista de contactos -->
    <div class="d-flex flex-column">
      <div class="d-flex flex-column">
        <h3>Chats</h3>
        <input v-model="searchTerm" placeholder="Buscar" class="search-input">
      </div>
      <div class="chat-sidebar">
        <div v-if="activeChats" class="bordes d-flex flex-column gap-3 align-items-center">
          <div v-for="chat in activeChats" :key="chat.id" @click="selectChat(chat.id, chat.users, chat.productId)" class="chat-item bordes d-flex flex-row align-items-center" :class="{ selected: currentChat?.id === chat.id }">
            <img :src="chat.product?.original_image" alt="" width="80" height="80" class="rounded-1">
            <div class="d-flex centradoChat flex-column text-start ms-2">
              <p class="m-0">{{ chat.product?.title }}</p>
              <p v-if="chat.lastMessage">
                {{ chat.user?.name || "" }}: {{ chat.lastMessage[1] }}
              </p>
              <p>{{ chat.users }}</p>
            </div>
          </div>
        </div>
        <div v-else>
          <img src="/images/undraw_file-search_cbur.svg" alt="Imagen compras" class="image-else">
        </div>
      </div>
     </div>


    <!-- Área de mensajes -->
    <div v-if="!activeChats || !currentChat" class="chat-content">
      <img src="images/noChatSelected.png" alt="">
    </div>
    <div v-else class="chat-content">
      <div class="d-flex justify-content-start align-items-start">
        <img :src="product?.original_image" alt="" width="200px">
        <div class="p-4">
          <h4 class="my-1">{{ product?.title }}</h4>
          <p class="m-0 h3-p ">{{ product?.price }} €</p>
          <p class="m-0 h4-p ">{{ product?.estado?.name }}</p>
        </div>
        <div class="height-100 align-items-center d-flex flex-column justify-content-center">
          <img :src="user?.avatar" class="rounded-circle" width="60px"  alt="">
        </div>
      </div>
      <div class="messages" ref="messagesContainer">
        <div v-for="msg in messages" :key="msg.id" :class="['msg', msg.userId === usuarioAutenticado ? 'outgoing' : 'incoming']">
            <!-- Texto del mensaje -->
            <p class="message-text">{{ msg.text }}</p>
            <!-- Hora y estado de lectura -->
            <div class="centradoInfoMessage" :style="{ justifyContent: msg.userId === usuarioAutenticado ? 'flex-end' : 'flex-start' }">
              <small>{{ formatMessageTime(msg.timestamp) }}</small>
              <small v-if="msg.userId === auth.user.id" v-html="msg.userId === auth.user.id && msg.read ? checkIconSVG : notCheckIconSVG"></small>
            </div>
          
        </div>
        
      </div>

      <!-- Entrada de texto -->
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
import useUsers from "../../../composables/users";
import { useRoute, useRouter } from "vue-router";
import { authStore } from "@/store/auth.js";
import Skeleton from 'primevue/skeleton';


// Composables y constantes
const { sendMessage, getMessages, getUserChats, chatExists, activeChats, loading, getOppositeMessages } = useFirebase();
const { product, getProduct } = useProducts();
const { user, getUser } = useUsers();
const auth = authStore();
const route = useRoute();
const router = useRouter();

// Variables reactivas
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
  console.log(productId.value);
  await getProduct(productId.value);
  const destinatario = usuarioAutenticado === compradorId.value ? vendedorId.value : compradorId.value;
  await getUser(destinatario);
  console.log(product.value)
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


</script>
<style scoped>
/* Contenedor principal del chat */
.chat-container {
  display: flex;
  height: 75vh;
  max-width: 900px;
  margin: auto;
  border: 1px solid #ddd;
  border-radius: 8px;
  overflow: hidden;
  font-family: Arial, sans-serif;
  margin-top: 40px;
  margin-bottom: 40px;
}

/* Barra lateral de contactos */
.chat-sidebar {
  width: 300px; /* Ancho fijo del sidebar */
  background: #f7f9fc;
  border-right: 1px solid #ddd;
  padding: 10px;
  overflow-y: auto; /* Permite el scroll si el contenido excede la altura */
  row-gap: 15px;
}

.search-input {
  width: 100%;
  padding: 5px;
  margin-bottom: 10px;
  border-radius: 5px;
  border: 1px solid #ddd;
}

.chat-item {
  width: 100%;
  height: 20%;
  padding: 8px;
  cursor: pointer;
  border-radius: 5px;
}

.chat-item.selected, .chat-item:hover {
  background: #ddd;
}

/* Contenido del chat */
.chat-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

/* Contenedor de mensajes */
.messages {
  flex: 1;
  padding: 25px;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  row-gap: 16px; /* Espaciado entre mensajes */
}

/* Estilo de cada mensaje */
.msg {
  padding: 8px;
  margin-bottom: 8px;
  max-width: 40%;
  width: fit-content;
  border-radius: 5px;
  font-size: 14px;
  position: relative;
  word-wrap: break-word; /* Ajusta palabras largas */
  overflow-wrap: break-word; /* Evita desbordamientos */
  white-space: pre-wrap;
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

/* Texto del mensaje */
.message-text {
  margin: 0;
  font-size: 14px;
  line-height: 1.5;
}

/* Contenedor de hora y estado de lectura */
.centradoChat {
  display: flex;
  gap: 8px; /* Espaciado entre elementos */
  margin-top: 4px;
}


.centradoInfoMessage {
  display: flex;
  align-items: center;
  justify-content: end;
  gap: 8px; /* Espaciado entre elementos */
  margin-top: 4px;
}

/* Estilo para los elementos pequeños */
.msg small {
  font-size: 12px;
  color: #666;
  white-space: nowrap; /* Evita que el texto se divida en varias líneas */
}

.msg small.propietario {
  position: absolute;
  top: -19px;
  right: 5px;
}

/* Entrada de texto */
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

.secondary-button-2 {
  margin-left: 8px;
  padding: 8px 16px;
  border: none;
  border-radius: 5px;
  background-color: var(--primary-color);
  color: var(--secondary-color);
  cursor: pointer;
}

.secondary-button-2:hover {
  background-color: var(--primary-hover-color);
}
</style>