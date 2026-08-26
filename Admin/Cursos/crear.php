<?php

require_once "../../config/conexion.php";
require_once "../../includes/sesion.php";
require_once "../../includes/funciones.php";

// Verificamos que solo el ADMIN pueda acceder.
exigirRol("ADMIN");

$error = "";

// Variables para conservar los datos si ocurre un error.
$nombre = "";
$descripcion = "";
$estado = "ACTIVO";


// Procesamos el formulario.
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $estado = $_POST["estado"] ?? "ACTIVO";


    // Validamos el nombre.
    if (empty($nombre)) {

        $error = "El nombre del curso es obligatorio.";

    } elseif (
        $estado !== "ACTIVO" &&
        $estado !== "INACTIVO"
    ) {

        $error = "El estado seleccionado no es válido.";

    } else {

        try {

            // Consulta para insertar el nuevo curso.
            $sql = "INSERT INTO cursos
                    (nombre, descripcion, estado)
                    VALUES
                    (:nombre, :descripcion, :estado)";

            $sentencia = $conexion->prepare($sql);

            $sentencia->execute([
                ":nombre" => $nombre,
                ":descripcion" => $descripcion,
                ":estado" => $estado
            ]);


            // Después de guardar regresamos al listado.
            header(
                "Location: /Agenda/admin/cursos/index.php"
            );

            exit;

        } catch (PDOException $e) {

            $error = "Ocurrió un error al guardar el curso.";

        }
    }
}


// Título de la página.
$tituloPagina = "Crear nuevo curso";

require_once "../../includes/header.php";

?>

<div class="row justify-content-center">

    <div class="col-md-10">

        <div class="card shadow-sm">

            <div class="card-body p-4">

                <h1 class="mb-4">
                    Crear nuevo curso
                </h1>

                <p>
                    Complete la información para registrar
                    un nuevo curso.
                </p>


                <?php if (!empty($error)): ?>

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
                            class="form-control"
                            id="nombre"
                            name="nombre"
                            value="<?= escapar($nombre) ?>"
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
                            class="form-control"
                            id="descripcion"
                            name="descripcion"
                            rows="4"
                        ><?= escapar($descripcion) ?></textarea>

                    </div>


                    <div class="mb-4">

                        <label
                            for="estado"
                            class="form-label"
                        >
                            Estado
                        </label>

                        <select
                            class="form-select"
                            id="estado"
                            name="estado"
                        >

                            <option
                                value="ACTIVO"
                                <?= $estado === "ACTIVO"
                                    ? "selected"
                                    : ""
                                ?>
                            >
                                ACTIVO
                            </option>

                            <option
                                value="INACTIVO"
                                <?= $estado === "INACTIVO"
                                    ? "selected"
                                    : ""
                                ?>
                            >
                                INACTIVO
                            </option>

                        </select>

                    </div>


                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Guardar curso
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

    </div>

</div>

<?php require_once "../../includes/footer.php"; ?>