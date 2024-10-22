<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Teckinator: Sistema de preguntas y respuestas sobre tus maestros.">
    <meta name="keywords" content="teckinator, preguntas, maestros">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <title>Proyecto Teckinator</title>
    <link rel="stylesheet" type="text/css" href="css/Teckinator.css">
</head>

<body>

    <header>
        <h1>Teckinator</h1>
        <p>Descubre quién es tu maestro a través de nuestras preguntas interactivas</p>
    </header>

    <main>
        <section>
            <h2>PREGUNTA</h2>

            <?php
            //CONEXION CON LA BASE DE DATOS
            require 'conexion.php';

            //RECOGER EL NUMERO DE NODO DE LA URL (PARAMETRO)
            $nodo = 1; //VALOR POR DEFECTO, EMPEZAMOS EN 1

            if (isset($_GET['n'])) {
                $nodo = $_GET['n'];
            }

            //CALCULAR LOS SIGUIENTES NODOS (SI - NO)
            $nodoSi = $nodo * 2;
            $nodoNo = $nodo * 2 + 1;

            //Consulta a la base de DATOS
            $consulta = "SELECT texto,pregunta FROM arbol WHERE nodo=" . $nodo . ";";
            $texto = '';
            $pregunta = true;

            $resultado;

            if ($resultado = mysqli_query($enlace, $consulta)) {

                if ($resultado->num_rows === 0) {
                    echo '<div class="error">ERROR: EL NODO NO EXISTE</div>';
                } else {
                    while ($fila = mysqli_fetch_row($resultado)) {
                        $texto = $fila[0];
                        $pregunta = $fila[1];
                    }

                    //RESULTADO
                    if ($pregunta == FALSE) {
                        echo "<div class='contenedorPregunta'>";
                        echo "<h2>¡Ya lo tengo, tu maestro es $texto!</h2>";
                        echo "</div>";

                        echo "<div class='contenedorRespuestas'>";
                        echo "<a class='boton' href='respuesta.php?n=" . $nodo . "&r=1&nom=" . $texto . ";'>SI</a>";
                        echo "<a class='boton' href='respuesta.php?n=" . $nodo . "&r=0&nom=" . $texto . ";'>NO</a>";
                        echo "</div>";
                    } else {
                        echo "<div class='contenedorPregunta'>";
                        echo "<h2>¿Tu maestro es $texto?</h2>";
                        echo "</div>";

                        echo "<div class='contenedorRespuestas'>";
                        echo "<a class='boton' href='index.php?n=" . $nodoSi . "'>SI</a>";
                        echo "<a class='boton' href='index.php?n=" . $nodoNo . "'>NO</a>";
                        echo "</div>";
                    }
                }
            }
            ?>
        </section>
    </main>

    <footer>
        <a href="index.php">Volver a intentar</a>
    </footer>

</body>

</html>
