<?php

/**
 * Escapa caracteres especiales para mostrar información
 * de forma segura dentro de HTML.
 */
function escapar($texto)
{
    return htmlspecialchars(
        $texto ?? '',
        ENT_QUOTES,
        'UTF-8'
    );
}


/**
 * Redirige al usuario hacia otra página.
 */
function redireccionar($pagina)
{
    header("Location: " . $pagina);
    exit;
}


/**
 * Comprueba si existe una sesión iniciada.
 */
function usuarioAutenticado()
{
    return isset($_SESSION['usuario_id']);
}


/**
 * Obtiene el ID del usuario que inició sesión.
 */
function obtenerUsuarioId()
{
    return $_SESSION['usuario_id'] ?? null;
}


/**
 * Obtiene el rol del usuario que inició sesión.
 */
function obtenerRolUsuario()
{
    return $_SESSION['rol'] ?? null;
}


/**
 * Comprueba si el usuario tiene un determinado rol.
 */
function tieneRol($rol)
{
    return isset($_SESSION['rol'])
        && $_SESSION['rol'] === $rol;
}


/**
 * Muestra un mensaje almacenado en sesión
 * y después lo elimina.
 */
function mostrarMensaje()
{
    if (isset($_SESSION['mensaje'])) {

        $mensaje = $_SESSION['mensaje'];

        unset($_SESSION['mensaje']);

        return $mensaje;
    }

    return null;
}


/**
 * Guarda un mensaje temporal en la sesión.
 */
function establecerMensaje($mensaje)
{
    $_SESSION['mensaje'] = $mensaje;
}

?>