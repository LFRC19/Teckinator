<?php
// CONEXIÓN CON LA BASE DE DATOS
require 'conexion.php';

// RECUPERAMOS LOS DATOS
$nodo = $_POST['nodo'];
$nombre = $_POST['nombre'];
$caracteristicas = $_POST['caracteristicas'];
$nombreAnt = $_POST['nombreAnt'];

if ($nodo == 0) {
    echo "Error: No se puede utilizar el nodo '0'.";
    exit();
}

$numHijoI = $nodo * 2;
$numHijoD = $nodo * 2 + 1;

$nombreHijoI = $nombre;
$nombreHijoD = $nombreAnt;

// Verificar si los nodos hijos ya existen
$checkQuery = "SELECT nodo FROM arbol WHERE nodo IN ($numHijoI, $numHijoD)";
$result = mysqli_query($enlace, $checkQuery);

if (mysqli_num_rows($result) > 0) {
    echo "Error: Uno o ambos nodos ($numHijoI, $numHijoD) ya existen en la base de datos.";
    exit();
}

$consulta = "INSERT INTO arbol (nodo, texto, pregunta) VALUES('$numHijoI', '$nombreHijoI', FALSE)";
if (!mysqli_query($enlace, $consulta)) {
    echo "Error en la inserción del nodo hijo izquierdo: " . mysqli_error($enlace);
    exit();
}

$consulta = "INSERT INTO arbol (nodo, texto, pregunta) VALUES('$numHijoD', '$nombreHijoD', FALSE)";
if (!mysqli_query($enlace, $consulta)) {
    echo "Error en la inserción del nodo hijo derecho: " . mysqli_error($enlace);
    exit();
}

$consulta = "UPDATE arbol SET texto = '$caracteristicas', pregunta = TRUE WHERE nodo = '$nodo'";
if (!mysqli_query($enlace, $consulta)) {
    echo "Error en la actualización del nodo: " . mysqli_error($enlace);
    exit();
}

header("Location: index.php");
?>
