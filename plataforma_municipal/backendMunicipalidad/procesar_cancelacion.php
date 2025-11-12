<?php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit();
}

require_once "config.php"; // Archivo de conexión

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cliente = $_SESSION["id_usuario"];
    $id_contrato = $_POST["id_contrato"];
    $motivo = trim($_POST["motivo"]);

    // Verifica que el contrato pertenece al usuario autenticado y está activo
    $sql_check = "SELECT id_contrato FROM Contratos WHERE id_contrato = ? AND id_cliente = ? AND estado = 'Activo'";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ii", $id_contrato, $id_cliente);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {

        // Insertar la solicitud en la base de datos
        $sql_insert = "INSERT INTO SolicitudesCancelacion (id_contrato, id_cliente, motivo, estado) VALUES (?, ?, ?, 'Pendiente')";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("iis", $id_contrato, $id_cliente, $motivo);

        if ($stmt_insert->execute()) {
            $_SESSION["mensaje"] = "Tu solicitud de cancelación ha sido enviada.";
        } else {
            $_SESSION["mensaje"] = "Error al enviar la solicitud.";
        }
    } else {
        $_SESSION["mensaje"] = "No puedes solicitar la cancelación de este servicio.";
    }
}

// Redirigir al formulario
header("Location: solicitar_cancelacion.php");
exit();
