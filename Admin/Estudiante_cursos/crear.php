<?php

require_once "../../config/conexion.php";
require_once "../../includes/sesion.php";
require_once "../../includes/funciones.php";

// Solo el administrador puede acceder.
exigirRol("ADMIN");

$mensaje = "";
$error = "";

/*
 * Obtener estudiantes
 *
 * Los estudiantes están registrados en la tabla usuarios
 * y se identifican mediante rol_id = 3.
 */
$sqlEstudiantes = "
    SELECT id, nombres, apellidos
    FROM usuarios
    WHERE rol_id = 3
    ORDER BY nombres ASC, apellidos ASC
";

$stmtEstudiantes = $conexion->prepare($sqlEstudiantes);
$stmtEstudiantes->execute();

$estudiantes = $stmtEstudiantes->fetchAll(PDO::FETCH_ASSOC);


/*
 * Obtener cursos activos
 */
$sqlCursos = "
    SELECT id, nombre
    FROM cursos
    WHERE estado = 'ACTIVO'
    ORDER BY nombre ASC
";

$stmtCursos = $conexion->prepare($sqlCursos);
$stmtCursos->execute();

$cursos = $stmtCursos->fetchAll(PDO::FETCH_ASSOC);


/*
 * Obtener períodos académicos activos
 */
$sqlPeriodos = "
    SELECT id, nombre
    FROM periodos_academicos
    WHERE estado = 'ACTIVO'
    ORDER BY nombre DESC
";

$stmtPeriodos = $conexion->prepare($sqlPeriodos);
$stmtPeriodos->execute();

$periodos = $stmtPeriodos->fetchAll(PDO::FETCH_ASSOC);


/*
 * Cuando se envía el formulario
 */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $estudiante_id = isset($_POST["estudiante_id"])
        ? (int) $_POST["estudiante_id"]
        : 0;

    $curso_id = isset($_POST["curso_id"])
        ? (int) $_POST["curso_id"]
        : 0;

    $periodo_id = isset($_POST["periodo_id"])
        ? (int) $_POST["periodo_id"]
        : 0;


    /*
     * Validar que se hayan seleccionado
     * los tres datos.
     */
    if ($estudiante_id <= 0 || $curso_id <= 0 || $periodo_id <= 0) {

        $error = "Debe seleccionar el estudiante, el curso y el período académico.";

    } else {

        /*
         * Verificar que el estudiante no esté
         * asignado nuevamente al mismo curso
         * en el mismo período.
         */
        $sqlExiste = "
            SELECT id
            FROM estudiante_curso
            WHERE estudiante_id = ?
              AND curso_id = ?
              AND periodo_id = ?
        ";

        $stmtExiste = $conexion->prepare($sqlExiste);

        $stmtExiste->execute([
            $estudiante_id,
            $curso_id,
            $periodo_id
        ]);

        $existe = $stmtExiste->fetch(PDO::FETCH_ASSOC);


        if ($existe) {

            $error = "Este estudiante ya está asignado a ese curso en el período seleccionado.";

        } else {

            /*
             * Registrar la asignación.
             */
            $sqlInsertar = "
                INSERT INTO estudiante_curso
                (
                    estudiante_id,
                    curso_id,
                    periodo_id
                )
                VALUES (?, ?, ?)
            ";

            $stmtInsertar = $conexion->prepare($sqlInsertar);

            $stmtInsertar->execute([
                $estudiante_id,
                $curso_id,
                $periodo_id
            ]);


            /*
             * Volver al listado después de guardar.
             */
            header("Location: index.php");
            exit;
        }
    }
}


$tituloPagina = "Nueva asignación de estudiante";

require_once "../../includes/header.php";

?>

<div class="card shadow-sm">

    <div class="card-body">

        <h1 class="mb-4">
            Nueva asignación de estudiante
        </h1>

        <p>
            Seleccione el estudiante, el curso y el período académico.
        </p>


        <?php if ($error != ""): ?>

            <div class="alert alert-danger">
                <?= escapar($error) ?>
            </div>

        <?php endif; ?>


        <form method="POST" action="crear.php">


            <!-- ESTUDIANTE -->

            <div class="mb-3">

                <label for="estudiante_id" class="form-label">
                    Estudiante
                </label>

                <select
                    name="estudiante_id"
                    id="estudiante_id"
                    class="form-select"
                    required
                >

                    <option value="">
                        Seleccione un estudiante
                    </option>

                    <?php foreach ($estudiantes as $estudiante): ?>

                        <option
                            value="<?= $estudiante["id"] ?>"
                            <?= (
                                isset($_POST["estudiante_id"])
                                &&
                                $_POST["estudiante_id"] == $estudiante["id"]
                            )
                                ? "selected"
                                : ""
                            ?>
                        >

                            <?= escapar(
                                $estudiante["nombres"]
                                . " "
                                . $estudiante["apellidos"]
                            ) ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <!-- CURSO -->

            <div class="mb-3">

                <label for="curso_id" class="form-label">
                    Curso
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
                            value="<?= $curso["id"] ?>"
                            <?= (
                                isset($_POST["curso_id"])
                                &&
                                $_POST["curso_id"] == $curso["id"]
                            )
                                ? "selected"
                                : ""
                            ?>
                        >

                            <?= escapar($curso["nombre"]) ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <!-- PERÍODO -->

            <div class="mb-4">

                <label for="periodo_id" class="form-label">
                    Período académico
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
                            value="<?= $periodo["id"] ?>"
                            <?= (
                                isset($_POST["periodo_id"])
                                &&
                                $_POST["periodo_id"] == $periodo["id"]
                            )
                                ? "selected"
                                : ""
                            ?>
                        >

                            <?= escapar($periodo["nombre"]) ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <!-- BOTONES -->

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


<?php require_once "../../includes/footer.php"; ?>