<template>
    <div class="howto-container">
        <!-- Banner de la guía -->
        <div class="howto-banner">
            <div class="howto-banner-content">
                <h1>¿Cómo funciona Soomfy?</h1>
                <p>Tu guía completa para aprovechar al máximo nuestra plataforma</p>
            </div>
        </div>

        <div class="grid">
            <!-- Menú de navegación lateral -->
            <div class="col-12 md:col-3 lg:col-2">
                <div class="sticky-menu">
                    <div class="card">
                        <h3>Contenido</h3>
                        <div class="menu-list">
                            <ul>
                                <li v-for="(section, index) in sections" :key="index">
                                    <a :href="`#${section.id}`" :class="{ 'active': activeSection === section.id }">
                                        {{ section.title }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido principal del tutorial -->
            <div class="col-12 md:col-9 lg:col-10">
                <div class="card">
                    <!-- Introducción -->
                    <section id="introduccion" class="tutorial-section">
                        <h2>Bienvenido a Soomfy</h2>
                        <div class="section-content">
                            <p>
                                Soomfy es una plataforma intuitiva diseñada para hacer que tus compras en línea sean 
                                sencillas, seguras y agradables. Esta guía te ayudará a familiarizarte con todas 
                                nuestras funciones y facilitarte la navegación por nuestra plataforma.
                            </p>
                        </div>
                    </section>

                    <!-- Registro y cuenta -->
                    <section id="registro-cuenta" class="tutorial-section">
                        <h2>Registro y gestión de cuenta</h2>
                        <div class="section-content">
                            <h3>Crear una cuenta</h3>
                            <p>
                                Para comenzar a utilizar todas las funciones de Soomfy, regístrate siguiendo estos pasos:
                            </p>
                            <ol>
                                <li>Haz clic en el botón "Registrarse" en la esquina superior derecha</li>
                                <li>Completa el formulario con tu información personal</li>
                                <li>Verifica tu dirección de correo electrónico</li>
                                <li>¡Listo! Ya puedes acceder a tu cuenta personal</li>
                            </ol>
                            
                            <img src="/docs_howto/register.png" class = "responsiveImage" alt="Register">
                            
                            <h3>Iniciar sesión</h3>
                            <p>
                                Si ya tienes una cuenta, simplemente:
                            </p>
                            <ol>
                                <li>Haz clic en "Iniciar sesión" en la esquina superior derecha</li>
                                <li>Ingresa tu correo electrónico y contraseña</li>
                                <li>Haz clic en "Acceder"</li>
                            </ol>
                            
                            <h3>Gestionar tu perfil</h3>
                            <p>
                                Puedes actualizar tu información personal, direcciones de envío y preferencias 
                                en cualquier momento desde el panel "Mi cuenta".
                            </p>
                            
                            <img src="/docs_howto/profile.png" class = "responsiveImage" alt="Register">

                        </div>
                    </section>

                    <!-- Navegación -->
                    <section id="navegacion" class="tutorial-section">
                        <h2>Navegación por Soomfy</h2>
                        <div class="section-content">
                            <h3>Menú principal</h3>
                            <p>
                                El menú principal está siempre visible en la parte superior y te permite acceder rápidamente a:
                            </p>
                            <ul>
                                <li><strong>Inicio:</strong> La página principal con las ofertas y productos destacados</li>
                                <li><strong>Categorías:</strong> Explora productos por categorías</li>
                                <li><strong>Ofertas:</strong> Descubre los mejores descuentos</li>
                                <li><strong>Mi cuenta:</strong> Accede a tu perfil, pedidos y preferencias</li>
                                <li><strong>Carrito:</strong> Revisa los productos que has seleccionado para comprar</li>
                            </ul>
                            
                            <img src="/docs_howto/nav.png" class = "responsiveImage" alt="Register">

                        </div>
                    </section>

                    <!-- Búsqueda y filtros -->
                    <section id="busqueda-filtros" class="tutorial-section">
                        <h2>Búsqueda y filtros</h2>
                        <div class="section-content">
                            <h3>Buscar productos</h3>
                            <p>
                                Encuentra rápidamente lo que estás buscando:
                            </p>
                            <ol>
                                <li>Utiliza la barra de búsqueda en la parte superior</li>
                                <li>Escribe palabras clave relacionadas con el producto que buscas</li>
                                <li>Presiona Enter o haz clic en el icono de lupa</li>
                            </ol>
                            
                            <img src="/docs_howto/search.png" class = "responsiveImage" alt="Register">

                            
                            <h3>Aplicar filtros</h3>
                            <p>
                                Refina tus resultados de búsqueda utilizando los filtros disponibles:
                            </p>
                            <ul>
                                <li><strong>Categoría:</strong> Selecciona la categoría específica</li>
                                <li><strong>Precio:</strong> Establece un rango de precios</li>
                                <li><strong>Valoraciones:</strong> Filtra por calificación de estrellas</li>
                                <li><strong>Marca:</strong> Selecciona tus marcas preferidas</li>
                                <li><strong>Ordenar por:</strong> Organiza los resultados por relevancia, precio o popularidad</li>
                            </ul>
                            
                            <img src="/docs_howto/products.png" class = "responsiveImage" alt="Register">

                        </div>
                    </section>

                    <!-- Productos -->
                    <section id="productos" class="tutorial-section">
                        <h2>Explorar y seleccionar productos</h2>
                        <div class="section-content">
                            <h3>Detalles del producto</h3>
                            <p>
                                Para ver toda la información sobre un producto:
                            </p>
                            <ol>
                                <li>Haz clic en la imagen o nombre del producto que te interesa</li>
                                <li>En la página de detalles encontrarás:</li>
                                <ul>
                                    <li>Imágenes detalladas del producto</li>
                                    <li>Precio y disponibilidad</li>
                                    <li>Especificaciones técnicas</li>
                                    <li>Opiniones de otros compradores</li>
                                    <li>Productos relacionados</li>
                                </ul>
                            </ol>
                            
                            <img src="/docs_howto/detail.png" class = "responsiveImage" alt="Register">

                        </div>
                    </section>

                    <!-- Chat -->
                    <section id="chat-soporte" class="tutorial-section">
                        <h2>Chat</h2>
                        <div class="section-content">
                            <h3>Chat con vendedores</h3>
                            <p>
                                Puedes comunicarte directamente con los vendedores para resolver dudas:
                            </p>
                            <ol>
                                <li>En la página de un producto, busca el botón "Chatear con vendedor"</li>
                                <li>Escribe tu consulta en el cuadro de texto</li>
                                <li>Espera la respuesta del vendedor (recibirás una notificación)</li>
                            </ol>
                            
                            <img src="/docs_howto/chat.png" class = "responsiveImage" alt="Register">

                        </div>
                    </section>

                    <!-- Proceso de compra -->
                    <section id="proceso-compra" class="tutorial-section">
                        <h2>Proceso de compra</h2>
                        <div class="section-content">
                            <h3>Contactar con el vendedor</h3>
                            <p>
                                Para comprar un producto que te interesa:
                            </p>
                            <ol>
                                <li>En la página del producto, haz clic en "Chat"</li>
                                <li>Usa el chat para negociar los detalles con el vendedor</li>
                                <li>Acuerda el precio final y método de entrega</li>
                            </ol>
                            
                            <img src="/docs_howto/sell_buy.png" class = "responsiveImage" alt="Register">

                            
                            <h3>Realizar el pago</h3>
                            <p>
                                Una vez que hayas acordado los términos con el vendedor:
                            </p>
                            <ol>
                                <li>Haz clic en "Comprar ahora"</li>
                                <li>Selecciona el método de pago preferido</li>
                                <li>Confirma los detalles de envío o recogida</li>
                                <li>Completa la transacción</li>
                                <li>El dinero queda retenido hasta que confirmes la recepción del artículo</li>
                            </ol>
                            
                            <img src="/docs_howto/transaction.png" class = "responsiveImage" alt="Register">

                            
                            <h3>Seguimiento y recepción</h3>
                            <p>
                                Para finalizar el proceso correctamente:
                            </p>
                            <ol>
                                <li>Mantente en contacto con el vendedor a través del chat</li>
                                <li>Cuando recibas el producto, verifica que esté en las condiciones acordadas</li>
                                <li>Confirma la recepción para liberar el pago al vendedor</li>
                                <li>Deja una valoración al vendedor para ayudar a otros compradores</li>
                            </ol>
                            
                            <img src="/docs_howto/historic.png" class = "responsiveImage" alt="Register">
                            <img src="/docs_howto/resumeHistoric.png" class = "responsiveImage" alt="Register">

                        </div>
                    </section>

                    <!-- Consejos y trucos -->
                    <section id="consejos" class="tutorial-section">
                        <h2>Consejos y trucos para sacar el máximo provecho</h2>
                        <div class="section-content">
                            <h3>Pagina de favoritos</h3>
                            <p>
                                Guarda productos en favoritos para comprarlos más tarde:
                            </p>
                            <ul>
                                <li>Haz clic en el icono de corazón en cualquier producto</li>
                                <li>Accede a tu lista de favoritos desde cualquier pagina accediendo desde el menu superior de la pagina</li>
                            </ul>
                            
                            <h3>Reseñas y valoraciones</h3>
                            <p>
                                Comparte tu experiencia y ayuda a otros compradores:
                            </p>
                            <ul>
                                <li>Después de recibir tu pedido, recibirás una invitación para valorar el producto</li>
                                <li>Las reseñas honestas ayudan a la comunidad</li>
                                <li>Puedes incluir fotos en tus valoraciones</li>
                            </ul>
                            
                            <img src="/docs_howto/opinion.png" class = "responsiveImage" alt="Register">

                        </div>
                    </section>

                    <!-- Sobre nosotros -->
                    <section id="sobre-nosotros" class="tutorial-section">
                        <h2>Sobre Nosotros</h2>
                        <div class="section-content">
                            <p>
                                Soomfy nació con una idea clara: crear un espacio donde las personas puedan dar una segunda vida a aquellos 
                                objetos que ya no utilizan, promoviendo un consumo más responsable y sostenible.
                            </p>
                            <p>
                                Somos una plataforma de compraventa de artículos de segunda mano que conecta a personas con intereses comunes, 
                                fomentando transacciones seguras y transparentes entre usuarios.
                            </p>
                            <h3>Nuestra misión</h3>
                            <p>
                                Crear una comunidad donde comprar y vender artículos usados sea tan fácil y seguro como hacerlo con nuevos, 
                                contribuyendo a un modelo económico más circular y sostenible.
                            </p>
                            <h3>Nuestros valores</h3>
                            <ul>
                                <li><strong>Sostenibilidad:</strong> Extender la vida útil de los productos para reducir el impacto ambiental.</li>
                                <li><strong>Transparencia:</strong> Ofrecer información clara y honesta en todas las transacciones.</li>
                                <li><strong>Comunidad:</strong> Fomentar relaciones de confianza entre compradores y vendedores.</li>
                                <li><strong>Innovación:</strong> Mejorar constantemente la experiencia de usuario en nuestra plataforma.</li>
                                <li><strong>Seguridad:</strong> Garantizar que todas las transacciones sean seguras para nuestros usuarios.</li>
                            </ul>
                            <h3>¿Por qué elegir Soomfy?</h3>
                            <p>
                                Porque garantizamos que solo pagará por el producto cuando lo reciba y confirme que está en las condiciones acordadas.
                                Nuestro servicio de intermediación protege tanto al comprador como al vendedor, asegurando transacciones justas y transparentes.
                            </p>
                        </div>
                    </section>

                    <!-- Legal -->
                    <section id="legal" class="tutorial-section">
                        <h2>Información Legal</h2>
                        <div class="section-content">
                            <h3>Términos y condiciones</h3>
                            <p>
                                Al utilizar Soomfy, aceptas cumplir con nuestros términos y condiciones, que establecen las 
                                reglas para el uso de la plataforma y los derechos y obligaciones de los usuarios.
                            </p>
                            <h4>Registro y cuenta</h4>
                            <p>
                                Para utilizar Soomfy, debes:
                            </p>
                            <ul>
                                <li>Ser mayor de 18 años o tener autorización de un tutor legal.</li>
                                <li>Proporcionar información veraz y completa durante el registro.</li>
                                <li>Mantener la confidencialidad de tu información de acceso.</li>
                                <li>Ser responsable de todas las actividades realizadas con tu cuenta.</li>
                            </ul>
                            
                            <h4>Publicación de artículos</h4>
                            <p>
                                Al publicar productos en Soomfy:
                            </p>
                            <ul>
                                <li>Garantizas que eres el propietario legítimo o estás autorizado para venderlos.</li>
                                <li>Te comprometes a proporcionar descripciones precisas y detalladas.</li>
                                <li>Aceptas no publicar artículos prohibidos por la ley o nuestras políticas.</li>
                                <li>Reconoces que Soomfy puede retirar cualquier anuncio que incumpla estas condiciones.</li>
                            </ul>
                            
                            <h3>Política de privacidad</h3>
                            <p>
                                En Soomfy respetamos tu privacidad y nos comprometemos a proteger tus datos personales 
                                de acuerdo con la normativa vigente.
                            </p>
                            <h4>Datos que recopilamos</h4>
                            <ul>
                                <li>Información de registro: nombre, correo electrónico, dirección, teléfono.</li>
                                <li>Datos de transacciones: historial de compras y ventas.</li>
                                <li>Información de uso: interacciones con la plataforma, productos visitados.</li>
                                <li>Datos de ubicación: para facilitar transacciones locales.</li>
                            </ul>
                            
                            <h4>Uso de la información</h4>
                            <p>
                                Utilizamos tus datos para:
                            </p>
                            <ul>
                                <li>Gestionar tu cuenta y permitir el uso de la plataforma.</li>
                                <li>Facilitar transacciones entre usuarios.</li>
                                <li>Mejorar nuestros servicios y personalizar tu experiencia.</li>
                                <li>Prevenir actividades fraudulentas y proteger la seguridad de los usuarios.</li>
                                <li>Cumplir con obligaciones legales.</li>
                            </ul>
                            
                            <h3>Propiedad intelectual</h3>
                            <p>
                                Todo el contenido de Soomfy, incluyendo logotipos, diseños, textos y software, está 
                                protegido por derechos de propiedad intelectual y pertenece a nuestra empresa o a sus licenciantes.
                            </p>
                        </div>
                    </section>

                    <!-- Devoluciones -->
                    <section id="devoluciones" class="tutorial-section">
                        <h2>Política de Devoluciones</h2>
                        <div class="section-content">
                            <h3>Aviso importante</h3>
                            <p>
                                En Soomfy, actuamos únicamente como plataforma intermediaria entre compradores y vendedores. 
                                <strong>No nos hacemos responsables de las devoluciones</strong> de los productos adquiridos a través de nuestra plataforma.
                            </p>
                            
                            <h3>Gestión de devoluciones</h3>
                            <p>
                                Si has recibido un producto que no cumple con tus expectativas o presenta algún problema:
                            </p>
                            <ol>
                                <li>Debes contactar directamente con el vendedor a través del chat de la plataforma.</li>
                                <li>En caso de problemas con el envío, contacta con la empresa de correos o servicio de mensajería utilizado.</li>
                                <li>Cualquier acuerdo de devolución debe ser negociado directamente entre comprador y vendedor.</li>
                            </ol>
                            
                            <h3>Recomendaciones</h3>
                            <p>
                                Para evitar problemas con tus compras:
                            </p>
                            <ul>
                                <li>Antes de comprar, pregunta al vendedor sobre su política de devoluciones.</li>
                                <li>Solicita imágenes adicionales del producto si tienes dudas sobre su estado.</li>
                                <li>Confirma los detalles de envío y seguro en caso de que el producto sufra daños durante el transporte.</li>
                                <li>Conserva todos los comprobantes de pago y conversaciones con el vendedor.</li>
                            </ul>
                            
                            <h3>Rol de Soomfy</h3>
                            <p>
                                Nuestra plataforma:
                            </p>
                            <ul>
                                <li>Facilita la comunicación entre compradores y vendedores.</li>
                                <li>Proporciona un espacio seguro para realizar transacciones.</li>
                                <li>No interviene en disputas relacionadas con devoluciones.</li>
                                <li>No asume responsabilidad por la calidad, estado o envío de los productos.</li>
                            </ul>                            
                            <p>
                                <strong>Recuerda:</strong> Al utilizar nuestra plataforma, aceptas que cualquier problema con un producto 
                                debe ser resuelto directamente con el vendedor o con la empresa de transporte correspondiente.
                            </p>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import './howto.css'; // Importamos el archivo CSS externo

// Secciones del tutorial para el menú de navegación
const sections = [
    { id: 'introduccion', title: 'Introducción' },
    { id: 'registro-cuenta', title: 'Registro y cuenta' },
    { id: 'navegacion', title: 'Navegación' },
    { id: 'busqueda-filtros', title: 'Búsqueda y filtros' },
    { id: 'productos', title: 'Explorar productos' },
    { id: 'chat-soporte', title: 'Chat y soporte' },
    { id: 'proceso-compra', title: 'Proceso de compra' },
    { id: 'consejos', title: 'Consejos y trucos' },
    { id: 'sobre-nosotros', title: 'Sobre nosotros' },
    { id: 'legal', title: 'Legal' },
    { id: 'devoluciones', title: 'Devoluciones' }
];

// Control de sección activa para el menú
const activeSection = ref('introduccion');

// Función para actualizar la sección activa basada en el scroll
const handleScroll = () => {
    const sectionElements = document.querySelectorAll('.tutorial-section');
    const scrollPosition = window.scrollY;
    
    // Determinar qué sección está visible actualmente
    for (let i = sectionElements.length - 1; i >= 0; i--) {
        const section = sectionElements[i];
        const sectionTop = section.offsetTop - 100;
        
        if (scrollPosition >= sectionTop) {
            activeSection.value = section.id;
            break;
        }
    }
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
    handleScroll(); // Inicializar la sección activa
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>
