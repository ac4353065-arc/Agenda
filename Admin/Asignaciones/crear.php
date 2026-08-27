<?php

require_once "../../config/conexion.php";
require_once "../../includes/sesion.php";
require_once "../../includes/funciones.php";

// Solo el administrador puede acceder.
exigirRol("ADMIN");

$error = "";

// Variables para conservar las selecciones.
$docente_id = "";
$curso_id = "";
$asignatura_id = "";
$periodo_id = "";


try {

    /*
     * Consultamos los docentes activos.
     * Solo mostramos usuarios que tengan
     * el rol DOCENTE.
     */
    $sqlDocentes = "
        SELECT
            usuarios.id,
            usuarios.nombres,
            usuarios.apellidos
        FROM usuarios
        INNER JOIN roles
            ON usuarios.rol_id = roles.id
        WHERE roles.nombre = 'DOCENTE'
        AND usuarios.estado = 'ACTIVO'
        ORDER BY usuarios.nombres, usuarios.apellidos
    ";

    $sentenciaDocentes =
        $conexion->prepare($sqlDocentes);

    $sentenciaDocentes->execute();

    $docentes =
        $sentenciaDocentes->fetchAll();


    // Consultamos los cursos activos.
    $sqlCursos = "
        SELECT id, nombre
        FROM cursos
        WHERE estado = 'ACTIVO'
        ORDER BY nombre
    ";

    $sentenciaCursos =
        $conexion->prepare($sqlCursos);

    $sentenciaCursos->execute();

    $cursos =
        $sentenciaCursos->fetchAll();


    // Consultamos las asignaturas activas.
    $sqlAsignaturas = "
        SELECT id, nombre
        FROM asignaturas
        WHERE estado = 'ACTIVO'
        ORDER BY nombre
    ";

    $sentenciaAsignaturas =
        $conexion->prepare($sqlAsignaturas);

    $sentenciaAsignaturas->execute();

    $asignaturas =
        $sentenciaAsignaturas->fetchAll();


    // Consultamos los períodos activos.
    $sqlPeriodos = "
        SELECT id, nombre
        FROM periodos_academicos
        WHERE estado = 'ACTIVO'
        ORDER BY fecha_inicio DESC
    ";

    $sentenciaPeriodos =
        $conexion->prepare($sqlPeriodos);

    $sentenciaPeriodos->execute();

    $periodos =
        $sentenciaPeriodos->fetchAll();


} catch (PDOException $e) {

    $error =
        "Ocurrió un error al cargar la información.";

    $docentes = [];
    $cursos = [];
    $asignaturas = [];
    $periodos = [];

}


// Procesamos el formulario.
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Recibimos los datos.
    $docente_id =
        $_POST["docente_id"] ?? "";

    $curso_id =
        $_POST["curso_id"] ?? "";

    $asignatura_id =
        $_POST["asignatura_id"] ?? "";

    $periodo_id =
        $_POST["periodo_id"] ?? "";


    // Validamos que todos los campos estén seleccionados.
    if (
        empty($docente_id) ||
        empty($curso_id) ||
        empty($asignatura_id) ||
        empty($periodo_id)
    ) {

        $error =
            "Debe seleccionar el docente, curso, asignatura y período académico.";

    } else {

        try {

            /*
             * Verificamos si la asignación ya existe.
             *
             * La combinación docente + curso + asignatura
             * debe evitar duplicados.
             */
            $sqlVerificar = "
                SELECT id
                FROM docente_asignacion
                WHERE docente_id = :docente_id
                AND curso_id = :curso_id
                AND asignatura_id = :asignatura_id
                LIMIT 1
            ";

            $sentenciaVerificar =
                $conexion->prepare($sqlVerificar);

            $sentenciaVerificar->execute([

                ":docente_id" => $docente_id,

                ":curso_id" => $curso_id,

                ":asignatura_id" => $asignatura_id

            ]);

            $asignacionExistente =
                $sentenciaVerificar->fetch();


            if ($asignacionExistente) {

                $error =
                    "Esta asignación ya se encuentra registrada.";

            } else {

                // Insertamos la nueva asignación.
                $sqlInsertar = "
                    INSERT INTO docente_asignacion
                    (
                        docente_id,
                        curso_id,
                        asignatura_id,
                        periodo_id
                    )
                    VALUES
                    (
                        :docente_id,
                        :curso_id,
                        :asignatura_id,
                        :periodo_id
                    )
                ";

                $sentenciaInsertar =
                    $conexion->prepare($sqlInsertar);

                $sentenciaInsertar->execute([

                    ":docente_id" =>
                        $docente_id,

                    ":curso_id" =>
                        $curso_id,

                    ":asignatura_id" =>
                        $asignatura_id,

                    ":periodo_id" =>
                        $periodo_id

                ]);


                // Regresamos al listado.
                header(
                    "Location: index.php"
                );

                exit;

            }

        } catch (PDOException $e) {

            $error =
                "Ocurrió un error al guardar la asignación.";

        }

    }

}


