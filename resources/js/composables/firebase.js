import { onMounted, ref } from 'vue';
import { initializeApp } from "firebase/app";
import {
    getFirestore, doc, getDoc, getDocs, updateDoc, setDoc, collection, addDoc, where, serverTimestamp, query, orderBy, onSnapshot
} from "firebase/firestore";

import useProducts from "@/composables/products.js";
import useUsers from "@/composables/users.js";

const { getUser } = useUsers();
const { product, getProduct } = useProducts();

// Configuración de Firebase
const firebaseConfig = {
    apiKey: "AIzaSyCWsmiUVB20WRmJUam3W2eqa9wnEprKqus",
    authDomain: "soomfy-f0f44.firebaseapp.com",
    projectId: "soomfy-f0f44",
    storageBucket: "soomfy-f0f44.firebasestorage.app",
    messagingSenderId: "403822758985",
    appId: "1:403822758985:web:2a2cc0e83f7f52c25d0398"
};

// Inicializamos la aplicación de Firebase
const app = initializeApp(firebaseConfig);
const db = getFirestore(app);

export default function useFirebase() {
    const chats = ref({ id: null }); // Referencia para almacenar un chat específico
    const activeChats = ref([]); // Referencia para almacenar los chats activos

    /**
     * Verifica si un chat existe en Firestore. Si no existe, lo crea.
     * @param {string} productId - ID del producto asociado al chat.
     * @param {string} compradorId - ID del comprador.
     * @param {string} vendedorId - ID del vendedor.
     * @returns {Object} - Datos del chat existente o recién creado.
     */
    const chatExists = async (productId, compradorId, vendedorId) => {
        if (compradorId === vendedorId) {
            return;
        }
        console.log("Comprador ID", compradorId);

        // Creamos un ID único para el chat combinando los IDs
        const chat_id = `${productId}_${compradorId}_${vendedorId}`;
        const chatRef = doc(db, "chats", chat_id);

        try {
            const chatSnapshot = await getDoc(chatRef);

            const chatData = {
                productId,
                users: [compradorId, vendedorId],
                createdAt: serverTimestamp()
            };

            if (chatSnapshot.exists()) {
                console.log("✔️ El chat ya existe:", chatSnapshot.data());
                return { ...chatData, id: chat_id };
            } else {
                console.log("ℹ️ El chat no existe, creándolo ahora...");
                await setDoc(chatRef, chatData);
                console.log("✔️ Chat creado con éxito!");

                return { ...chatData, id: chat_id };
            }
        } catch (error) {
            console.error("❌ Error comprobando o creando el chat:", error);
            throw error;
        }
    };

    /**
     * Marca como leídos los mensajes del usuario contrario en un chat.
     * @param {string} chatId - ID del chat.
     * @param {string} userId - ID del usuario actual.
     * @returns {Function} - Función para cancelar la suscripción.
     */
    const getOppositeMessages = (chatId, userId) => {
        if (!chatId) {
            console.log("❌ ID del chat no válido");
            return null;
        }

        const chatRef = doc(db, "chats", chatId);

        // Suscribirse a los cambios en tiempo real
        const unsubscribe = onSnapshot(chatRef, async (snapshot) => {
            if (!snapshot.exists()) {
                console.error("❌ El chat no existe");
                return;
            }

            const chatData = snapshot.data();
            const messagesObj = chatData.messages || {};

            // Procesar los mensajes para marcarlos como leídos
            const updates = {};
            Object.keys(messagesObj).forEach((key) => {
                const msg = messagesObj[key];

                if (msg.userId === userId && !msg.read) {
                    updates[`messages.${key}.read`] = true;
                }
            });

            if (Object.keys(updates).length > 0) {
                try {
                    await updateDoc(chatRef, updates);
                    console.log("✔️ Mensajes del usuario contrario marcados como leídos");
                } catch (error) {
                    console.error("❌ Error actualizando mensajes como leídos:", error);
                }
            }
        });

        return unsubscribe;
    };

    /**
     * Obtiene los mensajes de un chat en tiempo real y los procesa.
     * @param {string} chatId - ID del chat.
     * @param {Function} callback - Función para manejar los mensajes obtenidos.
     * @returns {Function} - Función para cancelar la suscripción.
     */
    const getMessages = (chatId, callback) => {
        if (!chatId) {
            console.error("❌ ID de chat no válido");
            return null;
        }

        const chatRef = doc(db, "chats", chatId);

        const unsubscribe = onSnapshot(chatRef, (snapshot) => {
            if (snapshot.exists()) {
                const chatData = snapshot.data();
                const messagesObj = chatData.messages || {};

                const messagesList = Object.keys(messagesObj).map(key => ({
                    id: key,
                    ...messagesObj[key],
                    timestamp: messagesObj[key].createdAt
                        ? new Date(messagesObj[key].createdAt.seconds * 1000)
                        : new Date()
                })).sort((a, b) => a.timestamp - b.timestamp);

                callback(messagesList);
            } else {
                console.log("❓ No se encontraron mensajes o el chat no existe");
                callback([]);
            }
        }, (error) => {
            console.error("❌ Error obteniendo mensajes:", error);
        });

        return unsubscribe;
    };

    /**
     * Envía un mensaje a un chat en Firestore.
     * @param {string} chatId - ID del chat.
     * @param {string} userId - ID del usuario que envía el mensaje.
     * @param {string} text - Contenido del mensaje.
     */
    const sendMessage = async (chatId, userId, text) => {
        try {
            const chatRef = doc(db, "chats", chatId);

            const chatSnapshot = await getDoc(chatRef);

            if (!chatSnapshot.exists()) {
                console.error("❌ El chat no existe");
                return;
            }

            const messageId = Date.now().toString();

            const newMessage = {
                userId,
                text,
                createdAt: serverTimestamp(),
                read: false
            };

            await updateDoc(chatRef, {
                [`messages.${messageId}`]: newMessage,
                lastMessage: [userId, text],
                lastMessageTimestamp: serverTimestamp()
            });

            console.log("✔️ Mensaje enviado con éxito");
        } catch (error) {
            console.error("❌ Error al enviar mensaje:", error);
        }
    };

    /**
     * Obtiene los chats de un usuario en tiempo real y los actualiza.
     * @param {string} userId - ID del usuario.
     * @returns {Function} - Función para cancelar la suscripción.
     */
    const getUserChats = (userId) => {
        const chatRef = collection(db, "chats");
        const q = query(chatRef, where("users", "array-contains", userId));

        const unsubscribe = onSnapshot(q, async (querySnapshot) => {
            const chats = await Promise.all(querySnapshot.docs.map(async (doc) => {
                const chatData = doc.data();
                const productId = chatData.productId;

                const productData = await getProduct(productId);

                let lastMessage = null;
                let userData = null;

                if (Array.isArray(chatData.lastMessage) && chatData.lastMessage.length >= 2) {
                    lastMessage = chatData.lastMessage;
                    userData = await getUser(lastMessage[0]);
                }

                return {
                    id: doc.id,
                    product: productData,
                    lastMessage,
                    lastMessageTimestamp: chatData.lastMessageTimestamp || chatData.createdAt,
                    user: userData,
                    ...chatData
                };
            }));

            chats.sort((a, b) => {
                const timeA = a.lastMessageTimestamp?.seconds || 0;
                const timeB = b.lastMessageTimestamp?.seconds || 0;
                return timeB - timeA;
            });

            activeChats.value = chats;
        });

        return unsubscribe;
    };

    return {
        activeChats,
        getOppositeMessages,
        chats,
        chatExists,
        sendMessage,
        getMessages,
        getUserChats
    };
}