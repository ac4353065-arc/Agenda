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
    if (!isset($_SESSION["usuario_id"])) {

        header("Location: /Agenda/login.php");
        exit;
    }


    /*
     * Si el usuario debe cambiar su contraseña,
     * no puede continuar hacia otras páginas.
     */
    $paginaActual = basename($_SERVER["PHP_SELF"]);

    if (
        isset($_SESSION["requiere_cambio_password"]) &&
        $_SESSION["requiere_cambio_password"] == 1 &&
        $paginaActual !== "cambiar_password.php"
    ) {

        header(
            "Location: /Agenda/cambiar_password.php"
        );

        exit;
    }
}


/**
 * Verifica que el usuario tenga uno de los roles permitidos.
 */
function exigirRol($rolesPermitidos)
{
    exigirLogin();

    // Si se recibe un solo rol, lo convertimos en arreglo.
    if (!is_array($rolesPermitidos)) {
        $rolesPermitidos = [$rolesPermitidos];
    }

    if (
        !isset($_SESSION["rol"]) ||
        !in_array($_SESSION["rol"], $rolesPermitidos)
    ) {

        http_response_code(403);

        die("No tienes permiso para acceder a esta página.");
    }
}


/**
 * Cierra la sesión del usuario.
 */
function cerrarSesion()
{
    // Eliminamos las variables de sesión.
    $_SESSION = [];

    // Destruimos la sesión.
    session_destroy();
}

?>