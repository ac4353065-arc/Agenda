<?php

require_once "../../config/conexion.php";
require_once "../../includes/sesion.php";
require_once "../../includes/funciones.php";

// Solo el administrador puede acceder.
exigirRol("ADMIN");

try {

    /*
     * Consultamos las asignaciones de estudiantes.
     *
     * Los estudiantes están guardados en la tabla usuarios.
     * Se identifican porque tienen rol_id = 3.
     */

    $sql = "
        SELECT
            ec.id,
            u.nombres,
            u.apellidos,
            c.nombre AS curso,
            p.nombre AS periodo

        FROM estudiante_curso ec

        INNER JOIN usuarios u
            ON ec.estudiante_id = u.id

        INNER JOIN cursos c
            ON ec.curso_id = c.id

        INNER JOIN periodos_academicos p
            ON ec.periodo_id = p.id

        WHERE u.rol_id = 3

        ORDER BY ec.id DESC
    ";

    $sentencia = $conexion->prepare($sql);

    $sentencia->execute();

    $asignaciones = $sentencia->fetchAll();

} catch (PDOException $e) {

    $asignaciones = [];

}

$tituloPagina = "Asignación de Estudiantes";

require_once "../../includes/header.php";

?>

<div class="card shadow-sm">

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h1 class="mb-0">
                Asignación de Estudiantes
            </h1>

            <a
                href="crear.php"
                class="btn btn-primary"
            >
                + Nueva asignación
            </a>

        </div>


        <?php if (empty($asignaciones)): ?>

            <div class="alert alert-info">

                No hay estudiantes asignados actualmente.

            </div>

        <?php else: ?>

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Estudiante</th>

                            <th>Curso</th>

                            <th>Período</th>

                            <th>Acciones</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($asignaciones as $asignacion): ?>

                            <tr>

                                <td>
                                    <?= escapar($asignacion["id"]) ?>
                                </td>

                                <td>
                                    <?= escapar(
                                        $asignacion["nombres"] . " " .
                                        $asignacion["apellidos"]
                                    ) ?>
                                </td>

                                <td>
                                    <?= escapar($asignacion["curso"]) ?>
                                </td>

                                <td>
                                    <?= escapar($asignacion["periodo"]) ?>
                                </td>

                                <td>

                                    <a
                                        href="editar.php?id=<?= $asignacion["id"] ?>"
                                        class="btn btn-warning btn-sm"
                                    >
                                        Editar
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php endif; ?>


        <div class="mt-4">

            <a
                href="../index.php"
                class="btn btn-secondary"
            >
                Volver al panel
            </a>

        </div>

    </div>

</div>


<?php require_once "../../includes/footer.php"; ?>