<?php

// Iniciar sesión solamente si todavía no existe.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/**
 * Verifica que exista un usuario autenticado.
 */
function exigirLogin()
{
    if (!isset($_SESSION['usuario_id'])) {

        header("Location: ../login.php");
        exit;
    }
}


/**
 * Verifica que el usuario tenga uno de los roles permitidos.
 */
function exigirRol($rolesPermitidos)
{
    exigirLogin();

    // Convertimos un solo rol en un arreglo.
    if (!is_array($rolesPermitidos)) {
        $rolesPermitidos = [$rolesPermitidos];
    }

    if (
        !isset($_SESSION['rol']) ||
        !in_array($_SESSION['rol'], $rolesPermitidos)
    ) {

        http_response_code(403);

        die("No tienes permiso para acceder a esta página.");
    }
}

?>