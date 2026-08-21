<?php

require_once "../includes/sesion.php";
require_once "../includes/funciones.php";

exigirRol("DOCENTE");

$tituloPagina = "Panel Docente";

require_once "../includes/header.php";

?>

<div class="card shadow-sm">

    <div class="card-body">

        <h1>Panel del Docente</h1>

        <p class="lead">
            Bienvenido,
            <?= escapar($_SESSION["nombres"]) ?>
            <?= escapar($_SESSION["apellidos"]) ?>
        </p>

        <p>
            Has iniciado sesión correctamente como
            <strong>DOCENTE</strong>.
        </p>

    </div>

</div>

<?php require_once "../includes/footer.php"; ?>