<?php
/**
*    File        : backend/routes/routesFactory.php
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 3.0 ( prototype )
*/

function routeRequest($conn, $customHandlers = [], $prefix = 'handle')    //el 2do y 3er parametro son opcionales ya que tienen un valor por defecto
{
    $method = $_SERVER['REQUEST_METHOD'];    //PHP guarda los datos del servidor en el array global $_SERVER.

    // Lista de handlers CRUD por defecto
    //se crea un array de funciones (lambda) para cada metodo
    $defaultHandlers = [
        'GET'    => $prefix . 'Get',    //handleGet($conn)
        'POST'   => $prefix . 'Post',    //handlePost($conn)
        'PUT'    => $prefix . 'Put',    //handlePut($conn)
        'DELETE' => $prefix . 'Delete'    //handleDelete($conn)
    ];

    // Sobrescribir handlers por defecto si hay personalizados (en studentsRoutes, aunque esta comentada)
    $handlers = array_merge($defaultHandlers, $customHandlers);

    if (!isset($handlers[$method])) 
    {
        http_response_code(405);
        echo json_encode(["error" => "Método $method no permitido"]);
        return;
    }

    $handler = $handlers[$method];    //guarda en $handler el nombre de la función que debe ejecutarse para el método solicitado (handleGet, handlePost, etc.)

    if (is_callable($handler)) //verifica si el $handler realmente es una función que se puede ejecutar
    {
        $handler($conn);
    }
    else
    {
        http_response_code(500);
        echo json_encode(["error" => "Handler para $method no es válido"]);
    }
}
/**
Esta función es como un “despachador de rutas”. Recibe las peticiones HTTP y llama a la función que las maneja.
Tiene un comportamiento por defecto (basado en nombres convencionales como handleGet, handlePost, etc.).
Puede ser personalizado para casos especiales (por ejemplo, validaciones en POST).
Devuelve errores en formato JSON y con códigos HTTP estandarizados.
**/
