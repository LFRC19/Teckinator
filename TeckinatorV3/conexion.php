<?php

$mysql_host = 'localhost';
$mysql_usuario = 'root';
/*Sin contraseña*/
$mysql_passwd = '';
$mysql_bd = 'teckinatorv3';

$enlace = mysqli_connect($mysql_host, $mysql_usuario, $mysql_passwd, $mysql_bd);

/* Comprobar la conexión */
if (mysqli_connect_errno()) {
    printf("Fallo la conexión: %s\n", mysqli_connect_error());
    exit();
}

/* Codificación de caracteres UTF-8 */
mysqli_set_charset($enlace, 'utf8');

?>
