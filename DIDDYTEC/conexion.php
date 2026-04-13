<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "diddytec";
// crear conexion
$conexion = new mysqli($host, $user, $password, $dbname);
if ($conexion -> connect_error) {
    die("Conexion fallida: ". $conexion -> connect_error);
}
?>