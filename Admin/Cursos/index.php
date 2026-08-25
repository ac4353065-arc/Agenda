<?php

require_once "../../config/conexion.php";
require_once "../../includes/sesion.php";
require_once "../../includes/funciones.php";

// Solo el administrador puede acceder.
exigirRol("ADMIN");

try {

    // Consultamos todos los cursos.
    $sql = "SELECT id, nombre, descripcion, estado
            FROM cursos
            ORDER BY nombre ASC";

    $sentencia = $conexion->prepare($sql);

    $sentencia->execute();

    $cursos = $sentencia->fetchAll();

} catch (PDOException $e) {

    $cursos = [];

}

$tituloPagina = "Gestión de Cursos";

require_once "../../includes/header.php";

?>

<div class="card shadow-sm">

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h1 class="mb-0">
                Gestión de Cursos
            </h1>

            <a
                href="crear.php"
                class="btn btn-primary"
            >
                + Nuevo curso
            </a>

        </div>

        <?php if (empty($cursos)): ?>

            <div class="alert alert-info">

                No hay cursos registrados actualmente.

            </div>

        <?php else: ?>

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead>

                        <tr>

                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Acciones</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($cursos as $curso): ?>

                            <tr>

                                <td>
                                    <?= escapar($curso["id"]) ?>
                                </td>

                                <td>
                                    <?= escapar($curso["nombre"]) ?>
                                </td>

                                <td>
                                    <?= escapar($curso["descripcion"]) ?>
                                </td>

                                <td>

                                    <?php if ($curso["estado"] === "ACTIVO"): ?>

                                        <span class="badge bg-success">
                                            ACTIVO
                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-secondary">
                                            INACTIVO
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <a
                                        href="editar.php?id=<?= $curso["id"] ?>"
                                        class="btn btn-sm btn-warning"
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

        <div class="mt-3">

            <a
                href="/Agenda/admin/index.php"
                class="btn btn-secondary"
            >
                Volver al panel
            </a>

        </div>

    </div>

</div>

<?php require_once "../../includes/footer.php"; ?>