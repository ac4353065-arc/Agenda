<?php

require_once "../../config/conexion.php";
require_once "../../includes/sesion.php";
require_once "../../includes/funciones.php";

// Solo el administrador puede acceder.
exigirRol("ADMIN");

$error = "";

// Verificamos que llegue un ID.
if (!isset($_GET["id"]) || empty($_GET["id"])) {

    header("Location: index.php");
    exit;
}

$id = $_GET["id"];

// Buscamos la asignación.
$sql = "
    SELECT
        id,
        docente_id,
        curso_id,
        asignatura_id,
        periodo_id
    FROM docente_asignacion
    WHERE id = :id
";

$sentencia = $conexion->prepare($sql);

$sentencia->execute([
    ":id" => $id
]);

$asignacion = $sentencia->fetch();

// Si no existe la asignación, regresamos al listado.
if (!$asignacion) {

    header("Location: index.php");
    exit;
}


// Procesamos el formulario.
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $docente_id = $_POST["docente_id"] ?? "";
    $curso_id = $_POST["curso_id"] ?? "";
    $asignatura_id = $_POST["asignatura_id"] ?? "";
    $periodo_id = $_POST["periodo_id"] ?? "";

    // Validamos que todos los campos estén completos.
    if (
        empty($docente_id) ||
        empty($curso_id) ||
        empty($asignatura_id) ||
        empty($periodo_id)
    ) {

        $error = "Debe seleccionar todos los campos.";

    } else {

        try {

            // Actualizamos la asignación.
            $sqlActualizar = "
                UPDATE docente_asignacion
                SET
                    docente_id = :docente_id,
                    curso_id = :curso_id,
                    asignatura_id = :asignatura_id,
                    periodo_id = :periodo_id
                WHERE id = :id
            ";

            $sentenciaActualizar =
                $conexion->prepare($sqlActualizar);

            $sentenciaActualizar->execute([

                ":docente_id" => $docente_id,

                ":curso_id" => $curso_id,

                ":asignatura_id" => $asignatura_id,

                ":periodo_id" => $periodo_id,

                ":id" => $id

            ]);

            // Regresamos al listado.
            header("Location: index.php");

            exit;

        } catch (PDOException $e) {

            $error = "Ocurrió un error al actualizar la asignación.";
        }
    }

} else {

    // Cargamos los valores actuales.
    $docente_id = $asignacion["docente_id"];
    $curso_id = $asignacion["curso_id"];
    $asignatura_id = $asignacion["asignatura_id"];
    $periodo_id = $asignacion["periodo_id"];
}


// ===============================
// CONSULTAR DOCENTES
// ===============================

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
    ORDER BY usuarios.nombres ASC
";

$sentenciaDocentes = $conexion->prepare($sqlDocentes);

$sentenciaDocentes->execute();

$docentes = $sentenciaDocentes->fetchAll();


// ===============================
// CONSULTAR CURSOS
// ===============================

$sqlCursos = "
    SELECT
        id,
        nombre
    FROM cursos
    WHERE estado = 'ACTIVO'
    ORDER BY nombre ASC
";

$sentenciaCursos = $conexion->prepare($sqlCursos);

$sentenciaCursos->execute();

$cursos = $sentenciaCursos->fetchAll();


// ===============================
// CONSULTAR ASIGNATURAS
// ===============================

$sqlAsignaturas = "
    SELECT
        id,
        nombre
    FROM asignaturas
    WHERE estado = 'ACTIVO'
    ORDER BY nombre ASC
";

$sentenciaAsignaturas = $conexion->prepare($sqlAsignaturas);

$sentenciaAsignaturas->execute();

$asignaturas = $sentenciaAsignaturas->fetchAll();


// ===============================
// CONSULTAR PERÍODOS
// ===============================

$sqlPeriodos = "
    SELECT
        id,
        nombre
    FROM periodos_academicos
    WHERE estado = 'ACTIVO'
    ORDER BY nombre ASC
";

$sentenciaPeriodos = $conexion->prepare($sqlPeriodos);

$sentenciaPeriodos->execute();

$periodos = $sentenciaPeriodos->fetchAll();


$tituloPagina = "Editar asignación";

require_once "../../includes/header.php";

?>

<div class="card shadow-sm">

    <div class="card-body">

        <h1 class="mb-4">
            Editar asignación
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
                    Docente
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
                            <?= ($docente_id == $docente["id"])
                                ? "selected"
                                : "" ?>
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
                            value="<?= escapar($curso["id"]) ?>"
                            <?= ($curso_id == $curso["id"])
                                ? "selected"
                                : "" ?>
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
                    Asignatura
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
                            <?= ($asignatura_id == $asignatura["id"])
                                ? "selected"
                                : "" ?>
                        >

                            <?= escapar($asignatura["nombre"]) ?>

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
                            value="<?= escapar($periodo["id"]) ?>"
                            <?= ($periodo_id == $periodo["id"])
                                ? "selected"
                                : "" ?>
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
                Guardar cambios
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