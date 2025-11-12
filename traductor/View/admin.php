<?php
include_once 'assets/header.php';
// Verificar si la sesión contiene datos válidos (por ejemplo, el ID del usuario)
if (!isset($_SESSION["nombre"])) {
    // Si no hay una sesión válida, redireccionar al usuario a la página de login o la que desees
    header("Location: index.php");
    exit; // Es importante finalizar la ejecución del script tras redirigir
}
?>
<?php require_once "View/parte_superior.php"?>

<!-- Inicio del contenido principal -->
<div class="container">
    <hi>Panel principal</h1>
<div>

<!-- Fin del contenido princiapla -->

<?php require_once "View/dashboard.php"?>


