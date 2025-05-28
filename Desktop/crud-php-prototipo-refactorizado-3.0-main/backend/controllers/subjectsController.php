<?php
require_once("./models/subjects.php");
include 'databaseConfig.php';

// Añadir materia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
   

    $name = $_POST['name'];

    // Verificar si ya existe la materia
    $check = mysqli_prepare($conn, "SELECT COUNT(*) FROM subjects WHERE name = ?");
    mysqli_stmt_bind_param($check, "s", $name);
    mysqli_stmt_execute($check);
    mysqli_stmt_bind_result($check, $total);
    mysqli_stmt_fetch($check);
    mysqli_stmt_close($check);

    if ($total > 0) {
        echo "Ya existe una materia con ese nombre.";
        exit;
    }

    // Insertar materia
    $stmt = mysqli_prepare($conn, "INSERT INTO subjects (name) VALUES (?)");
    mysqli_stmt_bind_param($stmt, "s", $name);
    if (mysqli_stmt_execute($stmt)) {
        echo "Materia agregada correctamente.";
    } else {
        echo "Error al agregar materia: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}
// Eliminar estudiante o materia (GET con tipo)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete_id'], $_GET['type'])) {
    $id = $_GET['delete_id'];
    $type = $_GET['type'];

    if ($type === 'subject') {
        $stmt = mysqli_prepare($conn, "DELETE FROM subjects WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (!mysqli_stmt_execute($stmt)) {
            if (mysqli_errno($conn) == 1451) {
                echo "No se puede borrar la materia porque está asociada a un estudiante.";
            } else {
                echo "Error al borrar: " . mysqli_error($conn);
            }
        } else {
            echo "Materia borrada correctamente.";
        }
        mysqli_stmt_close($stmt); //Libero recursos
    }

    if ($type === 'student') {
        $stmt = mysqli_prepare($conn, "DELETE FROM students WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (!mysqli_stmt_execute($stmt)) {
            if (mysqli_errno($conn) == 1451) {  //1451 eliminación falló (restricción de clave foránea).
                echo "No se puede borrar el estudiante porque tiene materias asignadas.";
            } else {
                echo "Error al borrar: " . mysqli_error($conn);
            }
        } else {
            echo "Estudiante borrado correctamente.";
        }
        mysqli_stmt_close($stmt);  //Libero recursos
    }
}


function handleGet($conn) 
{
    $input = json_decode(file_get_contents("php://input"), true);
    if (isset($input['id'])) 
    {
        $result = getSubjectById($conn, $input['id']);
        echo json_encode($result->fetch_assoc());
    } 
    else 
    {
        $result = getAllSubjects($conn);
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
    if (createSubject($conn, $input['name'])) 
    {
        echo json_encode(["message" => "Materia creada correctamente"]);
    } 
    else 
    {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo crear"]);
    }
}

function handlePut($conn) 
{
    $input = json_decode(file_get_contents("php://input"), true);
    if (updateSubject($conn, $input['id'], $input['name'])) 
    {
        echo json_encode(["message" => "Materia actualizada correctamente"]);
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
    if (deleteSubject($conn, $input['id'])) 
    {
        echo json_encode(["message" => "Materia eliminada correctamente"]);
    } 
    else 
    {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo eliminar"]);
    }
}

?>