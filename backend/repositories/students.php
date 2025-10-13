<?php
/**
*    File        : backend/models/students.php
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 3.0 ( prototype )
*/

//define una serie de funciones PHP que ejecutan consultas SQL sobre la tabla students

function getAllStudents($conn) 
{
    $sql = "SELECT * FROM students";

    //MYSQLI_ASSOC devuelve un array ya listo para convertir en JSON en el controlador
    return $conn->query($sql)->fetch_all(MYSQLI_ASSOC);    //$conn->query($sql) para ejecutar esa consulta con MySQLi y MYSQLI_ASSOC devuelve un array corte diccionario
}

function getStudentById($conn, $id) 
{
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");    //prepara la consulta, el "?" es un placeholder
    $stmt->bind_param("i", $id);    //le dice a PHP que reemplace el ? con un entero (i) que viene en $id, a modo de formato
    $stmt->execute();    //corre la consulta
    $result = $stmt->get_result();    //guarda el resultado

    return $result->fetch_assoc();    //extrae una sola fila como array asociativo (para covertir a JSON) 
}

function createStudent($conn, $fullname, $email, $age) 
{
    $sql = "INSERT INTO students (fullname, email, age) VALUES (?, ?, ?)";    //consulta sql con placeholders
    $stmt = $conn->prepare($sql);    //prepara la consulta
    $stmt->bind_param("ssi", $fullname, $email, $age);    //ssi indica el formato "string string integer"
    $stmt->execute();    //corre la consulta

    //Se retorna un arreglo con la cantidad e filas insertadas 
    //y id insertado para validar en el controlador:
    return 
    [
        'inserted' => $stmt->affected_rows,    //cantidad de filas afectadas (1)
        'id' => $conn->insert_id        //id autogenerado
    ];
}

function updateStudent($conn, $id, $fullname, $email, $age)     //lo mismo que los otros
{
    $sql = "UPDATE students SET fullname = ?, email = ?, age = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $fullname, $email, $age, $id);
    $stmt->execute();

    //Se retorna fila afectadas para validar en controlador:
    return ['updated' => $stmt->affected_rows];
}

function deleteStudent($conn, $id) 
{
    $sql = "DELETE FROM students WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    //Se retorna fila afectadas para validar en controlador
    return ['deleted' => $stmt->affected_rows];
}
?>
