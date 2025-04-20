import { onMounted, ref } from 'vue';
import { initializeApp } from "firebase/app";
import {
    getFirestore, doc, getDoc, getDocs, updateDoc, setDoc, collection, addDoc, where, serverTimestamp, query, orderBy, onSnapshot
} from "firebase/firestore";

import useProducts from "@/composables/products.js";
import useUsers from "@/composables/users.js";
import { CacheManager } from './cache-manager';

const { getUser } = useUsers();
const { product, getProduct } = useProducts();

// Configuración de Firebase
const firebaseConfig = {
    apiKey: import.meta.env.VITE_FIREBASE_APIKEY,
    authDomain: import.meta.VITE_FIREBASE_AUTHDOMAIN,
    projectId:import.meta.env.VITE_FIREBASE_PROJECTID,
    storageBucket: import.meta.env.VITE_FIREBASE_STORAGEBUCKET,
    messagingSenderId: import.meta.env.VITE_FIREBASE_MESSAGINGSENDERID,
    appId: import.meta.env.VITE_FIREBASE_APPID,
};

// Inicializamos la aplicación de Firebase
const app = initializeApp(firebaseConfig);
const db = getFirestore(app);

const cacheManager = new CacheManager();

export default function useFirebase() {
    const chats = ref({ id: null }); // Referencia para almacenar un chat específico
    const activeChats = ref([]); // Referencia para almacenar los chats activos
    const loading = ref(false); // Estado de carga

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
                createdAt: serverTimestamp(),
                messages: {}, // Ensure messages is initialized as an empty object
            };

            if (chatSnapshot.exists()) {
                console.log("✔️ El chat ya existe:", chatSnapshot.data());
                return { ...chatSnapshot.data(), id: chat_id };
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

            const messageId = Date.now().toString(); // Unique ID for the message

            const newMessage = {
                userId,
                text,
                createdAt: serverTimestamp(),
                read: false,
            };

            // Append the new message to the existing messages object
            const updates = {
                [`messages.${messageId}`]: newMessage, // Add the new message
                lastMessage: { userId, text }, // Update last message
                lastMessageTimestamp: serverTimestamp(), // Update last message timestamp
            };

            await updateDoc(chatRef, updates);

            console.log("✔️ Mensaje enviado con éxito");
        } catch (error) {
            console.error("❌ Error al enviar mensaje:", error);
        }
    };

    const getCachedProduct = async (productId) => {
        if (!productId) return null;
        return cacheManager.get(`product_${productId}`, () => getProduct(productId));
    };

    const getCachedUser = async (userId) => {
        if (!userId) return null;
        return cacheManager.get(`user_${userId}`, () => getUser(userId));
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
            loading.value = true;
            try {
                const chats = [];
                for (const doc of querySnapshot.docs) {
                    const chatData = doc.data();
                    const productData = await getCachedProduct(chatData.productId);
                    const userData = chatData.lastMessage?.userId ? 
                        await getCachedUser(chatData.lastMessage.userId) : null;

                    chats.push({
                        id: doc.id,
                        productId: chatData.productId,
                        product: productData,
                        lastMessage: chatData.lastMessage,
                        user: userData,
                        users: chatData.users,
                        lastMessageTimestamp: chatData.lastMessageTimestamp || chatData.createdAt
                    });
                }

                activeChats.value = chats.sort((a, b) => {
                    const timeA = a.lastMessageTimestamp?.seconds || 0;
                    const timeB = b.lastMessageTimestamp?.seconds || 0;
                    return timeB - timeA;
                });
            } catch (error) {
                console.error("Error loading chats:", error);
            } finally {
                loading.value = false;
            }
        });

        return unsubscribe;
    };

    async function getUsersIdInterested(productId) {
        const chatsRef = collection(db, "chats");
        const q = query(chatsRef, where("productId", "==", productId));
      
        const querySnapshot = await getDocs(q);
        const userIdsSet = new Set();
        console.log(querySnapshot);
      
        querySnapshot.forEach((doc) => {
          const data = doc.data();
          if (data.users && Array.isArray(data.users) && data.users.length > 1) {
            userIdsSet.add(data.users[0]); // solo usuario interesado
          }
        });
        console.log('FIRABASE -->', Array.from(userIdsSet));
        return Array.from(userIdsSet);
      }


    return {
        activeChats,
        loading,
        getOppositeMessages,
        chats,
        chatExists,
        sendMessage,
        getMessages,
        getUserChats,
        getUsersIdInterested,
    };
}