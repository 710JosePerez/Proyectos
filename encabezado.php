<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="CRM">
    <title>CRM</title>

    <!-- Cargar el CSS de Boostrap-->
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <script src="./js/Chart.min.js"></script>

    <!-- Cargar estilos propios -->
    <link href="estilo.css" rel="stylesheet">
</head>

<body>
    
    <!-- Definición del menú -->
    <nav class="navbar navbar-expand-md navbar-light bg-warning fixed-top">
        <a class="navbar-brand" href=".">CRM</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#miNavbar" aria-controls="miNavbar" aria-expanded="false" aria-label="Mostrar u ocultar menú">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="miNavbar">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="clientes.php">Clientes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="ventas.php">Ventas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="creditos.php">Créditos</a>
                </li>

            </ul>
        </div>
    </nav>
    <main role="main" class="container-fluid">