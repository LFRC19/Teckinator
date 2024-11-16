<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Meta y enlaces -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teckinator - Adivina al Maestro</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
    <div class="pt-20"></div>

    <!-- Contenedor principal -->
    <div class="main-container flex-grow">
        <section class="intro text-center my-8">
            <h1 class="text-4xl font-bold text-blue-600 mb-4">Teckinator</h1>
            <!-- Logo debajo del título -->
            <img src="Recursos/Logo (2).png" alt="Teckinator Logo" class="mx-auto mb-4 w-24 h-24">
            <p class="text-xl text-gray-700">Descubre quién es tu maestro a través de nuestras preguntas interactivas</p>
        </section>

        <!-- Contenido principal -->
        <div class="max-w-4xl mx-auto px-4">
            <?php
            require 'conexion.php';

            session_start();

            // Inicializar sesión si no existe
            if (!isset($_SESSION['maestros_posibles'])) {
                // Obtener todos los maestros
                $consultaMaestros = "SELECT id FROM maestros";
                $resultadoMaestros = mysqli_query($enlace, $consultaMaestros);

                $maestrosPosibles = [];
                while ($fila = mysqli_fetch_assoc($resultadoMaestros)) {
                    $maestrosPosibles[] = $fila['id'];
                }
                $_SESSION['maestros_posibles'] = $maestrosPosibles;
                $_SESSION['caracteristicas_preguntadas'] = [];
            } else {
                $maestrosPosibles = $_SESSION['maestros_posibles'];
            }

            // Procesar respuesta del usuario
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $respuesta = $_POST['respuesta'];
                $caracteristicaId = $_POST['caracteristica_id'];

                // Guardar la característica preguntada
                $_SESSION['caracteristicas_preguntadas'][] = $caracteristicaId;

                if ($respuesta === 'si') {
                    // Filtrar maestros que tengan la característica
                    $consulta = "
                        SELECT mc.maestro_id
                        FROM maestro_caracteristica mc
                        WHERE mc.caracteristica_id = $caracteristicaId
                        AND mc.maestro_id IN (" . implode(',', $maestrosPosibles) . ")
                    ";
                } else {
                    // Filtrar maestros que NO tengan la característica
                    $consulta = "
                        SELECT m.id AS maestro_id
                        FROM maestros m
                        WHERE m.id IN (" . implode(',', $maestrosPosibles) . ")
                        AND m.id NOT IN (
                            SELECT mc.maestro_id
                            FROM maestro_caracteristica mc
                            WHERE mc.caracteristica_id = $caracteristicaId
                        )
                    ";
                }

                $resultado = mysqli_query($enlace, $consulta);

                $maestrosPosibles = [];
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    $maestrosPosibles[] = $fila['maestro_id'];
                }

                $_SESSION['maestros_posibles'] = $maestrosPosibles;
            }

            // Verificar si queda un solo maestro
            if (count($maestrosPosibles) === 1) {
                // Obtener el nombre del maestro
                $maestroId = $maestrosPosibles[0];
                $consulta = "SELECT nombre FROM maestros WHERE id = $maestroId";
                $resultado = mysqli_query($enlace, $consulta);
                $fila = mysqli_fetch_assoc($resultado);
                $nombreMaestro = $fila['nombre'];

                // Mostrar resultado
                echo '<div class="central bg-white p-6 rounded-lg shadow text-center">';
                echo '<h2 class="text-2xl font-semibold text-blue-600 mb-4">¡Ya lo tengo, tu maestro es ' . htmlspecialchars($nombreMaestro) . '!</h2>';
                echo '<div class="flex justify-center space-x-4">';
                echo '<a href="respuesta.php?r=1&nom=' . urlencode($nombreMaestro) . '" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Sí</a>';
                echo '<a href="respuesta.php?r=0&nom=' . urlencode($nombreMaestro) . '" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">No</a>';
                echo '</div>';
                echo '</div>';

                // Reiniciar sesión
                session_destroy();
            } elseif (count($maestrosPosibles) === 0) {
                // No se encontró ningún maestro
                echo '<div class="central bg-white p-6 rounded-lg shadow text-center">';
                echo '<h2 class="text-2xl font-semibold text-blue-600 mb-4">No pude adivinar tu maestro.</h2>';
                echo '<p class="text-gray-700 mb-4">Ayúdanos a mejorar añadiendo información sobre tu maestro.</p>';
                echo '<a href="respuesta.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Agregar Maestro</a>';
                echo '</div>';

                // Reiniciar sesión
                session_destroy();
            } else {
                // Obtener la siguiente característica a preguntar
                $caracteristicaId = obtenerSiguienteCaracteristica($enlace, $maestrosPosibles, $_SESSION['caracteristicas_preguntadas']);

                if ($caracteristicaId === null) {
                    // No hay más características para preguntar
                    echo '<div class="central bg-white p-6 rounded-lg shadow text-center">';
                    echo '<h2 class="text-2xl font-semibold text-blue-600 mb-4">No pude adivinar tu maestro.</h2>';
                    echo '<p class="text-gray-700 mb-4">Ayúdanos a mejorar añadiendo información sobre tu maestro.</p>';
                    echo '<a href="respuesta.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Agregar Maestro</a>';
                    echo '</div>';

                    // Reiniciar sesión
                    session_destroy();
                } else {
                    // Mostrar pregunta
                    $consulta = "SELECT descripcion FROM caracteristicas WHERE id = $caracteristicaId";
                    $resultado = mysqli_query($enlace, $consulta);
                    $fila = mysqli_fetch_assoc($resultado);
                    $descripcion = $fila['descripcion'];

                    echo '<div class="central bg-white p-6 rounded-lg shadow text-center">';
                    echo '<h2 class="text-2xl font-semibold text-blue-600 mb-4">¿Tu maestro ' . htmlspecialchars($descripcion) . '?</h2>';
                    echo '<form method="POST" class="flex justify-center space-x-4">';
                    echo '<input type="hidden" name="caracteristica_id" value="' . $caracteristicaId . '">';
                    echo '<button type="submit" name="respuesta" value="si" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Sí</button>';
                    echo '<button type="submit" name="respuesta" value="no" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">No</button>';
                    echo '</form>';
                    echo '</div>';
                }
            }

            // Función para obtener la siguiente característica a preguntar
            function obtenerSiguienteCaracteristica($enlace, $maestrosPosibles, $caracteristicasPreguntadas) {
                // Obtener todas las características asociadas a los maestros posibles
                $consulta = "
                    SELECT mc.caracteristica_id, COUNT(*) AS total
                    FROM maestro_caracteristica mc
                    WHERE mc.maestro_id IN (" . implode(',', $maestrosPosibles) . ")
                    GROUP BY mc.caracteristica_id
                    ORDER BY total DESC
                ";
                $resultado = mysqli_query($enlace, $consulta);

                while ($fila = mysqli_fetch_assoc($resultado)) {
                    $caractId = $fila['caracteristica_id'];
                    if (!in_array($caractId, $caracteristicasPreguntadas)) {
                        return $caractId;
                    }
                }
                return null; // No hay más características para preguntar
            }
            ?>
        </div>
    </div>

    <!-- Footer adaptado a Teckinator -->
    <!-- Puedes copiar el código del footer desde el index anterior -->

    <!-- ...Código del footer... -->

    <!-- Animaciones y scripts (si los tienes) -->
    <!-- ...Scripts y estilos adicionales... -->

</body>
</html>
