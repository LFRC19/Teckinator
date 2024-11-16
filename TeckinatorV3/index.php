<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta description="Teckinator: Sistema de preguntas y respuestas sobre tus maestros.">
    <meta name="keywords" content="teckinator, preguntas, maestros">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <title>Proyecto Teckinator</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Tu CSS personalizado (si tienes estilos adicionales) -->
    <link rel="stylesheet" type="text/css" href="css/Teckinator.css?v=<?php echo time(); ?>">
</head>
<body class="flex flex-col min-h-screen">

    <!-- Header adaptado a Teckinator -->
    <header class="bg-white shadow-md fixed w-full top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="index.php" class="text-xl font-bold text-blue-600">Teckinator</a>
            <nav class="space-x-6">
                <a href="index.php" class="text-gray-700 hover:text-blue-600">Inicio</a>
                <a href="about.php" class="text-gray-700 hover:text-blue-600">Acerca de</a>
                <a href="faq.php" class="text-gray-700 hover:text-blue-600">Preguntas Frecuentes</a>
                <a href="contact.php" class="text-gray-700 hover:text-blue-600">Contacto</a>
            </nav>
            <div>
                <a href="#" class="text-gray-700 hover:text-blue-600">Iniciar Sesión</a>
            </div>
        </div>
    </header>

    <!-- Espaciador para que el contenido no quede detrás del header fijo -->
    <div class="pt-20"></div>

      <!-- Contenedor principal con contenido centrado -->
      <div class="main-container flex-grow">
        <section class="intro text-center my-8">
            <h1 class="text-4xl font-bold text-blue-600 mb-4">Teckinator</h1>
            <!-- Logo debajo del título -->
            <img src="path_to_teckinator_logo" alt="Teckinator Logo" class="mx-auto mb-4 w-24 h-24">
            <p class="text-xl text-gray-700">Descubre quién es tu maestro a través de nuestras preguntas interactivas</p>
        </section>

        <div class="main-content max-w-6xl mx-auto px-4 flex flex-col md:flex-row justify-between gap-6">
            <!-- Lateral izquierdo -->
            <aside class="lateral izquierda w-full md:w-1/4 bg-white p-6 rounded-lg shadow">
                <h3 class="text-2xl font-semibold text-blue-600 mb-4">Ranking de Maestros</h3>
                <ul class="space-y-2 text-gray-700">
                    <li>Maestro 1: 150 veces adivinado</li>
                    <li>Maestro 2: 120 veces adivinado</li>
                    <li>Maestro 3: 110 veces adivinado</li>
                </ul>
            </aside>

            <!-- Contenido central (pregunta dinámica) -->
            <main class="central w-full md:w-1/2 bg-white p-6 rounded-lg shadow">
                <section>
                    <!-- Aquí es donde el contenido dinámico PHP entra en juego -->
                    <?php
                    // Conexión a la base de datos
                    require 'conexion.php'; 

                    // Valor por defecto para el nodo
                    $nodo = 1; 
                    
                    // Revisamos si el parámetro 'n' está en la URL
                    if (isset($_GET['n'])) {
                        $nodo = $_GET['n'];
                    }

                    // Definimos los nodos siguientes
                    $nodoSi = $nodo * 2;
                    $nodoNo = $nodo * 2 + 1;

                    // Consulta a la base de datos
                    $consulta = "SELECT texto, pregunta FROM arbol WHERE nodo=" . $nodo . ";";
                    $texto = '';
                    $pregunta = true;

                    if ($resultado = mysqli_query($enlace, $consulta)) {
                        if ($resultado->num_rows === 0) {
                            echo '<div class="error">ERROR: EL NODO NO EXISTE</div>';
                        } else {
                            while ($fila = mysqli_fetch_row($resultado)) {
                                $texto = $fila[0];
                                $pregunta = $fila[1];
                            }
                            // Si es un nodo de pregunta final
                            if ($pregunta == FALSE) {
                                echo "<div class='contenedorPregunta mb-4'>";
                                echo "<h2 class='text-2xl font-semibold text-blue-600'>¡Ya lo tengo, tu maestro es $texto!</h2>";
                                echo "</div>";

                                echo "<div class='contenedorRespuestas flex justify-center space-x-4'>";
                                echo "<a class='boton bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700' href='respuesta.php?n=" . $nodo . "&r=1&nom=" . $texto . "'>SI</a>";
                                echo "<a class='boton bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700' href='respuesta.php?n=" . $nodo . "&r=0&nom=" . $texto . "'>NO</a>";
                                echo "</div>";
                            } else {
                                // Si es una pregunta intermedia
                                echo "<div class='contenedorPregunta mb-4'>";
                                echo "<h2 class='text-2xl font-semibold text-blue-600'>$texto</h2>";
                                echo "</div>";

                                echo "<div class='contenedorRespuestas flex justify-center space-x-4'>";
                                echo "<a class='boton bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700' href='index.php?n=" . $nodoSi . "'>SI</a>";
                                echo "<a class='boton bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700' href='index.php?n=" . $nodoNo . "'>NO</a>";
                                echo "</div>";
                            }
                        }
                    }
                    ?>
                </section>
            </main>

            <!-- Lateral derecho -->
            <aside class="lateral derecha w-full md:w-1/4 bg-white p-6 rounded-lg shadow">
                <h3 class="text-2xl font-semibold text-blue-600 mb-4">Dato Curioso</h3>
                <p class="text-gray-700">"Los maestros son la base del conocimiento, ¡descubre el tuyo!"</p>
            </aside>
        </div>
    </div>

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
            <hr class="border-t-2 border-white my-8">

            <!-- Texto interactivo con efecto -->
            <div class="relative text-center my-12">
                <p class="text-gray-400 text-sm">¿Listo para descubrir a tu maestro?</p>
                <a href="index.php" class="block text-4xl font-bold hover:text-blue-500 transition-transform transform hover:scale-110">
                    ¡Comienza aquí!
                </a>
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
        const nombres = ["Luis Fernando Rodriguez Cruz", "Nayeli Ortiz Garcia", "Jose Eduardo Marquez Santillan", "Maria de los Angeles Diaz Berumen", "Donovan Rojo Chaidez","Leidy Valle","Abraham Ezequiel Garcia Campos"];
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
