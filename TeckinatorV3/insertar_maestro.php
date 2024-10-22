<?php
// insertar_maestro.php

require 'conexion.php';
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Meta y enlaces -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teckinator - Insertar Maestro</title>
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
                <img src="path_to_teckinator_logo" alt="Teckinator Logo" class="w-8 h-8 mr-2">
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
            <h1 class="text-4xl font-bold text-blue-600 mb-4">Insertar Nuevo Maestro</h1>
            <p class="text-xl text-gray-700">Utiliza este formulario para agregar un nuevo maestro y sus características.</p>
        </section>

        <!-- Contenido principal -->
        <div class="max-w-4xl mx-auto px-4">
            <div class="central bg-white p-6 rounded-lg shadow">

                <?php

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Procesar datos del formulario
                    $nombreMaestro = trim($_POST['nombre']);
                    $caracteristicas = array_map('trim', $_POST['caracteristicas']);

                    // Validar que el nombre del maestro y al menos una característica no estén vacíos
                    if (empty($nombreMaestro)) {
                        echo '<p class="text-red-500">El nombre del maestro es obligatorio.</p>';
                    } elseif (empty($caracteristicas) || count(array_filter($caracteristicas)) === 0) {
                        echo '<p class="text-red-500">Debe ingresar al menos una característica.</p>';
                    } else {
                        // Insertar el maestro
                        $stmt = $enlace->prepare("INSERT INTO maestros (nombre) VALUES (?)");
                        $stmt->bind_param("s", $nombreMaestro);
                        $stmt->execute();
                        $maestroId = $stmt->insert_id;
                        $stmt->close();

                        // Insertar características y asociarlas
                        foreach ($caracteristicas as $caracteristica) {
                            if (!empty($caracteristica)) {
                                // Verificar si la característica ya existe
                                $stmt = $enlace->prepare("SELECT id FROM caracteristicas WHERE descripcion = ?");
                                $stmt->bind_param("s", $caracteristica);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if ($result->num_rows > 0) {
                                    // La característica ya existe
                                    $row = $result->fetch_assoc();
                                    $caracteristicaId = $row['id'];
                                } else {
                                    // Insertar la nueva característica
                                    $stmtInsert = $enlace->prepare("INSERT INTO caracteristicas (descripcion) VALUES (?)");
                                    $stmtInsert->bind_param("s", $caracteristica);
                                    $stmtInsert->execute();
                                    $caracteristicaId = $stmtInsert->insert_id;
                                    $stmtInsert->close();
                                }
                                $stmt->close();

                                // Asociar el maestro con la característica
                                $stmtAssoc = $enlace->prepare("INSERT INTO maestro_caracteristica (maestro_id, caracteristica_id) VALUES (?, ?)");
                                $stmtAssoc->bind_param("ii", $maestroId, $caracteristicaId);
                                $stmtAssoc->execute();
                                $stmtAssoc->close();
                            }
                        }

                        // Mostrar mensaje de éxito
                        echo '<p class="text-green-500">Maestro <strong>' . htmlspecialchars($nombreMaestro) . '</strong> y sus características han sido agregados correctamente.</p>';
                        echo '<a href="insertar_maestro.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mt-4 inline-block">Agregar otro maestro</a>';
                    }
                } else {
                    // Mostrar formulario
                    ?>
                    <form action="insertar_maestro.php" method="POST" class="space-y-4">
                        <div>
                            <label for="nombre" class="block text-gray-700">Nombre del Maestro:</label>
                            <input type="text" name="nombre" id="nombre" placeholder="Ingresa el nombre del maestro" class="w-full p-2 border border-gray-300 rounded" required>
                        </div>
                        <div>
                            <label class="block text-gray-700">Características del Maestro:</label>
                            <p class="text-gray-600 text-sm mb-2">Ingrese hasta 5 características. Si ya existen en el sistema, se asociarán automáticamente.</p>
                            <div class="space-y-2">
                                <?php for ($i = 0; $i < 5; $i++): ?>
                                    <input type="text" name="caracteristicas[]" placeholder="Característica <?php echo $i + 1; ?>" class="w-full p-2 border border-gray-300 rounded">
                                <?php endfor; ?>
                            </div>
                        </div>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Agregar Maestro</button>
                    </form>
                    <?php
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
