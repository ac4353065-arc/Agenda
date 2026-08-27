<?php

require_once "../../config/conexion.php";
require_once "../../includes/sesion.php";
require_once "../../includes/funciones.php";

// Solo el administrador puede acceder.
exigirRol("ADMIN");

try {

    // Consultamos todos los períodos académicos.
    $sql = "SELECT *
            FROM periodos_academicos
            ORDER BY id ASC";

    $sentencia = $conexion->prepare($sql);

    $sentencia->execute();

    $periodos = $sentencia->fetchAll();

} catch (PDOException $e) {

    $periodos = [];

}

$tituloPagina = "Gestión de Períodos Académicos";

require_once "../../includes/header.php";

?>

<div class="card shadow-sm">

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h1 class="mb-0">
                Gestión de Períodos Académicos
            </h1>

            <a
                href="crear.php"
                class="btn btn-primary"
            >
                + Nuevo período
            </a>

        </div>


        <?php if (empty($periodos)): ?>

            <div class="alert alert-info">

                No hay períodos académicos registrados actualmente.

            </div>

        <?php else: ?>

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead>

                        <tr>

                            <th>ID</th>
                            <th>Período</th>
                            <th>Estado</th>
                            <th>Acciones</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($periodos as $periodo): ?>

                            <tr>

                                <td>
                                    <?= escapar($periodo["id"]) ?>
                                </td>

                                <td>
                                    <?= escapar($periodo["nombre"]) ?>
                                </td>

                                <td>

                                    <?php if ($periodo["estado"] === "ACTIVO"): ?>

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
                                        href="editar.php?id=<?= $periodo["id"] ?>"
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