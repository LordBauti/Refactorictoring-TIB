<?php
require_once("./models/students.php");
require_once("./models/subjects.php");
include 'databaseConfig.php';

$id = $_GET['id'] ?? 0;

// Intentar borrar y capturar error por restricción de clave foránea
$query = "DELETE FROM students WHERE id = ?";//Prevengo inyecciones SQL pasando ?
$stmt = mysqli_prepare($conn, $query); //Consulta sql
mysqli_stmt_bind_param($stmt, "i", $id); //Asocio ID al ? , i indico que es entero

if (!mysqli_stmt_execute($stmt)) {
    if (mysqli_errno($conn) == 1451) { // Error de clave foránea
        echo "No se puede borrar el estudiante porque está inscrito en una materia.";
    } else {
        echo "Error al intentar borrar el estudiante: " . mysqli_error($conn);
    }
}
mysqli_stmt_close($stmt); //Libero recursos




function handleGet($conn) 
{
    $input = json_decode(file_get_contents("php://input"), true);
    if (isset($input['id'])) 
    {
        $result = getStudentById($conn, $input['id']);
        echo json_encode($result->fetch_assoc());
    } 
    else 
    {
        $result = getAllStudents($conn);
        $data = [];
        while ($row = $result->fetch_assoc()) 
        {
            $data[] = $row;
        }
        echo json_encode($data);
    }
}

function handlePost($conn) 
{
    $input = json_decode(file_get_contents("php://input"), true);
    if (createStudent($conn, $input['fullname'], $input['email'], $input['age'])) 
    {
        echo json_encode(["message" => "Estudiante agregado correctamente"]);
    } 
    else 
    {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo agregar"]);
    }
}

function handlePut($conn) 
{
    $input = json_decode(file_get_contents("php://input"), true);
    if (updateStudent($conn, $input['id'], $input['fullname'], $input['email'], $input['age'])) 
    {
        echo json_encode(["message" => "Actualizado correctamente"]);
    } 
    else 
    {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo actualizar"]);
    }
}

function handleDelete($conn) 
{
    $input = json_decode(file_get_contents("php://input"), true);
    if (deleteStudent($conn, $input['id'])) 
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