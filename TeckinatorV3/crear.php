<?php
// CONEXIÓN CON LA BASE DE DATOS
require 'conexion.php';

// Si se envía el formulario, agregar el nuevo maestro
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $departamento = $_POST['departamento'];
    $especialidad = $_POST['especialidad'];
    $materias = $_POST['materias'];
    $gafas = $_POST['gafas'];
    $genero = $_POST['genero'];
    $edad = $_POST['edad'];
    $barba = $_POST['barba'];
    $calvo = $_POST['calvo'];
    $grado_academico = $_POST['grado_academico'];
    $caracteristica_distintiva = $_POST['caracteristica_distintiva'];

    // Inserción en la base de datos
    $consulta = "INSERT INTO maestros (nombre, departamento, especialidad, materias, gafas, genero, edad, barba, calvo, grado_academico, caracteristica_distintiva)
                 VALUES ('$nombre', '$departamento', '$especialidad', '$materias', '$gafas', '$genero', '$edad', '$barba', '$calvo', '$grado_academico', '$caracteristica_distintiva')";

    if (mysqli_query($enlace, $consulta)) {
        echo "Nuevo maestro agregado exitosamente.";
    } else {
        echo "Error al agregar el maestro: " . mysqli_error($enlace);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Maestro</title>
</head>
<body>

<h2>Añadir un nuevo maestro</h2>

<form action="crear.php" method="POST">
    Nombre: <input type="text" name="nombre" required><br>
    Departamento: <input type="text" name="departamento" required><br>
    Especialidad: <input type="text" name="especialidad" required><br>
    Materias: <input type="text" name="materias" required><br>
    Gafas (1 = Sí, 0 = No): <input type="number" name="gafas" required><br>
    Género: <input type="text" name="genero" required><br>
    Edad: <input type="number" name="edad" required><br>
    Barba (1 = Sí, 0 = No): <input type="number" name="barba" required><br>
    Calvo (1 = Sí, 0 = No): <input type="number" name="calvo" required><br>
    Grado Académico: <input type="text" name="grado_academico" required><br>
    Característica Distintiva: <input type="text" name="caracteristica_distintiva" required><br>
    <button type="submit">Agregar Maestro</button>
</form>

<a href="index.php">Vol
