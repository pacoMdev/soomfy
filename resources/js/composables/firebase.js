// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import {ref} from "vue";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyCWsmiUVB20WRmJUam3W2eqa9wnEprKqus",
    authDomain: "soomfy-f0f44.firebaseapp.com",
    projectId: "soomfy-f0f44",
    storageBucket: "soomfy-f0f44.firebasestorage.app",
    messagingSenderId: "403822758985",
    appId: "1:403822758985:web:2a2cc0e83f7f52c25d0398"
};

// Initialize Firebase
const db = initializeApp(firebaseConfig);

const chats = ref ({
    id: null
})



const chatExists = (productId, compradorId, vendedorId) => {
    const vendedorId = usuario;
    const comprador


    const chatRef = db.database.ref(`chats`);
    // Recuperamos los datos del chat si existe
    return chatRef.once('value')
        .then((snapshot) => snapshot.exists())
        .then((exists) => {
            if (exists) {
                console.log("El chat existe");
            } else {
                console.log("El chat no existe");
                // Creamos el chat
                chatRef.set(
                    users: {
                        comprador:
                        vendedor:
                    }
                );
            }
        });

}
const sendMessage = (message) => {

}