$tituloPagina = "Nueva asignación";

require_once "../../includes/header.php";

?>

<div class="card shadow-sm">

    <div class="card-body">

        <h1 class="mb-4">
            Nueva asignación de docente
        </h1>


        <?php if (!empty($error)): ?>

            <div class="alert alert-danger">

                <?= escapar($error) ?>

            </div>

        <?php endif; ?>


        <form method="POST">


            <!-- DOCENTE -->

            <div class="mb-3">

                <label
                    for="docente_id"
                    class="form-label"
                >
                    Docente *
                </label>

                <select
                    name="docente_id"
                    id="docente_id"
                    class="form-select"
                    required
                >

                    <option value="">
                        Seleccione un docente
                    </option>


                    <?php foreach ($docentes as $docente): ?>

                        <option
                            value="<?= escapar($docente["id"]) ?>"

                            <?php
                            if (
                                $docente_id == $docente["id"]
                            ):
                            ?>

                                selected

                            <?php endif; ?>
                        >

                            <?= escapar(
                                $docente["nombres"]
                                . " "
                                . $docente["apellidos"]
                            ) ?>

                        </option>

                    <?php endforeach; ?>


                </select>

            </div>


            <!-- CURSO -->

            <div class="mb-3">

                <label
                    for="curso_id"
                    class="form-label"
                >
                    Curso *
                </label>

                <select
                    name="curso_id"
                    id="curso_id"
                    class="form-select"
                    required
                >

                    <option value="">
                        Seleccione un curso
                    </option>


                    <?php foreach ($cursos as $curso): ?>

                        <option
                            value="<?= escapar($curso["id"]) ?>"

                            <?php
                            if (
                                $curso_id == $curso["id"]
                            ):
                            ?>

                                selected

                            <?php endif; ?>
                        >

                            <?= escapar($curso["nombre"]) ?>

                        </option>

                    <?php endforeach; ?>


                </select>

            </div>


            <!-- ASIGNATURA -->

            <div class="mb-3">

                <label
                    for="asignatura_id"
                    class="form-label"
                >
                    Asignatura *
                </label>

                <select
                    name="asignatura_id"
                    id="asignatura_id"
                    class="form-select"
                    required
                >

                    <option value="">
                        Seleccione una asignatura
                    </option>


                    <?php foreach ($asignaturas as $asignatura): ?>

                        <option
                            value="<?= escapar($asignatura["id"]) ?>"

                            <?php
                            if (
                                $asignatura_id == $asignatura["id"]
                            ):
                            ?>

                                selected

                            <?php endif; ?>
                        >

                            <?= escapar(
                                $asignatura["nombre"]
                            ) ?>

                        </option>

                    <?php endforeach; ?>


                </select>

            </div>


            <!-- PERÍODO -->

            <div class="mb-4">

                <label
                    for="periodo_id"
                    class="form-label"
                >
                    Período académico *
                </label>

                <select
                    name="periodo_id"
                    id="periodo_id"
                    class="form-select"
                    required
                >

                    <option value="">
                        Seleccione un período
                    </option>


                    <?php foreach ($periodos as $periodo): ?>

                        <option
                            value="<?= escapar($periodo["id"]) ?>"

                            <?php
                            if (
                                $periodo_id == $periodo["id"]
                            ):
                            ?>

                                selected

                            <?php endif; ?>
                        >

                            <?= escapar(
                                $periodo["nombre"]
                            ) ?>

                        </option>

                    <?php endforeach; ?>


                </select>

            </div>


            <button
                type="submit"
                class="btn btn-primary"
            >
                Guardar asignación
            </button>


            <a
                href="index.php"
                class="btn btn-secondary"
            >
                Cancelar
            </a>


        </form>

    </div>

</div>


<?php

require_once "../../includes/footer.php";

?>