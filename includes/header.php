<?php

if (!isset($tituloPagina)) {
    $tituloPagina = "Agenda Escolar";
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= escapar($tituloPagina) ?> - Agenda Escolar
    </title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Estilos propios -->
    <link
        rel="stylesheet"
        href="/agenda_escolar/assets/css/estilos.css"
    >

</head>

<body>

<nav class="navbar navbar-expand-lg bg-primary navbar-dark">

    <div class="container">

        <a
            class="navbar-brand"
            href="/agenda_escolar/"
        >
            Agenda Escolar
        </a>

    </div>

</nav>

<main class="container py-4">