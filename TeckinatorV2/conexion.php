<?php

$mysql_host='localhost';
$mysql_usuario='root';
/*Sin contraseña*/
$mysql_passwd='';
$mysql_bd='teckinatorv2';

$enlace=mysqli_connect($mysql_host,$mysql_usuario,$mysql_passwd,$mysql_bd);

/*Comprobar la conexion*/
if(mysqli_connect_errno()){

  printf("Fallo la conexion %s\n", mysqli_connect_errno());
  exit();
}

/*Codificacion de caracteres UTF8*/
mysqli_set_charset($enlace,'utf8');

 ?>
