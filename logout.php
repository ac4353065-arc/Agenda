<?php

require_once "includes/sesion.php";

// Cerramos la sesión actual.
cerrarSesion();

// Redirigimos al login.
header("Location: /Agenda/login.php");
exit;

?>