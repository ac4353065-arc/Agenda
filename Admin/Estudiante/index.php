<?php

require_once "../../config/conexion.php";
require_once "../../includes/sesion.php";
require_once "../../includes/funciones.php";

// Verificamos que solo el administrador pueda entrar.
exigirRol("ADMIN");


// Consultamos los estudiantes registrados.

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

    WHERE roles.nombre = 'ESTUDIANTE'

    ORDER BY usuarios.id DESC
";

$sentencia = $conexion->prepare($sql);

$sentencia->execute();

$estudiantes = $sentencia->fetchAll();


// Título de la página.

$tituloPagina = "Gestión de estudiantes";

require_once "../../includes/header.php";

?>


<div class="card shadow-sm">

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h1 class="mb-0">
                Gestión de Estudiantes
            </h1>

            <a
                href="crear.php"
                class="btn btn-primary"
            >
                + Nuevo estudiante
            </a>

        </div>


        <?php if (count($estudiantes) > 0): ?>

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead>

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

                        <?php foreach ($estudiantes as $estudiante): ?>

                            <tr>

                                <td>
                                    <?= escapar($estudiante["id"]) ?>
                                </td>


                                <td>
                                    <?= escapar($estudiante["documento"]) ?>
                                </td>


                                <td>

                                    <?= escapar(
                                        $estudiante["nombres"] . " " .
                                        $estudiante["apellidos"]
                                    ) ?>

                                </td>


                                <td>

                                    <?= escapar(
                                        $estudiante["email"] ?? ""
                                    ) ?>

                                </td>


                                <td>

                                    <?= escapar(
                                        $estudiante["telefono"] ?? ""
                                    ) ?>

                                </td>


                                <td>

                                    <?php if ($estudiante["estado"] === "ACTIVO"): ?>

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
                                        href="editar.php?id=<?= $estudiante["id"] ?>"
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


        <?php else: ?>

            <div class="alert alert-info">

                No hay estudiantes registrados actualmente.

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