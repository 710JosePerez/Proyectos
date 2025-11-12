<?php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit();
}

require_once "config.php"; // Archivo de conexión a la base de datos

$id_usuario = $_SESSION["id_usuario"];

// Obtener el ID del cliente asociado al usuario
$sql_cliente = "SELECT id_cliente FROM Usuarios WHERE id_usuario = ?";
$stmt_cliente = $conn->prepare($sql_cliente);
$stmt_cliente->bind_param("i", $id_usuario);
$stmt_cliente->execute();
$result_cliente = $stmt_cliente->get_result();
$row_cliente = $result_cliente->fetch_assoc();
$id_cliente = $row_cliente['id_cliente'];

// Obtener los servicios contratados por el cliente
$sql = "SELECT c.id_contrato, s.nombre, s.descripcion, s.costo_mensual, c.fecha_inicio, c.fecha_fin, c.estado
        FROM Contratos c
        JOIN Servicios s ON c.id_servicio = s.id_servicio
        WHERE c.id_cliente = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_cliente);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios Contratados</title>
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
            <h3>Servicios Contratados</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Costo Mensual</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row["nombre"]); ?></td>
                            <td><?php echo htmlspecialchars($row["descripcion"]); ?></td>
                            <td>Q<?php echo number_format($row["costo_mensual"], 2); ?></td>
                            <td><?php echo htmlspecialchars($row["fecha_inicio"]); ?></td>
                            <td><?php echo $row["fecha_fin"] ? htmlspecialchars($row["fecha_fin"]) : 'Indefinido'; ?></td>
                            <td><span class="badge bg-<?php echo ($row["estado"] == '1') ? 'success' : 'danger'; ?>">
                                <?php echo htmlspecialchars(($row["estado"] == '1') ? 'Activo' : 'Inactivo'); ?>
                            </span></td>
                            <td>
                                <?php if ($row["estado"] == "1"): ?>
                                    <form action="cancelar_servicio.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="id_contrato" value="<?php echo $row['id_contrato']; ?>">
                                        <button type="submit" class="btn btn-warning btn-sm">Solicitar Cancelación</button>
                                    </form>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-sm" disabled>Cancelado</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
