<?php

// CONEXION CON LA BASE DE DATOS
require 'conexion.php';

// RECUPERAMOS LOS DATOS
$nodo = $_POST['nodo'];
$nombre = $_POST['nombre'];
$caracteristicas = $_POST['caracteristicas'];
$nombreAnt = $_POST['nombreAnt'];

// Evitar el nodo 0
if ($nodo == 0) {
    echo "Error: No se puede utilizar el nodo '0'.";
    exit();
}

// NUEVOS NODOS
$numHijoI = $nodo * 2;  // Nodo izquierdo
$numHijoD = $nodo * 2 + 1;  // Nodo derecho

// TEXTOS
$nombreHijoI = $nombre;   // Texto para el nodo izquierdo
$nombreHijoD = $nombreAnt; // Texto para el nodo derecho

// Verificar si los nodos hijos ya existen
$checkQuery = "SELECT nodo FROM arbol WHERE nodo IN ($numHijoI, $numHijoD)";
$result = mysqli_query($enlace, $checkQuery);

if (mysqli_num_rows($result) > 0) {
    echo "Error: Uno o ambos nodos ($numHijoI, $numHijoD) ya existen en la base de datos.";
    exit();
}

// Insertar nodo hijo izquierdo
$consulta = "INSERT INTO arbol (nodo, texto, pregunta) VALUES('$numHijoI', '$nombreHijoI', FALSE)";
if (!mysqli_query($enlace, $consulta)) {
    echo "Error en la inserción del nodo hijo izquierdo: " . mysqli_error($enlace);
    exit();
}

// Insertar nodo hijo derecho
$consulta = "INSERT INTO arbol (nodo, texto, pregunta) VALUES('$numHijoD', '$nombreHijoD', FALSE)";
if (!mysqli_query($enlace, $consulta)) {
    echo "Error en la inserción del nodo hijo derecho: " . mysqli_error($enlace);
    exit();
}

// Actualizar el nodo actual con la nueva característica
$consulta = "UPDATE arbol SET texto = '$caracteristicas', pregunta = TRUE WHERE nodo = '$nodo'";
if (!mysqli_query($enlace, $consulta)) {
    echo "Error en la actualización del nodo: " . mysqli_error($enlace);
    exit();
}

// Redirigir a la página inicial
header("Location: index.php");

?>
