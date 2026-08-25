<?php

require_once '../../config/conexion.php';
require_once '../../includes/auth.php';

// Verificar que exista una sesión iniciada
exigirLogin();

// Verificar que el usuario sea administrador
if ($_SESSION['rol'] !== 'ADMINISTRADOR') {
    header('Location: ../../login.php');
    exit;
}

$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $estado = $_POST['estado'];

    if ($nombre === '') {

        $error = 'El nombre del curso es obligatorio.';

    } else {

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

            header('Location: index.php');
            exit;

        } else {

            $error = 'Ocurrió un error al guardar el curso.';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nuevo Curso - Agenda Escolar</title>

    <link rel="stylesheet" href="../../assets/css/estilos.css">

</head>

<body>

<header>

    <div class="contenedor">

        <h1>Agenda Escolar</h1>

    </div>

</header>


<main class="contenedor">

    <section class="card">

        <h2>Nuevo Curso</h2>


        <?php if ($error !== ''): ?>

            <div class="alerta alerta-error">

                <?php echo $error; ?>

            </div>

        <?php endif; ?>


        <form method="POST" action="">

            <div class="form-group">

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


            <div class="form-group">

                <label for="descripcion">

                    Descripción

                </label>

                <textarea
                    id="descripcion"
                    name="descripcion"
                    rows="4"
                ></textarea>

            </div>


            <div class="form-group">

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

    </section>

</main>


<footer>

    <p>

        Agenda Escolar &copy; 2026

    </p>

</footer>

</body>

</html>