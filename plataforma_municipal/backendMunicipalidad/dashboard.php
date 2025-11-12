<?php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    
    <!-- CSS -->
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
    <!-- Sidebar -->
    <div class="sidebar">
        <h4>Panel de Opciones</h4>
        <a href="dashboard.php">Inicio</a>
        <a href="servicios.php">Servicios</a>
        <a href="#">Pagos</a>
        <a href="configuracion.php">Configuración</a>
    </div>

    <!-- Contenido -->
    <div class="content">
        <nav class="navbar navbar-light bg-light d-flex justify-content-between">
            <span class="navbar-brand mb-0 h4">Bienvenido, <?php echo $_SESSION["username"]; ?></span>
            <a href="logout.php" class="btn btn-danger">Cerrar sesión</a>
        </nav>
        <div class="mt-4">
            <h3>Dashboard</h3>
            <p>Tu rol es: <strong><?php echo $_SESSION["rol"]; ?></strong></p>
            <p>¡Bienvenido al panel de administración!</p>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

