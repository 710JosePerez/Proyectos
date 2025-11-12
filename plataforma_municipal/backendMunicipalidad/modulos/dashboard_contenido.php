<!-- modulos/dashboard_contenido.php -->

<div class="card">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title">Dashboard</h3>
    </div>
    <div class="card-body">
        <p>Bienvenido, <strong><?php echo $_SESSION["username"]; ?></strong></p>
        <p>Tu rol es: <strong><?php echo $_SESSION["rol"]; ?></strong></p>
        <p>¡Bienvenido al panel de administración!</p>
    </div>
</div>