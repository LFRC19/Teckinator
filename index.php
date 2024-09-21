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
          require 'conexion.php';

          //RECOGER EL NUMERO DE NODO DE LA URL (PARAMETRO)
          $nodo= 1 ; //VALOR POR DEFECTO, EMPEZAMOS EN 1

          if(isset($_GET['n'])){
            $nodo = $_GET['n'];
          }

          //CALCULAR LOS SIGUIENTES NODOS (SI - NO)
          $nodoSi = $nodo * 2 ;
          $nodoNo = $nodo * 2 + 1;

          ?>

          <?php

          //Consulta a la base de DATOS
          $consulta="SELECT texto,pregunta FROM arbol WHERE nodo=" . $nodo . ";";
          $texto='';
          $pregunta=true;

          $resultado;

          if($resultado = mysqli_query($enlace,$consulta)){

            if($resultado->num_rows === 0){

              echo'ERROR EL NODO NO EXISTE';
            }
            //RECUPERA CORRECTAMENTE LA INFORMACION
            else{
              while ($fila = mysqli_fetch_row($resultado)) {
                $texto=$fila[0];
                $pregunta=$fila[1];
              }


              //RESULTADO
              if ($pregunta == FALSE) {

                echo "<div class='contenedorPregunta'>";
                  echo "<h2>!Ya lo tengo, tu maestro es $texto!</h2>";
                  echo "</div>";

                  echo "<div class='contenedorRespuestas'>";


                    //Botones de Si y No para el usuario
                    echo "<a class='boton' href='respuesta.php?n=" . $nodo ."&r=1&nom=".$texto.";'>SI</a>";
                    echo "<a class='boton' href='respuesta.php?n=" . $nodo ."&r=0&nom=".$texto.";'>NO</a>";

                echo "</div>";
              }

              //PREGUNTA
              else {

                echo "<div class='contenedorPregunta'>";
                  echo "<h2>¿Tu maestro es $texto?</h2>";
                  echo "</div>";

                  echo "<div class='contenedorRespuestas'>";


                    //Botones de Si y No para el usuario
                    echo "<a class='boton' href='index.php?n=" . $nodoSi ."'>SI</a>";
                    echo "<a class='boton' href='index.php?n=" . $nodoNo ."'>NO</a>";


                echo "</div>";

              }



            }




          }


          ?>



        </main>
        <div class="limpiar"></div>


        <br>
        <br>

        <footer>
          <a href="">Volver a intentar</a>
        </footer>



      </body>


</html>
