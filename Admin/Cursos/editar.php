<?php

require_once "../../config/conexion.php";
require_once "../../includes/sesion.php";
require_once "../../includes/funciones.php";

// Solo el administrador puede acceder.
exigirRol("ADMIN");

// Verificamos que llegue el ID del curso.
if (!isset($_GET["id"]) || empty($_GET["id"])) {

    header("Location: index.php");
    exit;

}

$id = $_GET["id"];

try {

    // Buscamos el curso por su ID.
    $sql = "SELECT id, nombre, descripcion, estado
            FROM cursos
            WHERE id = :id";

    $sentencia = $conexion->prepare($sql);

    $sentencia->execute([
        ":id" => $id
    ]);

    $curso = $sentencia->fetch();

    // Si el curso no existe, regresamos a la lista.
    if (!$curso) {

        header("Location: index.php");
        exit;

    }

} catch (PDOException $e) {

    header("Location: index.php");
    exit;

}


// Procesamos el formulario cuando se presiona Guardar cambios.
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre"]);
    $descripcion = trim($_POST["descripcion"]);
    $estado = $_POST["estado"];

    try {

        $sql = "UPDATE cursos
                SET nombre = :nombre,
                    descripcion = :descripcion,
                    estado = :estado
                WHERE id = :id";

        $sentencia = $conexion->prepare($sql);

        $sentencia->execute([
            ":nombre" => $nombre,
            ":descripcion" => $descripcion,
            ":estado" => $estado,
            ":id" => $id
        ]);

        // Después de actualizar, volvemos a la lista.
        header("Location: index.php");
        exit;

    } catch (PDOException $e) {

        $error = "Ocurrió un error al actualizar el curso.";

    }

}


$tituloPagina = "Editar Curso";

require_once "../../includes/header.php";

?>

<div class="card shadow-sm">

    <div class="card-body">

        <h1 class="mb-4">
            Editar curso
        </h1>

        <?php if (isset($error)): ?>

            <div class="alert alert-danger">

                <?= escapar($error) ?>

            </div>

        <?php endif; ?>


        <form method="POST">

            <div class="mb-3">

                <label
                    for="nombre"
                    class="form-label"
                >
                    Nombre del curso
                </label>

                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    class="form-control"
                    value="<?= escapar($curso["nombre"]) ?>"
                    required
                >

            </div>


            <div class="mb-3">

                <label
                    for="descripcion"
                    class="form-label"
                >
                    Descripción
                </label>

                <textarea
                    id="descripcion"
                    name="descripcion"
                    class="form-control"
                    rows="4"
                ><?= escapar($curso["descripcion"]) ?></textarea>

            </div>


            <div class="mb-4">

                <label
                    for="estado"
                    class="form-label"
                >
                    Estado
                </label>

                <select
                    id="estado"
                    name="estado"
                    class="form-select"
                >

                    <option
                        value="ACTIVO"
                        <?= $curso["estado"] === "ACTIVO" ? "selected" : "" ?>
                    >
                        ACTIVO
                    </option>

                    <option
                        value="INACTIVO"
                        <?= $curso["estado"] === "INACTIVO" ? "selected" : "" ?>
                    >
                        INACTIVO
                    </option>

                </select>

            </div>


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

<?php require_once "../../includes/footer.php"; ?>