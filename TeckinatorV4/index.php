<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Meta y enlaces -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teckinator</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Iconos de FontAwesome (opcional) -->
</head>
<body class="flex flex-col min-h-screen">

    <!-- Header adaptado a Teckinator -->
    <header class="bg-white shadow-md fixed w-full top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="index.html" class="text-2xl font-bold text-blue-600 flex items-center">
                <!-- Logo -->
                <img src="Recursos/Logo (2).png" alt="Teckinator Logo" class="w-8 h-8 mr-2">
                Teckinator
            </a>
            <!-- Menú para pantallas grandes -->
            <nav class="hidden md:flex space-x-6">
                <a href="index.html" class="text-gray-700 hover:text-blue-600">Inicio</a>
                <a href="about.html" class="text-gray-700 hover:text-blue-600">Acerca de</a>
                <a href="faq.html" class="text-gray-700 hover:text-blue-600">Preguntas Frecuentes</a>
                <a href="contact.html" class="text-gray-700 hover:text-blue-600">Contacto</a>
            </nav>
            <div class="hidden md:block">
                <a href="#" class="text-gray-700 hover:text-blue-600">Iniciar Sesión</a>
            </div>
            <!-- Botón del menú móvil -->
            <div class="md:hidden">
                <button id="menu-toggle" class="text-gray-700 hover:text-blue-600 focus:outline-none">
                    <i class="fas fa-bars fa-2x"></i>
                </button>
            </div>
        </div>
        <!-- Menú desplegable móvil -->
        <div id="mobile-menu" class="hidden md:hidden bg-white">
            <nav class="px-6 py-4">
                <a href="index.html" class="block text-gray-700 hover:text-blue-600 mb-2">Inicio</a>
                <a href="about.html" class="block text-gray-700 hover:text-blue-600 mb-2">Acerca de</a>
                <a href="faq.html" class="block text-gray-700 hover:text-blue-600 mb-2">Preguntas Frecuentes</a>
                <a href="contact.html" class="block text-gray-700 hover:text-blue-600 mb-2">Contacto</a>
                <a href="#" class="block text-gray-700 hover:text-blue-600">Iniciar Sesión</a>
            </nav>
        </div>
    </header>

    <!-- Espaciador para el header fijo -->
    <div class="pt-20"></div>

    <!-- Contenedor principal -->
    <div class="main-container flex-grow">
        <section class="intro text-center my-8">
            <h1 class="text-4xl font-bold text-blue-600 mb-4">Teckinator</h1>
            <!-- Logo debajo del título -->
            <img src="Recursos/Logo (2).png" alt="Teckinator Logo" class="mx-auto mb-4 w-24 h-24">
            <p class="text-xl text-gray-700">Descubre quién la persona que estas pensando</p>
        </section>

        <!-- Contenido principal del juego -->
        <div class="max-w-4xl mx-auto px-4">
            <!-- Contenedor del juego -->
            <div id="game-container" class="central bg-white p-6 rounded-lg shadow text-center">
                <p id="question" class="text-2xl font-semibold text-blue-600 mb-4">Presiona "Iniciar" para comenzar el juego.</p>
                <button onclick="startGame()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Iniciar</button>
                <div id="answers" class="flex justify-center space-x-4 mt-4 hidden">
                    <button onclick="sendAnswer(0)" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Sí</button>
                    <button onclick="sendAnswer(1)" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">No</button>
                    <button onclick="sendAnswer(2)" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">No sé</button>
                    <button onclick="sendAnswer(3)" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Probablemente sí</button>
                    <button onclick="sendAnswer(4)" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Probablemente no</button>
                </div>
                <div id="result" class="mt-6 hidden">
                    <h2 class="text-2xl font-semibold text-blue-600 mb-4">¡He adivinado!</h2>
                    <p id="character-name" class="text-xl font-bold mb-2"></p>
                    <p id="character-description" class="text-gray-700 mb-4"></p>
                    <img id="character-image" src="" alt="Imagen del personaje" class="mx-auto rounded-lg shadow">
                </div>
            </div>
        </div>
    </div>

    <!-- Footer adaptado a Teckinator -->
    <!-- Aquí puedes incluir el footer que tenías en tu proyecto original -->

    <!-- Scripts -->
    <!-- Tailwind CSS ya está incluido, pero si usas algún script adicional, inclúyelo aquí -->
    <!-- Por ejemplo, para el menú móvil -->
    <script>
        // Script para el menú móvil
        const menuToggle = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');

        menuToggle.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Lógica del juego
        let gameOver = false;

        function startGame() {
            fetch('http://localhost:5000/start', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                gameOver = false;
                document.getElementById('question').innerText = data.question;
                document.querySelector('button[onclick="startGame()"]').style.display = 'none';
                document.getElementById('answers').classList.remove('hidden');
            })
            .catch(error => console.error('Error al iniciar el juego:', error));
        }

        function sendAnswer(answer) {
            if (gameOver) {
                alert("El juego ha terminado. Por favor, inicia un nuevo juego.");
                return;
            }
            fetch('http://localhost:5000/answer', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ answer: answer })
            })
            .then(response => response.json())
            .then(data => {
                if (data.game_over) {
                    gameOver = true;
                    document.getElementById('question').style.display = 'none';
                    document.getElementById('answers').classList.add('hidden');
                    document.getElementById('result').classList.remove('hidden');
                    document.getElementById('character-name').innerText = data.result.name;
                    document.getElementById('character-description').innerText = data.result.description;
                    document.getElementById('character-image').src = data.result.image;
                } else {
                    document.getElementById('question').innerText = data.question;
                }
            })
            .catch(error => console.error('Error al enviar respuesta:', error));
        }
    </script>

     <!-- Footer adaptado a Teckinator -->
     <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-4">
            <!-- Redes Sociales -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center md:text-left">
                    <h3 class="text-lg font-semibold mb-4">Síguenos en redes</h3>
                    <div class="flex justify-center md:justify-start space-x-6">
                    <a href="https://facebook.com">
              <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/Facebook_f_logo_%282019%29.svg" alt="Facebook" class="w-8 h-8">
            </a>
            <a href="https://twitter.com">
              <img src="https://upload.wikimedia.org/wikipedia/commons/6/6f/Logo_of_Twitter.svg" alt="Twitter" class="w-8 h-8">
            </a>
            <a href="https://linkedin.com">
              <img src="https://upload.wikimedia.org/wikipedia/commons/e/e9/Linkedin_icon.svg" alt="LinkedIn" class="w-8 h-8">
            </a>
            <a href="https://instagram.com">
              <img src="https://upload.wikimedia.org/wikipedia/commons/a/a5/Instagram_icon.png" alt="Instagram" class="w-8 h-8">
            </a>
                    </div>
                </div>

                <!-- Información de contacto -->
                <div class="text-center">
                    <h3 class="text-lg font-semibold mb-4">Contacto</h3>
                    <p>Correo: <a href="mailto:contacto@teckinator.com" class="text-blue-400 hover:text-blue-500">contacto@teckinator.com</a></p>
                    <p>Teléfono: +52 800 123 4567</p>
                    <p>Dirección: Av. Educación 123, CDMX, México</p>
                </div>

                <!-- Descarga la App (si aplica) -->
                <!-- Puedes eliminar esta sección si no tienes una app -->
                <div class="text-center md:text-right">
                    <h3 class="text-lg font-semibold mb-4">Descarga la app</h3>
                    <div class="flex justify-center md:justify-end space-x-4">
                    <a href="https://play.google.com">
              <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Google Play" class="w-32 h-auto">
            </a>
            <a href="https://apple.com">
                <img src="https://upload.wikimedia.org/wikipedia/commons/6/67/App_Store_%28iOS%29.svg" alt="App Store" class="h-12">
            </a>
                    </div>
                </div>
            </div>


            <!-- Línea separadora -->
            <div class="w-full h-1 bg-white my-8"></div>

            <!-- Enlaces rápidos -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center md:text-left">
                <div>
                    <h3 class="text-lg font-semibold">Teckinator</h3>
                    <ul class="mt-2 space-y-2">
                        <li><a href="about.php" class="text-gray-400 hover:text-blue-400">Acerca de</a></li>
                        <li><a href="testimonios.php" class="text-gray-400 hover:text-blue-400">Testimonios</a></li>
                        <li><a href="blog.php" class="text-gray-400 hover:text-blue-400">Blog</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold">Soporte</h3>
                    <ul class="mt-2 space-y-2">
                        <li><a href="faq.php" class="text-gray-400 hover:text-blue-400">Ayuda</a></li>
                        <li><a href="privacidad.php" class="text-gray-400 hover:text-blue-400">Política de privacidad</a></li>
                        <li><a href="terminos.php" class="text-gray-400 hover:text-blue-400">Términos y condiciones</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold">Funcionalidades</h3>
                    <ul class="mt-2 space-y-2">
                        <li><a href="agendamiento.php" class="text-gray-400 hover:text-blue-400">Agendamiento</a></li>
                        <li><a href="historia-clinica.php" class="text-gray-400 hover:text-blue-400">Historia Clínica</a></li>
                        <li><a href="recordatorios.php" class="text-gray-400 hover:text-blue-400">Recordatorios</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold">Suscripción</h3>
                    <form class="mt-2">
                        <input type="email" class="p-2 rounded-lg w-full bg-gray-800 text-white" placeholder="Ingresa tu email">
                        <button type="submit" class="mt-2 w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Suscribirme</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sección dinámica con nombres -->
        <div class="mt-8 flex flex-col justify-center items-center px-4 md:px-8">
            <div class="text-white text-sm flex items-center animate-fade-in">
                <span>Teckinator hecho con ❤️ por </span>
                <span id="nombre" class="ml-1"></span>
            </div>
            <div>
                <img src="path_to_teckinator_logo" alt="Logo" class="w-10 h-10 object-contain mt-4">
            </div>
        </div>
    </footer>

    <!-- Animación y scripts -->
    <style>
        /* Animación de fade in/out */
        @keyframes fadeInOut {
            0% { opacity: 0; }
            50% { opacity: 1; }
            100% { opacity: 0; }
        }

        .animate-fade-in {
            animation: fadeInOut 5s infinite;
        }

        /* Estilos adicionales */
        footer a img {
            transition: transform 0.3s ease;
        }
        footer a img:hover {
            transform: scale(1.2);
        }
        footer a:hover {
            color: #007BFF;
        }
    </style>

    <script>
        // Lista de nombres que cambiarán dinámicamente
        const nombres = ["Luis Fernando Rodriguez Cruz"];
        let index = 0;

        // Función para cambiar los nombres
        function cambiarNombre() {
            document.getElementById("nombre").textContent = nombres[index];
            index = (index + 1) % nombres.length; // Cambia al siguiente nombre
        }

        // Cambiar el nombre cada 5 segundos
        setInterval(cambiarNombre, 5000);

        // Iniciar con el primer nombre
        cambiarNombre();
    </script>
</body>
</html>
