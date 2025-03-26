import {onMounted, ref} from 'vue';
import { initializeApp } from "firebase/app";
import {
    getFirestore, doc, getDoc, getDocs, updateDoc, setDoc, collection, addDoc, where, serverTimestamp, query, orderBy, onSnapshot
} from "firebase/firestore";

import useProducts from "@/composables/products.js";

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
    };


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

            // Comprovem si l'objecte messages existeix al document
            const chatData = chatSnapshot.data();

            if (!chatData.messages) {
                // Si messages no existeix, l'inicialitzem amb el primer missatge
                await updateDoc(chatRef, {
                    messages: {
                        [messageId]: newMessage
                    }
                });
            } else {
                // Si ja existeix, només afegim el nou missatge
                await updateDoc(chatRef, {
                    [`messages.${messageId}`]: newMessage
                });
            }

            console.log("✔️ Missatge enviat amb èxit");
        } catch (error) {
            console.error("❌ Error al enviar missatge:", error);
        }
    }

    const getUserChats = async (userId) => {
        const chatRef = collection(db, "chats"); // Referència a la col·lecció
        const q = query(chatRef, where("users", "array-contains", userId)); // ✅ Query correcta
        const querySnapshot = await getDocs(q); // ✅ Executem la consulta

        const chats = await Promise.all(querySnapshot.docs.map(async (doc) => {
            const chatData = doc.data();
            console.log("chat data", chatData);
            console.log("chat data productId", chatData.productId);

            const productoId = chatData.productId;
            // Esperamos que getProduct obtenga el producto y lo asignamos
            const productData = await getProduct(productoId);
            console.log("RESPUESTA GETPRODUCT", productData);

            // Retornamos el chat con la información del producto
            return {
                id: doc.id,
                product: productData,  // Usamos el producto obtenido
                ...chatData
            };
        }));

        return chats;

    }
    return {
        chats,
        chatExists,
        sendMessage,
        getMessages,
        getUserChats

    }

}
