<?php
class c_usuario
{
    private $m_usuario;
    public function __construct($m_usuarios)
    {
        $this->m_usuario = $m_usuarios;
    }
    public function index()
    {
        include('View/index.php');
    }
    public function login()
    {
        include('View/login.php');
    }
    public function traductor()
    {
        $this->m_usuario->traductor();
    }
    public function agregar()
    {
        $this->m_usuario->agregar();
    }
    public function login_v()
    {
        $validar = $this->m_usuario->login_v();
        if ($validar == 1) {
            header("Location: index.php?accion=admin");
        } else {
            include_once 'assets/header.php';
            echo '<div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="alert alert-danger" id="error-message">
                        Correo o contraseña inválidos.
                    </div>
                    <a href="index.php" class="btn btn-secondary mt-2">Ir a la página principal</a>
                </div>
            </div>
        </div>
        ';
        }
    }
    public function admin()
    {
        
        $noencontradas = $this->m_usuario->noencontradas();
        $consultas = $this->m_usuario->consultas();
        include('View/admin.php');

    }
    public function cerrar()
    {
        session_destroy(); // Destruye toda la información asociada con la sesión actual
        header("Location: index.php");
    }
}
