<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/*
|--------------------------------------------------------------------------
| Verificar que exista una sesión iniciada
|--------------------------------------------------------------------------
*/

function exigirLogin()
{
    if (
        !isset($_SESSION['usuario_id']) &&
        !isset($_SESSION['id_usuario'])
    ) {

        header('Location: /Agenda/login.php');
        exit;
    }
}


/*
|--------------------------------------------------------------------------
| Verificar el rol del usuario
|--------------------------------------------------------------------------
*/

function exigirRol($rol)
{
    exigirLogin();

    // Intentamos obtener el rol según la sesión creada en el login
    if (isset($_SESSION['rol'])) {
        $rolUsuario = $_SESSION['rol'];
    } elseif (isset($_SESSION['usuario_rol'])) {
        $rolUsuario = $_SESSION['usuario_rol'];
    } else {
        $rolUsuario = '';
    }

    // Si no corresponde al rol permitido
    if ($rolUsuario !== $rol) {

        header('Location: /Agenda/login.php');
        exit;
    }
}