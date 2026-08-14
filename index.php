<?php

require_once "config/conexion.php";
require_once "includes/sesion.php";
require_once "includes/funciones.php";

$tituloPagina = "Inicio";

?>

<?php require_once "includes/header.php"; ?>

<div class="row justify-content-center">

    <div class="col-md-8">

        <div class="card shadow-sm">

            <div class="card-body text-center">

                <h1 class="mb-3">
                    Agenda Escolar
                </h1>

                <p class="lead">
                    Sistema de gestión académica
                </p>

                <div class="alert alert-success">

                    <strong>¡Conexión correcta!</strong>

                    <br>

                    PHP logró conectarse correctamente
                    con la base de datos
                    <strong>agenda_escolar</strong>.

                </div>

                <a
                    href="login.php"
                    class="btn btn-primary"
                >
                    Ir al inicio de sesión
                </a>

            </div>

        </div>

    </div>

</div>

<?php require_once "includes/footer.php"; ?>