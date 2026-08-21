<?php

require_once "../includes/sesion.php";
require_once "../includes/funciones.php";

exigirRol("ESTUDIANTE");

$tituloPagina = "Panel Estudiante";

require_once "../includes/header.php";

?>

<div class="card shadow-sm">

    <div class="card-body">

        <h1>Panel del Estudiante</h1>

        <p class="lead">
            Bienvenido,
            <?= escapar($_SESSION["nombres"]) ?>
            <?= escapar($_SESSION["apellidos"]) ?>
        </p>

        <p>
            Has iniciado sesión correctamente como
            <strong>ESTUDIANTE</strong>.
        </p>

        <div class="mt-4">

            <a
                href="/Agenda/logout.php"
                class="btn btn-danger"
            >
                Cerrar sesión
            </a>

        </div>

    </div>

</div>

<?php require_once "../includes/footer.php"; ?>