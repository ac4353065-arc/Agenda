<?php

require_once __DIR__ . "/../../config/conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST["nombre"]);
    $descripcion = trim($_POST["descripcion"]);
    $estado = $_POST["estado"];

    if ($nombre == "") {

        $mensaje = "El nombre de la asignatura es obligatorio.";

    } else {

        $sql = "INSERT INTO asignaturas (nombre, descripcion, estado)
                VALUES (:nombre, :descripcion, :estado)";

        $stmt = $conexion->prepare($sql);

        $stmt->execute([
            ":nombre" => $nombre,
            ":descripcion" => $descripcion,
            ":estado" => $estado
        ]);

        header("Location: index.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Crear asignatura</title>

    <link rel="stylesheet" href="../../assets/css/estilos.css">

</head>

<body>

    <header class="header">

        <div class="contenedor-header">

            <h1>Agenda Escolar</h1>

        </div>

    </header>

    <main class="contenedor">

        <div class="card">

            <h2>Crear nueva asignatura</h2>

            <p>
                Complete la información para registrar una nueva asignatura.
            </p>

            <?php if ($mensaje != ""): ?>

                <p style="color: red;">
                    <?php echo $mensaje; ?>
                </p>

            <?php endif; ?>

            <form method="POST">

                <div class="grupo-formulario">

                    <label for="nombre">
                        Nombre de la asignatura
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

                <br>

                <button
                    type="submit"
                    class="btn btn-primario"
                >
                    Guardar asignatura
                </button>

                <a
                    href="index.php"
                    class="btn btn-secundario"
                >
                    Cancelar
                </a>

            </form>

        </div>

    </main>

    <footer>

        Agenda Escolar © 2026

    </footer>

</body>

</html>