<?php
// respuesta.php

require 'conexion.php';
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Meta y enlaces -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teckinator - Agregar Maestro</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Tu CSS personalizado (si tienes estilos adicionales) -->
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="flex flex-col min-h-screen">

    <!-- Header adaptado a Teckinator -->
    <header class="bg-white shadow-md fixed w-full top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold text-blue-600 flex items-center">
                <!-- Logo -->
                <img src="Recursos/Logo (2).png" alt="Teckinator Logo" class="w-8 h-8 mr-2">
                Teckinator
            </a>
            <!-- Menú para pantallas grandes -->
            <nav class="hidden md:flex space-x-6">
                <a href="index.php" class="text-gray-700 hover:text-blue-600">Inicio</a>
                <a href="about.php" class="text-gray-700 hover:text-blue-600">Acerca de</a>
                <a href="faq.php" class="text-gray-700 hover:text-blue-600">Preguntas Frecuentes</a>
                <a href="contact.php" class="text-gray-700 hover:text-blue-600">Contacto</a>
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
                <a href="index.php" class="block text-gray-700 hover:text-blue-600 mb-2">Inicio</a>
                <a href="about.php" class="block text-gray-700 hover:text-blue-600 mb-2">Acerca de</a>
                <a href="faq.php" class="block text-gray-700 hover:text-blue-600 mb-2">Preguntas Frecuentes</a>
                <a href="contact.php" class="block text-gray-700 hover:text-blue-600 mb-2">Contacto</a>
                <a href="#" class="block text-gray-700 hover:text-blue-600">Iniciar Sesión</a>
            </nav>
        </div>
    </header>

    <!-- Espaciador para el header fijo -->
    <div class="pt-24"></div>

    <!-- Contenido principal -->
    <div class="main-container flex-grow">
        <section class="intro text-center my-8">
            <h1 class="text-4xl font-bold text-blue-600 mb-4">Teckinator</h1>
            <!-- Logo debajo del título -->
            <img src="Recursos/Logo (2).png" alt="Teckinator Logo" class="mx-auto mb-4 w-24 h-24">
            <p class="text-xl text-gray-700">¡Ayúdanos a aprender más sobre tus maestros!</p>
        </section>

        <!-- Contenido principal -->
        <div class="max-w-4xl mx-auto px-4">
            <div class="central bg-white p-6 rounded-lg shadow">

                <?php

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Procesar datos del formulario
                    // Obtener datos del formulario
                    $nombreAnt = isset($_POST['nombreAnt']) ? $_POST['nombreAnt'] : null;
                    $nombreNuevo = trim($_POST['nombre']);
                    $caracteristicas = trim($_POST['caracteristicas']);

                    // Validar que el nombre del maestro y la característica no estén vacíos
                    if (empty($nombreNuevo)) {
                        echo '<p class="text-red-500">El nombre del maestro es obligatorio.</p>';
                    } elseif (empty($caracteristicas)) {
                        echo '<p class="text-red-500">La característica es obligatoria.</p>';
                    } else {
                        // Insertar la nueva característica en la tabla 'caracteristicas' si no existe
                        $stmt = $enlace->prepare("SELECT id FROM caracteristicas WHERE descripcion = ?");
                        $stmt->bind_param("s", $caracteristicas);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0) {
                            // La característica ya existe
                            $row = $result->fetch_assoc();
                            $caracteristicaId = $row['id'];
                        } else {
                            // Insertar la nueva característica
                            $stmt = $enlace->prepare("INSERT INTO caracteristicas (descripcion) VALUES (?)");
                            $stmt->bind_param("s", $caracteristicas);
                            $stmt->execute();
                            $caracteristicaId = $stmt->insert_id;
                        }
                        $stmt->close();

                        // Insertar el nuevo maestro en la tabla 'maestros'
                        $stmt = $enlace->prepare("INSERT INTO maestros (nombre) VALUES (?)");
                        $stmt->bind_param("s", $nombreNuevo);
                        $stmt->execute();
                        $maestroId = $stmt->insert_id;
                        $stmt->close();

                        // Asociar el maestro con la característica
                        $stmt = $enlace->prepare("INSERT INTO maestro_caracteristica (maestro_id, caracteristica_id) VALUES (?, ?)");
                        $stmt->bind_param("ii", $maestroId, $caracteristicaId);
                        $stmt->execute();
                        $stmt->close();

                        // Mostrar mensaje de éxito
                        echo '<p class="text-green-500">¡Gracias por tu ayuda! Hemos agregado a <strong>' . htmlspecialchars($nombreNuevo) . '</strong> con la característica: <strong>' . htmlspecialchars($caracteristicas) . '</strong>.</p>';
                        echo '<a href="index.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mt-4 inline-block">Volver al inicio</a>';

                        // Limpiar sesión
                        session_destroy();
                    }
                } else {
                    // Mostrar formulario
                    // Obtener datos de la URL si existen
                    $nodo = isset($_GET['n']) ? $_GET['n'] : null;
                    $respuesta = isset($_GET['r']) ? $_GET['r'] : null;
                    $nombre = isset($_GET['nom']) ? $_GET['nom'] : null;

                    function formularioRespuesta($nombreAnt = null) {
                        echo '<div class="contenedorPregunta mb-6">';
                        echo '<h2 class="text-2xl font-semibold text-blue-600 mb-4">Ayúdanos a mejorar</h2>';

                        if ($nombreAnt) {
                            echo '<p class="text-gray-700 mb-4">Pensé que tu maestro era <strong>' . htmlspecialchars($nombreAnt) . '</strong>. ¿En quién estabas pensando?</p>';
                        } else {
                            echo '<p class="text-gray-700 mb-4">¿En qué maestro estabas pensando?</p>';
                        }

                        // Formulario para agregar nuevo maestro
                        echo '<form action="respuesta.php" method="POST" class="space-y-4">';
                        // Campo oculto para el nombre del maestro que se pensó anteriormente
                        if ($nombreAnt) {
                            echo '<input type="hidden" name="nombreAnt" value="' . htmlspecialchars($nombreAnt) . '">';
                        }

                        // Campo para el nombre del nuevo maestro
                        echo '<div>';
                        echo '<label for="nombre" class="block text-gray-700">Nombre del Maestro:</label>';
                        echo '<input type="text" name="nombre" id="nombre" placeholder="Ingresa el nombre del maestro" class="w-full p-2 border border-gray-300 rounded" required>';
                        echo '</div>';

                        // Campo para agregar nueva característica
                        echo '<div>';
                        if ($nombreAnt) {
                            echo '<label for="caracteristicas" class="block text-gray-700">¿Qué tiene este maestro que no tenga ' . htmlspecialchars($nombreAnt) . '?</label>';
                        } else {
                            echo '<label for="caracteristicas" class="block text-gray-700">Ingresa una característica distintiva de este maestro:</label>';
                        }
                        echo '<input type="text" name="caracteristicas" id="caracteristicas" placeholder="Ingresa una característica" class="w-full p-2 border border-gray-300 rounded" required>';
                        echo '</div>';

                        // Botón de envío
                        echo '<button type="submit" name="enviar" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Enviar</button>';
                        echo '</form>';
                        echo '</div>';
                    }

                    formularioRespuesta($nombre);
                }

                ?>

            </div>
        </div>
    </div>

    <!-- Footer adaptado a Teckinator -->
    <!-- Puedes copiar el código del footer desde el index.php -->
    <!-- ...Código del footer... -->

    <!-- Scripts -->
    <script>
        // Menú móvil
        const menuToggle = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');

        menuToggle.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>

</body>
</html>
