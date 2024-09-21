<html>
  <head>
    <title> Proyecto Teckinator </title>
    <link rel="stylesheet" type="text/css" href="css/Teckinator.css">
  </head>

    <body>

      <header>
        <h1>Teckinator</h1>
      </header>

      <main>
        <?php
        //CONEXION CON LA BASE DE DATOS
        require 'conexion.php';

        $nodo = $_GET['n'];
        $respuesta = $_GET['r'];
        $nombre=$_GET['nom'];

        function formularioRespuesta($n, $p, $nom){

          echo "<div class='contenedorPregunta'>";
          //contenedor de logica para la pregunta (Queda pendiente ingresar tambien una foto)
          echo "<textarea id='nodo' name='nodo' form='formulario', style='display:none;'>" .$n. "</textarea>";
          echo "<textarea id='nombreAnt' name='nombreAnt' form='formulario', style='display:none;'>" .$nom. "</textarea>";

          echo "<h3>¿En que maestro habias pensado?</h3>";
          echo "<textarea id='nombre' name='nombre' form='formulario' placeholder='Nombre'></textarea>";
          echo "<h3>¿Que tiene este maestro que no tenga ".$nom."? </h3>";
          echo "<textarea id='caracteristicas' name='caracteristicas' form='formulario' placeholder='caracteristicas'></textarea>";

          echo "<form action='crear.php' id='formulario' method='POST'>";
          echo "<button type ='submit' name='enviar'> ENVIAR </button>";
          echo "</form";

          echo "</div>";

        }

        formularioRespuesta($nodo,$respuesta,$nombre);

         ?>
      </main>
  </body>
</html>
