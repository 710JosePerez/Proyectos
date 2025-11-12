<?php
session_start();
require_once 'Controller/C_usuarios.php';
require_once 'Model/M_usuarios.php';
$m_usuarios = new M_usuarios();
$c_usuario = new c_usuario($m_usuarios);

//echo $_SERVER['REQUEST_METHOD'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $caso = $_POST['caso'];
    switch ($caso) {
        case 'traductor':
            $c_usuario->traductor();
            break;
        case 'agregar':
            $c_usuario->agregar();
            break;
        case 'login':
            $c_usuario->login_v();
            break;

        default:
            # code...
            break;
    }
} else {
    if (isset($_GET['accion'])) {
        switch ($_GET['accion']) {
            case 'login':
                $c_usuario->login();
                break;
            case 'admin':
                $c_usuario->admin();
                break;
                case 'cerrar':
                    $c_usuario->cerrar();
                    break;

            default:
                break;
        }
    } else {
        $c_usuario->index();
    }
}
