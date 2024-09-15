<html>

  <head>
      <title> Proyecto Teckinator </title>
      <link rel="stylesheet" type="text/css" href="css/Teckinator.css">
  <head>

      <body>

        <header>
          <h1>Teckinator</h1>
        </header>

        <main>
          <h2>PREGUNTA</h2>

          <?php

          //CONEXION CON LA BASE DE DATOS
          require'conexion.php';

          //RECOGER EL NUMERO DE NODO DE LA URL (PARAMETRO)
          $nodo=1; //VALOR POR DEFECTO, EMPEZAMOS EN 1

          if(isset($_GET['n'])){
            $nodo = $_GET['n'];
          }

          //CALCULAR LOS SIGUIENTES NODOS (SI - NO)
          $nodoSi = $nodo * 2;
          $nodoNo = $nodo * 2 + 1;

          echo 'NO ACTUAL: '.$nodo;
          ?>




          <div class='contenedorPregunta'>
            <h2>¿Tu maestro es...</h2>
          </div>

          <div class="contenedorRespuestas">

            <?php
            echo "<a class='boton' href='index.php?n=" . $nodoSi ."'>SI</a>";
            echo "<a class='boton' href='index.php?n=" . $nodoNo ."'>NO</a>";

             ?>

          </div>

        </main>
        <div class="limpiar"></div>


        <br>
        <br>

        <footer>
          <a href="">Volver a intentar</a>
        </footer>



      </body>


</html>
