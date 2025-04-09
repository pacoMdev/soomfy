<template>
  <div class="chat-container">
    <!-- Lista de contactos -->
    <div 
      class="chat-sidebar" 
      :class="{ 'hidden-sidebar': isSmallScreen && !showChatList }"
    >
      <button 
        v-if="isSmallScreen" 
        class="toggle-sidebar-btn" 
        @click="toggleChatList"
      >
        {{ showChatList ? 'Ocultar Chats' : 'Mostrar Chats' }}
      </button>
      <div v-if="activeChats" class="bordes d-flex flex-column gap-3 align-items-center">
        <div 
          v-for="chat in activeChats" 
          :key="chat.id" 
          @click="selectChat(chat.id, chat.users, chat.productId)" 
          class="chat-item bordes d-flex flex-row align-items-center" 
          :class="{ selected: currentChat?.id === chat.id }"
        >
          <div class="d-flex flex-column">
            <img :src="chat.product?.original_image" alt="" width="80" height="80" class="rounded-1">
          </div>
          <div class="d-flex centradoChat flex-column text-start ms-2">
            <p class="m-0">{{ chat.product?.title }}</p>
            <p v-if="chat.lastMessage">
              {{ chat.user?.name || "" }}: {{ chat.lastMessage[1] }}
            </p>
          </div>
        </div>
      </div>
      <div v-else>
        <img src="/images/undraw_file-search_cbur.svg" alt="Imagen compras" class="image-else">
      </div>
    </div>

    <!-- Área de mensajes -->
    <div 
      v-if="!isSmallScreen || (isSmallScreen && currentChat)" 
      class="chat-content"
    >
      <div v-if="!activeChats || !currentChat">
        <img src="images/noChatSelected.png" alt="">
      </div>
      <div v-else>
        <!-- Chat content -->
        <div class="ps-3 gap-3 m-3 position-relative">
          <div :class="[!showProductDetails ? 'divider py-4' : '']">
            <div v-if="showProductDetails" class="d-flex flex-column justify-content-start align-items-center gap-3 m-3">
              <img :src="user?.avatar" class="rounded-circle" width="60px" alt="">
              <h4 class="m-0">{{ user?.name }}</h4>
            </div>
            <div v-else class="d-flex flex-row justify-content-start align-items-center gap-3">
              <img :src="product?.original_image" class="rounded-2" width="80px" alt="">
              <h4 class="m-0">{{ product?.title }}</h4>
            </div>
            <div v-if="product" class="info-icon-container">
              <span class="info-icon" @click="toggleProductDetails">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#042A2D">
                  <path d="M440-280h80v-240h-80v240Zm40-320q17 0 28.5-11.5T520-640q0-17-11.5-28.5T480-680q-17 0-28.5 11.5T440-640q0 17 11.5 28.5T480-600Zm0 520q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/>
                </svg>
              </span>
            </div>
          </div>
          <div v-if="showProductDetails" class="product-details-container">
            <img :src="product?.original_image" alt="product-image" width="200px">
            <div class="product-details">
              <p class="m-0 h3-p "><b>Descripcion:</b></p>
              <p>{{ product?.content }}</p>
              <p class="m-0 h3-p "><b>Precio:</b></p>
              <p>{{ product?.price }}</p>
              <p class="m-0 h3-p "><b>Estado:</b></p>
              <p>{{ product?.estado?.name }}</p>
              <p class="m-0 h3-p "><b>Marca:</b></p>
              <p>{{ product?.marca }}</p>
              <p class="m-0 h3-p "><b>Color:</b></p>
              <p>{{ product?.product?.color }}</p>
              <p class="m-0 h4-p ">{{ product?.toSend == 1 ? "Envio disponible" : "Envio no disponible" }}</p>
            </div>
          </div>
        </div>
        <div v-if="!showProductDetails" class="messages" ref="messagesContainer">
          <div v-for="msg in messages" :key="msg.id" :class="['msg', msg.userId === usuarioAutenticado ? 'outgoing' : 'incoming']">
            <p class="message-text">{{ msg.text }}</p>
            <div class="centradoInfoMessage" :style="{ justifyContent: msg.userId === usuarioAutenticado ? 'flex-end' : 'flex-start' }">
              <small>{{ formatMessageTime(msg.timestamp) }}</small>
              <small v-if="msg.userId === auth.user.id" v-html="msg.userId === auth.user.id && msg.read ? checkIconSVG : notCheckIconSVG"></small>
            </div>
          </div>
        </div>
        <div v-if="!showProductDetails" class="chat-input">
          <input type="text" v-model="newMessage" @keyup.enter="sendNewMessage" placeholder="Escribe tu mensaje">
          <button class="secondary-button-2" @click="sendNewMessage">Enviar</button>
        </div>
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

// Importar estilos CSS
import "./chat.css";

// Composables y constantes
const { sendMessage, getMessages, getUserChats, chatExists, activeChats, loading, getOppositeMessages } = useFirebase();
const { product, getProduct } = useProducts();
const { user, getUser } = useUsers();
const auth = authStore();
const route = useRoute();
const router = useRouter();

// Variables reactivas
const currentChat = ref(null); // Chat actual seleccionado
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

// Variable reactiva para controlar la visibilidad de los detalles del producto
const showProductDetails = ref(false);

/**
 * Alterna la visibilidad de los detalles del producto.
 */
const toggleProductDetails = () => {
  showProductDetails.value = !showProductDetails.value;
};

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
  showProductDetails.value = false; // Cerrar detalles del producto al cambiar de chat
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

const isSmallScreen = ref(window.innerWidth <= 768);
const showChatList = ref(true);

const toggleChatList = () => {
  showChatList.value = !showChatList.value;
};

window.addEventListener('resize', () => {
  isSmallScreen.value = window.innerWidth <= 768;
});
</script>