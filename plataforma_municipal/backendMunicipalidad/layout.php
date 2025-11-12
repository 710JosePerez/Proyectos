<!-- layout.php -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel - AdminLTE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="adminlte/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="adminlte/dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="logout.php" class="nav-link text-danger">Cerrar sesión</a>
                </li>
            </ul>
        </nav>

        <!-- Sidebar -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="dashboard.php" class="brand-link">
                <span class="brand-text font-weight-light">Plataforma Municipal</span>
            </a>
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
                        <li class="nav-item"><a href="dashboard.php" class="nav-link"><i class="nav-icon fas fa-home"></i>
                                <p>Inicio</p>
                            </a></li>
                        <li class="nav-item"><a href="servicios.php" class="nav-link"><i class="nav-icon fas fa-cogs"></i>
                                <p>Servicios</p>
                            </a></li>
                        <li class="nav-item"><a href="#" class="nav-link"><i class="nav-icon fas fa-credit-card"></i>
                                <p>Pagos</p>
                            </a></li>
                        <li class="nav-item"><a href="configuracion.php" class="nav-link"><i class="nav-icon fas fa-user-cog"></i>
                                <p>Configuración</p>
                            </a></li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Contenido principal -->
        <div class="content-wrapper">
            <section class="content pt-3">
                <div class="container-fluid">
                    <?php include $contenido; ?>
                </div>
            </section>
        </div>

        <!-- Footer -->
        <footer class="main-footer text-center">
            <strong>&copy; <?php echo date("Y"); ?> Plataforma Municipal</strong>. Todos los derechos reservados.
        </footer>
    </div>

    <!-- Scripts -->
    <script src="adminlte/plugins/jquery/jquery.min.js"></script>
    <script src="adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="adminlte/dist/js/adminlte.min.js"></script>
</body>

</html>