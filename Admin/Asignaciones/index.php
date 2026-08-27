<?php

require_once "../../config/conexion.php";
require_once "../../includes/sesion.php";
require_once "../../includes/funciones.php";

// Solo el administrador puede acceder.
exigirRol("ADMIN");

try {

    // Consultamos las asignaciones realizadas.
    $sql = "SELECT
                docente_asignacion.id,

                usuarios.nombres,
                usuarios.apellidos,

                cursos.nombre AS curso,

                asignaturas.nombre AS asignatura,

                periodos_academicos.nombre AS periodo

            FROM docente_asignacion

            INNER JOIN usuarios
                ON docente_asignacion.docente_id = usuarios.id

            INNER JOIN cursos
                ON docente_asignacion.curso_id = cursos.id

            INNER JOIN asignaturas
                ON docente_asignacion.asignatura_id = asignaturas.id

            INNER JOIN periodos_academicos
                ON docente_asignacion.periodo_id = periodos_academicos.id

            ORDER BY
                usuarios.nombres ASC,
                cursos.nombre ASC,
                asignaturas.nombre ASC";

    $sentencia = $conexion->prepare($sql);

    $sentencia->execute();

    $asignaciones = $sentencia->fetchAll();

} catch (PDOException $e) {

    $asignaciones = [];

}

$tituloPagina = "Asignación de Docentes";

require_once "../../includes/header.php";

?>

<div class="card shadow-sm">

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h1 class="mb-0">
                Asignación de Docentes
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

                No hay asignaciones registradas actualmente.

            </div>

        <?php else: ?>

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead>

                        <tr>

                            <th>ID</th>
                            <th>Docente</th>
                            <th>Curso</th>
                            <th>Asignatura</th>
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
                                        $asignacion["nombres"]
                                        . " "
                                        . $asignacion["apellidos"]
                                    ) ?>
                                </td>

                                <td>
                                    <?= escapar($asignacion["curso"]) ?>
                                </td>

                                <td>
                                    <?= escapar($asignacion["asignatura"]) ?>
                                </td>

                                <td>
                                    <?= escapar($asignacion["periodo"]) ?>
                                </td>

                                <td>

                                    <a
                                        href="editar.php?id=<?= escapar($asignacion["id"]) ?>"
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

        <a
            href="../index.php"
            class="btn btn-secondary mt-3"
        >
            Volver al panel
        </a>

    </div>

</div>

<?php

require_once "../../includes/footer.php";

?>