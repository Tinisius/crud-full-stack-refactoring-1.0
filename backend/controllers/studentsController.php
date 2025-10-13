<?php
/**
*    File        : backend/controllers/studentsController.php
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 3.0 ( prototype )
*/

//contiene las funciones handleGet(), etc que hacen el trabajo real de consultar, modificar, etc
//es un controlador, define funciones que se ejecutan cuando el usuario hace una operación sobre los estudiantes: ver, crear, modificar o eliminar.

require_once("./repositories/students.php");    //archivo que contiene las funciones para trabajar con la tabla students en la DB (createStudent, etc)

//LEER
function handleGet($conn)    //cuando se hace una peticion GET (leer estudiantes?)
{
    $input = json_decode(file_get_contents("php://input"), true);    //lee el contenido del cuerpo de la peticion (URL) y lo convierte desde JSON a un array PHP
    
    if (isset($input['id']))    //si el JSON tiene un campo id en el input 
    {
        $student = getStudentById($conn, $input['id']);    //busca el estudiante con getStudentById()
        echo json_encode($student);    //lo vuelve a codificar a JSON (JSON guarda variables (objetos) es su formato)
    } 
    else    //si no recibio nungun id
    {
        $students = getAllStudents($conn);    //se devuelven todos los estudiantes
        echo json_encode($students);
    }
}

//CREAR
function handlePost($conn)    //cuando se hace una peticion POST (crear un nuevo estudiante)
{
    $input = json_decode(file_get_contents("php://input"), true);    //...convierte desde JSON a un array PHP

    $result = createStudent($conn, $input['fullname'], $input['email'], $input['age']);    //se llama a createStudent() con los datos del formulario
    if ($result['inserted'] > 0)     //si la creación fue exitosa
    {
        echo json_encode(["message" => "Estudiante agregado correctamente"]);
    } 
    else 
    {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo agregar"]);
    }
}

//ACTUALIZAR
function handlePut($conn)    //cuando se hace una peticion PUT (actualizar un estudiante)
{
    $input = json_decode(file_get_contents("php://input"), true);    //...convierte desde JSON a un array PHP

    $result = updateStudent($conn, $input['id'], $input['fullname'], $input['email'], $input['age']);    //se llama a updateStudent() con el ID + los datos del formulario
    if ($result['updated'] > 0)    //si la actualizacion fue exitosa
    {
        echo json_encode(["message" => "Actualizado correctamente"]);
    } 
    else 
    {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo actualizar"]);
    }
}

//BORRAR
function handleDelete($conn) 
{
    $input = json_decode(file_get_contents("php://input"), true);    //...convierte desde JSON a un array PHP

    $result = deleteStudent($conn, $input['id']);    //se llama a updateStudent() con el ID
    if ($result['deleted'] > 0)    //si el borrado fue exitoso
    {
        echo json_encode(["message" => "Eliminado correctamente"]);
    } 
    else 
    {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo eliminar"]);
    }
}
?>
