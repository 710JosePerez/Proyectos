<?php
session_start();
require "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usuario = trim($_POST["usuario"]);
    $clave = trim($_POST["clave"]);

    $sql = "SELECT id_usuario, id_cliente, username, password_hash, rol, estado FROM Usuarios WHERE username = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if ($user["estado"] === "Inactivo") {
            $_SESSION["login_error"] = "Cuenta inactiva. Contacte con el soporte.";
            header("Location: login.php");
            exit();
        }

        if (password_verify($clave, $user["password_hash"])) {
            
            // Login exitoso
            $_SESSION["id_usuario"] = $user["id_usuario"];
            $_SESSION["id_cliente"] = $user["id_cliente"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["rol"] = $user["rol"];

            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION["login_error"] = "Contraseña incorrecta.";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION["login_error"] = "Usuario no encontrado.";
        header("Location: login.php");
        exit();
    }
}
