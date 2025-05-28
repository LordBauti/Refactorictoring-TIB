<?php
require_once("./models/studentsSubjects.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'databaseConfig.php';

    $student_id = $_POST['student_id'] ?? 0;
    $subject_id = $_POST['subject_id'] ?? 0;

    // Verificar duplicado
    $check = mysqli_prepare($conn, "SELECT COUNT(*) FROM students_subjects WHERE student_id = ? AND subject_id = ?");
    mysqli_stmt_bind_param($check, "ii", $student_id, $subject_id);
    mysqli_stmt_execute($check);
    mysqli_stmt_bind_result($check, $exists);
    mysqli_stmt_fetch($check);
    mysqli_stmt_close($check);

    if ($exists > 0) {
        echo "Esta relación ya existe.";
        exit;
    }

    // Validar máximo de materias por estudiante
    $checkLimit = mysqli_prepare($conn, "SELECT COUNT(*) FROM students_subjects WHERE student_id = ?");
    mysqli_stmt_bind_param($checkLimit, "i", $student_id);
    mysqli_stmt_execute($checkLimit);
    mysqli_stmt_bind_result($checkLimit, $total);
    mysqli_stmt_fetch($checkLimit);
    mysqli_stmt_close($checkLimit);

    if ($total >= 6) {
        echo "El estudiante ya está inscrito en 6 materias.";
        exit;
    }

    // Insertar relación
    $stmt = mysqli_prepare($conn, "INSERT INTO students_subjects (student_id, subject_id) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, "ii", $student_id, $subject_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "Relación creada exitosamente.";
    } else {
        echo "Error al crear relación: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}

function handleGet($conn) 
{
    $result = getAllSubjectsStudents($conn);
    $data = [];
    while ($row = $result->fetch_assoc()) 
    {
        $data[] = $row;
    }
    echo json_encode($data);
}

function handlePost($conn) 
{
    $input = json_decode(file_get_contents("php://input"), true);
    if (assignSubjectToStudent($conn, $input['student_id'], $input['subject_id'], $input['approved'])) 
    {
        echo json_encode(["message" => "Asignación realizada"]);
    } 
    else 
    {
        http_response_code(500);
        echo json_encode(["error" => "Error al asignar"]);
    }
}

function handlePut($conn) 
{
    $input = json_decode(file_get_contents("php://input"), true);

    if (!isset($input['id'], $input['student_id'], $input['subject_id'], $input['approved'])) 
    {
        http_response_code(400);
        echo json_encode(["error" => "Datos incompletos"]);
        return;
    }

    if (updateStudentSubject($conn, $input['id'], $input['student_id'], $input['subject_id'], $input['approved'])) 
    {
        echo json_encode(["message" => "Actualización correcta"]);
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
    if (removeStudentSubject($conn, $input['id'])) 
    {
        echo json_encode(["message" => "Relación eliminada"]);
    } 
    else 
    {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo eliminar"]);
    }
}
?>
