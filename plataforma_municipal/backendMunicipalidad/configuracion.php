<?php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit();
}

require_once "config.php"; // Conexión a la base de datos

$id_usuario = $_SESSION["id_usuario"];
$mensaje = "";

// Si el formulario es enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nuevo_usuario = trim($_POST["username"]);
    $nueva_password = trim($_POST["password"]);
    $confirmar_password = trim($_POST["confirm_password"]);

    // Verificar que la contraseña coincida
    if ($nueva_password !== $confirmar_password) {
        $mensaje = "Las contraseñas no coinciden.";
    } else {
        
        // Encriptar la nueva contraseña
        $password_hash = password_hash($nueva_password, PASSWORD_BCRYPT);

        // Actualizar los datos en la base de datos
        $sql = "UPDATE Usuarios SET username = ?, password_hash = ? WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([$nuevo_usuario, $password_hash, $id_usuario])) {
            $_SESSION["username"] = $nuevo_usuario; // Actualizar sesión
            $mensaje = "Datos actualizados correctamente.";
        } else {
            $mensaje = "Error al actualizar los datos.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            padding: 20px;
            height: 100%;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 5px;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4>Panel de Opciones</h4>
        <a href="dashboard.php">Inicio</a>
        <a href="servicios.php">Servicios</a>
        <a href="#">Pagos</a>
        <a href="configuracion.php">Configuración</a>
    </div>

    <div class="content">
        <nav class="navbar navbar-light bg-light d-flex justify-content-between">
            <span class="navbar-brand mb-0 h4">Bienvenido, <?php echo $_SESSION["username"]; ?></span>
            <a href="logout.php" class="btn btn-danger">Cerrar sesión</a>
        </nav>

        <div class="mt-4">
            <h3>Configuración de Usuario</h3>
            <p>Modifica tu usuario y contraseña.</p>

            <?php if ($mensaje): ?>
                <div class="alert alert-info"><?php echo $mensaje; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Nuevo Usuario</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo $_SESSION["username"]; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Nueva Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirmar Nueva Contraseña</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </form>
        </div>
    </div>
</body>
</html>
