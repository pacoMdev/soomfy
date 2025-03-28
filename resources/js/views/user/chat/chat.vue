<template>
  <div class="chat-container">
    <!-- Llista contactes -->
    <div class="chat-sidebar">
      <h3>Contactes</h3>
      <input v-model="searchTerm" placeholder="Cerca" class="search-input">
      <div v-for="chat in activeChats" :key="chat.id" @click="selectChat(chat.id, chat.users, chat.productId)" class="chat-item bordes">
        <p>Producte ID: {{ chat.productId }}</p>
        <p>Producte Name: {{ chat.product.title }}</p>
        <p v-if="chat.lastMessage">
          {{chat.user?.name || "" }}: {{ chat.lastMessage[1] }}
        </p>
        <p>Chat ID: {{ chat.id }}</p>
        <p>{{chat.users}}</p>
        <p>Participants: {{ chat.users.join(', ') }}</p>
      </div>
    </div>

    <!-- Àrea missatges -->
    <div class="chat-content">
      <div class="messages" ref="messagesContainer">
        <div v-for="msg in messages" :key="msg.id"
             :class="['msg', msg.userId === usuarioAutenticado ? 'outgoing' : 'incoming']">
          <small class="propietario">{{ msg.userId === auth.user.id ? auth.user.name : msg.userId }}</small>
          {{ msg.text }}
          <small>{{ formatMessageTime(msg.timestamp) }}</small>
          <small>{{ msg.userId !== auth.user.id ? (msg.read ? "✔️✔️" : "No leido") : "" }}</small>

        </div>
      </div>

      <div class="chat-input">
        <input type="text" v-model="newMessage" @keyup.enter="sendNewMessage" placeholder="Escriu el teu missatge">
        <button class="secondary-button-2" @click="sendNewMessage">Enviar</button>
      </div>
    </div>

  </div>
</template>
<script setup>

import {onMounted, ref, onUnmounted, watch, nextTick, inject} from "vue";
import useFirebase from "../../../composables/firebase";
import useProducts from "../../../composables/products";
const { sendMessage, getMessages, getUserChats, chatExists, activeChats, getOppositeMessages, chats } = useFirebase();
const { product, getProduct } = useProducts();
import { useRoute,useRouter} from "vue-router";
import { authStore } from "@/store/auth.js";
const auth = authStore();


const route = useRoute();
const router = useRouter();


const currentChat = ref(null);
const searchTerm = ref('');



const productId = ref(null);
const compradorId = ref(null);
const vendedorId = ref(null);


const usuarioAutenticado = auth.user.id;

const newMessage = ref('');
const messagesContainer = ref(null);// DOCUMENTAR MAÑANA
const messages = ref([]); // DOCUMENTAR MAÑANA
let unsubscribeMessages = null; // DOCUMENTAR MAÑANA

const formatMessageTime = (timestamp) => { // DOCUMENTAR MAÑANA
  if (!timestamp) return '';

  const date = timestamp instanceof Date ? timestamp : new Date(timestamp);
  return date.toLocaleTimeString(undefined, {
    hour: '2-digit',
    minute: '2-digit'
  });
};


const scrollToBottom = () => { // DOCUMENTAR MAÑANA
  if (messagesContainer.value) {
    nextTick(() => {
      messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
    });
  }
};

const selectChat = async (chatId, users, idProducto) => { // DOCUMENTAR MAÑANA
  productId.value = idProducto;
  compradorId.value = users[0];
  vendedorId.value = users[1];

  // Si el chat existe me devueve la informacion del chat
  const chatData = await chatExists(
      productId.value,
      compradorId.value,
      vendedorId.value,
  )
  currentChat.value = chatData;

  if (!compradorId.value || !vendedorId.value || !productId.value) {
    console.error("❌ Faltan IDs para crear el chat:", {
      compradorId: compradorId.value,
      vendedorId: vendedorId.value,
      productId: productId.value
    });
  }
  loadMessages(chatId);
  
  // Tendria que comprobar
  if(usuarioAutenticado === compradorId){
    // Cojemos los mensajes de vendedorId y ponemos cada mensaje en read: true
    getOppositeMessages(chatId, vendedorId);
  } else {
    // Cojemos los mensajes de compradorId y ponemos cada mensaje en read: true
    getOppositeMessages(chatId, compradorId);
  }
};


watch(messages, () => { // DOCUMENTAR MAÑANA
  scrollToBottom();
}, { deep: true });

const loadMessages = (chatId) => { // DOCUMENTAR MAÑANA
  // Cancel·lem qualsevol subscripció anterior
  if (unsubscribeMessages) {
    unsubscribeMessages();
  }

  // Iniciem una nova subscripció
  unsubscribeMessages = getMessages(chatId, (newMessages) => {
    messages.value = newMessages;
  });
}

const obtainUserChats = async () => {
    activeChats.value = await getUserChats(usuarioAutenticado);
    console.log(activeChats.value);

}

const sendNewMessage = async () => {
  console.log("Intentant enviar missatge:", newMessage.value);
  console.log("Estat del currentChat:", currentChat.value);

  if (!newMessage.value.trim() || !currentChat.value) return;

  try {
    await sendMessage(currentChat.value.id, usuarioAutenticado, newMessage.value);
    console.log("✅ Missatge enviat amb èxit");
    newMessage.value = "";
  } catch (error) {
    console.error("❌ Error enviant missatge:", error);
  }
};

const swal = inject('$swal')

onMounted(
    async () => {
      try {
        await obtainUserChats();
        // Si creamos o accedemos al chat desde detalle_producto
        if(route.query.chatData){
            currentChat.value = JSON.parse(route.query.chatData);
            console.log("🔹 Dades del chat (de ruta):", currentChat.value);
            console.log("🆔 ID del chat:", currentChat.value.id);
            productId.value = currentChat.value.productId;
            vendedorId.value = currentChat.value.users.vendedorId;
            compradorId.value = currentChat.value.users.compradorId;

            console.log(compradorId.value);
            console.log(auth.user.id)
            // if(compradorId.value !== auth.user.id || vendedorId.value !== auth.user.id){
            //   await router.push({name: 'home'});
            // }

            // Carreguem els missatges quan tenim l'ID del xat
            if (currentChat.value && currentChat.value.id) { // DOCUMENTAR MAÑANA
              loadMessages(currentChat.value.id);
            }

        }

      } catch (error) {
        console.error("❌ Error:", error);
      }

    }
);

onUnmounted(() => { // DOCUMENTAR MAÑANA
  // Netegem la subscripció quan es desmunta el component
  if (unsubscribeMessages) {
    unsubscribeMessages();
  }
});

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
  background: #ececec;
}

.outgoing {
  align-self: flex-end;
  background: #01bfa5;
  color: white;
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