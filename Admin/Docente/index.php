<?php

require_once "../../config/conexion.php";
require_once "../../includes/sesion.php";
require_once "../../includes/funciones.php";

// Verificamos que solo el administrador pueda entrar.
exigirRol("ADMIN");


// Consultamos todos los docentes.

$sql = "
    SELECT
        usuarios.id,
        usuarios.documento,
        usuarios.nombres,
        usuarios.apellidos,
        usuarios.email,
        usuarios.telefono,
        usuarios.estado
    FROM usuarios

    INNER JOIN roles
        ON usuarios.rol_id = roles.id

    WHERE roles.nombre = 'DOCENTE'

    ORDER BY usuarios.nombres ASC
";

$sentencia = $conexion->prepare($sql);

$sentencia->execute();

$docentes = $sentencia->fetchAll();


// Título de la página.

$tituloPagina = "Gestión de Docentes";

require_once "../../includes/header.php";

?>

<div class="card shadow-sm">

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h1 class="mb-0">
                Gestión de Docentes
            </h1>

            <a
                href="crear.php"
                class="btn btn-primary"
            >
                + Nuevo docente
            </a>

        </div>


        <div class="table-responsive">

            <table class="table table-bordered table-hover">

                <thead class="table-light">

                    <tr>

                        <th>ID</th>

                        <th>Documento</th>

                        <th>Nombre completo</th>

                        <th>Correo</th>

                        <th>Teléfono</th>

                        <th>Estado</th>

                        <th>Acciones</th>

                    </tr>

                </thead>


                <tbody>

                    <?php if (count($docentes) > 0): ?>

                        <?php foreach ($docentes as $docente): ?>

                            <tr>

                                <td>

                                    <?= escapar($docente["id"]) ?>

                                </td>


                                <td>

                                    <?= escapar($docente["documento"]) ?>

                                </td>


                                <td>

                                    <?= escapar(
                                        $docente["nombres"]
                                        . " "
                                        . $docente["apellidos"]
                                    ) ?>

                                </td>


                                <td>

                                    <?= escapar(
                                        $docente["email"]
                                        ?? ""
                                    ) ?>

                                </td>


                                <td>

                                    <?= escapar(
                                        $docente["telefono"]
                                        ?? ""
                                    ) ?>

                                </td>


                                <td>

                                    <?php if ($docente["estado"] === "ACTIVO"): ?>

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
                                        href="editar.php?id=<?= $docente["id"] ?>"
                                        class="btn btn-warning btn-sm"
                                    >
                                        Editar
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>


                    <?php else: ?>

                        <tr>

                            <td
                                colspan="7"
                                class="text-center"
                            >

                                No hay docentes registrados.

                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>


        <a
            href="../index.php"
            class="btn btn-secondary"
        >

            Volver al panel

        </a>

    </div>

</div>


<?php

require_once "../../includes/footer.php";

?>