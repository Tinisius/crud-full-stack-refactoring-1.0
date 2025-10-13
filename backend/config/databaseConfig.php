<?php
/**
*    File        : backend/config/databaseConfig.php
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 3.0 ( prototype )
*/

//establece la conexión con la DB MySQL.

$host = "localhost";        //direccion del servidor de DB
$user = "student";        //usuario mysql con todos los permisos "admin"
$password = "12345";        //su contraseña sql
$database = "students";        //nombre de la DB que queremos acceder

//crea una conexión a la base de datos usando la clase mysqli (MySQL Improved).
$conn = new mysqli($host, $user, $password, $database);        //crea un nuevo objeto $conn para consultas


if ($conn->connect_error) 
{
    http_response_code(500);
    die(json_encode(["error" => "Database connection failed"]));
}
?>
