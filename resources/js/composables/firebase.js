import {onMounted, ref} from 'vue';
import { initializeApp } from "firebase/app";
import {
    getFirestore, doc, getDoc, getDocs, updateDoc, setDoc, collection, addDoc, where, serverTimestamp, query, orderBy, onSnapshot
} from "firebase/firestore";

import useProducts from "@/composables/products.js";
import useUsers from "@/composables/users.js";

const { getUser } = useUsers();
const { product , getProduct } = useProducts()



// Configuració Firebase
const firebaseConfig = {
    apiKey: "AIzaSyCWsmiUVB20WRmJUam3W2eqa9wnEprKqus",
    authDomain: "soomfy-f0f44.firebaseapp.com",
    projectId: "soomfy-f0f44",
    storageBucket: "soomfy-f0f44.firebasestorage.app",
    messagingSenderId: "403822758985",
    appId: "1:403822758985:web:2a2cc0e83f7f52c25d0398"
};

// Inicialitzem Firebase app
const app = initializeApp(firebaseConfig);
const db = getFirestore(app);

export default function useFirebase() {
    const chats = ref({id: null});

    // Creamos una variable para almacenar los chats activos
    const activeChats = ref([]);
    // Funció que comprova si un chat existeix, si no existeix el crea
    const chatExists = async (productId, compradorId, vendedorId) => {
        if(compradorId === vendedorId){
            return;
        }
        console.log("Comprador ID", compradorId);
        // Fem un ID únic per al chat combinant IDs (productId_comprador_vendedor)
        const chat_id = `${productId}_${compradorId}_${vendedorId}`;
        const chatRef = doc(db, "chats", chat_id);

        try {
            const chatSnapshot = await getDoc(chatRef);

            const chatData = {
                productId,
                users: [
                    compradorId,
                    vendedorId
                ],
                createdAt: serverTimestamp()
            };
            if (chatSnapshot.exists()) {
                console.log("✔️ El chat ja existeix:", chatSnapshot.data());
                return {...chatData, id: chat_id};
            } else {
                console.log("ℹ️ El chat no existeix, creant-lo ara...");
                await setDoc(chatRef, chatData);
                console.log("✔️ Chat creat amb èxit!");

                return {...chatData, id: chat_id};
            }
        } catch (error) {
            console.error("❌ Error comprovant o creant el chat:", error);
            throw error;
        }
    };
    const getOppositeMessages = async (chatId, userId) => {
        if(!chatId){
            console.log("Id del xat no valido");
            return null;
        }

        const chatRef = doc(db, "chats", chatId);

        const chatSnapshot = await getDoc(chatRef);

        if (!chatSnapshot.exists()) {
            console.error("❌ El xat no existeix");
            return;
        }
        const chatData = chatSnapshot.data();
        // Almacenamos los mensajes
        const messagesObj = chatData.messages || {};

        // Convertim l'objecte de missatges en un array ordenat per data
        const messagesList = await Promise.all(
            Object.keys(messagesObj).map(async (key) => {
                const msg = {
                    id: key,
                    ...messagesObj[key]
                };

                if (msg.userId !== userId && !msg.read) {
                    await updateDoc(chatRef, {
                        [`messages.${key}.read`]: true
                    });
                }

                return msg;
            })
        );

        // Filtrar después de que todas las promesas se resuelvan
        const filteredMessages = messagesList
            .filter(msg => msg.userId !== userId && !msg.read)

    }
    const getMessages = (chatId, callback) => {
        // DOCUMENTAR MAÑANA
        // Validació del paràmetre d'entrada
        if (!chatId) {
            console.error("❌ ID de xat no vàlid");
            return null;
        }

        // Referència al document del xat
        const chatRef = doc(db, "chats", chatId);

        // Configurem un listener per actualitzacions en temps real
        const unsubscribe = onSnapshot(chatRef, (snapshot) => {
            if (snapshot.exists()) {
                // Obtenim les dades del xat
                const chatData = snapshot.data();

                // Obtenim l'objecte de missatges o un objecte buit si no existeix
                const messagesObj = chatData.messages || {};

                // Convertim l'objecte de missatges en un array ordenat per data
                const messagesList = Object.keys(messagesObj).map(key => ({
                    id: key,  // Guardem l'ID com a propietat
                    ...messagesObj[key],  // Expandim totes les propietats del missatge
                    // Convertim el timestamp de Firestore a objecte Date de JavaScript
                    timestamp: messagesObj[key].createdAt
                        ? new Date(messagesObj[key].createdAt.seconds * 1000)
                        : new Date()
                })).sort((a, b) => a.timestamp - b.timestamp);  // Ordenem cronològicament

                // Enviem els missatges processats a través del callback
                callback(messagesList);
            } else {
                console.log("❓ No s'han trobat missatges o el xat no existeix");
                callback([]);  // Retornem un array buit si no hi ha dades
            }
        }, (error) => {
            console.error("❌ Error obtenint missatges:", error);
        });

        // Retornem la funció per cancel·lar la subscripció
        return unsubscribe;
    }


// Funció per enviar missatge a Firestore
    const sendMessage = async (chatId, userId, text) => {
        try {
            const chatRef = doc(db, "chats", chatId);

            // Primer comprovem si el document del xat existeix
            const chatSnapshot = await getDoc(chatRef);

            if (!chatSnapshot.exists()) {
                console.error("❌ El xat no existeix");
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
                lastMessage: [userId, text]  // Forzar array
            });

            console.log("✔️ Missatge enviat amb èxit");
        } catch (error) {
            console.error("❌ Error al enviar missatge:", error);
        }
    }

    const getUserChats = (userId) => {
        const chatRef = collection(db, "chats");
        const q = query(chatRef, where("users", "array-contains", userId));

        // Usamos onSnapshot para escuchar cambios en la colección en tiempo real
        const unsubscribe = onSnapshot(q, async (querySnapshot) => {
            const chats = await Promise.all(querySnapshot.docs.map(async (doc) => {
                const chatData = doc.data();
                const productId = chatData.productId;

                // Obtener datos del producto asociado
                const productData = await getProduct(productId);

                let lastMessage = null;
                let userData = null;

                // Si existe un último mensaje, obtener los datos del usuario
                if (Array.isArray(chatData.lastMessage) && chatData.lastMessage.length >= 2) {
                    lastMessage = chatData.lastMessage;
                    userData = await getUser(lastMessage[0]);  // Suponiendo que el primer elemento es el `userId`
                }

                return {
                    id: doc.id,
                    product: productData,
                    lastMessage,  // Último mensaje
                    user: userData,  // Usuario asociado al último mensaje
                    ...chatData
                };
            }));

            // Actualizamos activeChats con los datos de los chats
            activeChats.value = chats;
        });

        // Retornamos la función para cancelar la suscripción si es necesario
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

    }

}
