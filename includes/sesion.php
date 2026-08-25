<?php

// Iniciamos la sesión si todavía no está iniciada.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/**
 * Verifica que exista un usuario autenticado.
 */
function exigirLogin()
{
    // Si no existe una sesión, enviamos al login.
    if (!isset($_SESSION["usuario_id"])) {

        header("Location: /Agenda/login.php");
        exit;
    }

    /*
     * Si el usuario debe cambiar su contraseña,
     * lo enviamos obligatoriamente a esa página.
     */
    if (
        isset($_SESSION["requiere_cambio_password"]) &&
        $_SESSION["requiere_cambio_password"] == 1
    ) {

        $paginaActual = basename($_SERVER["PHP_SELF"]);

        // Permitimos únicamente permanecer en cambiar_password.php.
        if ($paginaActual !== "cambiar_password.php") {

            header("Location: /Agenda/cambiar_password.php");
            exit;
        }
    }
}


/**
 * Verifica que el usuario tenga el rol permitido.
 */
function exigirRol($rolesPermitidos)
{
    // Primero verificamos que haya iniciado sesión.
    exigirLogin();

    // Si se recibe un solo rol, lo convertimos en arreglo.
    if (!is_array($rolesPermitidos)) {
        $rolesPermitidos = [$rolesPermitidos];
    }

    // Verificamos el rol.
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
    $_SESSION = [];

    session_destroy();
}

?>