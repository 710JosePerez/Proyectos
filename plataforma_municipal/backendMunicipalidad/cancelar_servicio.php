<?php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit();
}

require_once "config.php";

$id_cliente = $_SESSION["id_usuario"];
$sql = "SELECT id_contrato, id_servicio, fecha_inicio FROM Contratos WHERE id_cliente = ? AND estado = 'Activo'";
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
    <title>Solicitar Cancelación de Servicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-danger text-white">
                <h2 class="text-center">❌ Solicitar Cancelación de Servicio</h2>
            </div>
            <div class="card-body">

                <?php if (isset($_SESSION["mensaje"])): ?>
                    <div class="alert alert-info"><?php echo $_SESSION["mensaje"];
                                                    unset($_SESSION["mensaje"]); ?></div>
                <?php endif; ?>

                <form action="procesar_cancelacion.php" method="post" id="cancelForm">
                    <div class="mb-3">
                        <label for="id_contrato" class="form-label">📄 Servicio a cancelar:</label>
                        <select name="id_contrato" id="id_contrato" class="form-select" required>
                            <option value="">Seleccione un servicio</option>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <option value="<?= $row["id_contrato"]; ?>">
                                    Servicio #<?= $row["id_servicio"]; ?> (Inicio: <?= $row["fecha_inicio"]; ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="motivo" class="form-label">Motivo de la cancelación:</label>
                        <textarea name="motivo" id="motivo" class="form-control" rows="4" placeholder="Describa el motivo..." required></textarea>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-danger">Enviar Solicitud</button>
                        <a href="servicios.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Validación -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById("cancelForm").addEventListener("submit", function(e) {
            const motivo = document.getElementById("motivo").value.trim();
            if (motivo.length < 10) {
                e.preventDefault();
                alert("Por favor, escribe un motivo de al menos 10 caracteres.");
            }
        });
    </script>
</body>

</html>