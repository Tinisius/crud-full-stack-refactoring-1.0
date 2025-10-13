<?php
/**
*    File        : backend/server.php
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 3.0 ( prototype )
*/

/**FOR DEBUG: */
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

//configuración CORS (Cross-Origin Resource Sharing)
header("Access-Control-Allow-Origin: *");    //permite que cualquier sitio web se comunique con este servidor (reemplazar * por una direccion X)
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");    //admite las acciones basica del CRUD
header("Access-Control-Allow-Headers: Content-Type");    //permite que las solicitudes indiquen el typo del contenido (ejemplo: JSON)

function sendCodeMessage($code, $message = "")    //se usa para mandar mensajes por HTTP al frontend
{
    http_response_code($code);
    echo json_encode(["message" => $message]);    //Devuelve un JSON con un campo "message" que contiene el texto
    exit();    //detiene el script
}

// Respuesta correcta para solicitudes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS')    //el navegador manda una solicitud de prueba llamada options, si la recibo: devuelvo 200(ok)
{
    sendCodeMessage(200); // 200 OK
}

// Obtener el módulo desde la query string
$uri = parse_url($_SERVER['REQUEST_URI']);    //de la URL (EJ: http://server.php?module=students) la separa en partes a modo de array [[scheme]: http, [host]: server.php, [query]: module=students] 
$query = $uri['query'] ?? '';    //de la URL parseada, obtengo solo la query (module=students)
parse_str($query, $query_array);    //convierte el string en un array asociativo, al igual que uri ([module]: students)
$module = $query_array['module'] ?? null;    //obtengo el nombre del modulo (students)

// Validación de existencia del módulo
if (!$module)
{
    sendCodeMessage(400, "Módulo no especificado");
}

// Validación de caracteres seguros: solo letras, números y guiones bajos
if (!preg_match('/^\w+$/', $module))
{
    sendCodeMessage(400, "Nombre de módulo inválido");
}

// Buscar el archivo de ruta correspondiente
$routeFile = __DIR__ . "/routes/{$module}Routes.php";    //__DIR__ es una contante que contiene la ruta del archivo, la concatenacion de strin se hace con "."

if (file_exists($routeFile))
{
    require_once($routeFile);    //cargamos y ejecutamos el archivo de la ruta, ese archivo s eencarga de manejar GET, POST, etc
}
else
{
    sendCodeMessage(404, "Ruta para el módulo '{$module}' no encontrada");
}
