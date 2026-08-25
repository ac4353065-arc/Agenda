<?php
require_once __DIR__ . '/../../config/conexion.php';
require_once __DIR__ . '/../../includes/auth.php';

// Verificar que el usuario sea administrador
verificarRol('ADMINISTRADOR');

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST["nombre"]);
    $descripcion = trim($_POST["descripcion"]);
    $estado = $_POST["estado"];

    // Validar que el nombre no esté vacío
    if (empty($nombre)) {

        $mensaje = "El nombre del curso es obligatorio.";

    } else {

        // Consulta para guardar el curso
        $sql = "INSERT INTO cursos (nombre, descripcion, estado)
                VALUES (?, ?, ?)";

        $stmt = $conexion->prepare($sql);

        $stmt->bind_param(
            "sss",
            $nombre,
            $descripcion,
            $estado
        );

        if ($stmt->execute()) {

            header("Location: index.php?mensaje=creado");
            exit();

        } else {

            $mensaje = "Error al guardar el curso.";

        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear curso - Agenda Escolar</title>

    <link rel="stylesheet"
          href="../../assets/css/estilos.css">
</head>

<body>

<header>
    <h1>Agenda Escolar</h1>
</header>

<main>

    <div class="contenedor">

        <h2>Crear nuevo curso</h2>

        <p>
            Completa la información para registrar un nuevo curso.
        </p>

        <?php if (!empty($mensaje)) { ?>

            <div class="mensaje-error">
                <?php echo $mensaje; ?>
            </div>

        <?php } ?>

        <form method="POST">

            <div class="grupo-formulario">

                <label for="nombre">
                    Nombre del curso
                </label>

                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    required
                >

            </div>


            <div class="grupo-formulario">

                <label for="descripcion">
                    Descripción
                </label>

                <textarea
                    id="descripcion"
                    name="descripcion"
                    rows="5"
                ></textarea>

            </div>


            <div class="grupo-formulario">

                <label for="estado">
                    Estado
                </label>

                <select
                    id="estado"
                    name="estado"
                >

                    <option value="ACTIVO">
                        ACTIVO
                    </option>

                    <option value="INACTIVO">
                        INACTIVO
                    </option>

                </select>

            </div>


            <div class="botones">

                <button
                    type="submit"
                    class="btn-primario"
                >
                    Guardar curso
                </button>

                <a
                    href="index.php"
                    class="btn-secundario"
                >
                    Cancelar
                </a>

            </div>

        </form>

    </div>

</main>

<footer>

    Agenda Escolar © 2026

</footer>

</body>

</html>