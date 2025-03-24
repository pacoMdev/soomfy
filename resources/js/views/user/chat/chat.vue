<template>
  <div class="chat-container">
    <!-- Llista contactes -->
    <div class="chat-sidebar">
      <h3>Contactes</h3>
      <input v-model="searchTerm" placeholder="Cerca" class="search-input">
      <div v-for="contact in filteredContacts" :key="contact.id"
           :class="['chat-contact', currentContact === contact ? 'selected' : '']"
           @click="selectContact(contact)">
        {{ contact.name }}
      </div>
    </div>


    <!-- Àrea missatges -->
    <div class="chat-content">
      <div class="messages" ref="messagesContainer">
        <div v-for="msg in messages" :key="msg.id"
             :class="['msg', msg.userId === compradorId ? 'outgoing' : 'incoming']">
          {{ msg.text }}
          <small>{{ formatMessageTime(msg.timestamp) }}</small>
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

import {onMounted, ref, onUnmounted, watch, nextTick} from "vue";
import useFirebase from "../../../composables/firebase";
const { chatExists, sendMessage, getMessages } = useFirebase();
import { useRoute } from "vue-router";

const route = useRoute();

const currentChat = ref(null);
const searchTerm = ref('');
const currentContact = ref(null);


const productId = route.query.productId;
const vendedorId = route.query.vendedorId;
const compradorId = route.query.compradorId;

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
};



const sendNewMessage = async () => {
  if (!newMessage.value.trim() || !currentChat.value) return;

  try {
    await sendMessage(currentChat.value.id, compradorId, newMessage.value);
    newMessage.value = "";
  } catch (error) {
    console.error("❌ Error enviant missatge:", error);
  }
};

onMounted(
    async () => {
      try {
        currentChat.value = await chatExists(productId,compradorId, vendedorId);
        console.log("🔹 Dades del chat:", currentChat.value);
        console.log("🆔 ID del chat:", currentChat.value.id);
        // Carreguem els missatges quan tenim l'ID del xat
        if (currentChat.value && currentChat.value.id) { // DOCUMENTAR MAÑANA
          loadMessages(currentChat.value.id);
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
  padding: 15px;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
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