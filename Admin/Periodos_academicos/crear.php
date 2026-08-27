<?php

require_once __DIR__ . '/../../config/conexion.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim($_POST['nombre']);
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $estado = $_POST['estado'];

    if (
        $nombre === '' ||
        $fecha_inicio === '' ||
        $fecha_fin === ''
    ) {

        $mensaje = 'Por favor, complete todos los campos.';

    } else {

        // Verificar si el período ya existe
        $consulta = $conexion->prepare(
            "SELECT id FROM periodos_academicos WHERE nombre = ?"
        );

        $consulta->execute([$nombre]);

        $resultado = $consulta->fetch();

        if ($resultado) {

            $mensaje = 'Este período académico ya existe.';

        } else {

            // Guardar el nuevo período
            $sql = "INSERT INTO periodos_academicos
                    (nombre, fecha_inicio, fecha_fin, estado)
                    VALUES (?, ?, ?, ?)";

            $sentencia = $conexion->prepare($sql);

            $sentencia->execute([
                $nombre,
                $fecha_inicio,
                $fecha_fin,
                $estado
            ]);

            header('Location: index.php');
            exit;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Nuevo período académico</title>

    <link
        rel="stylesheet"
        href="../../assets/css/estilos.css"
    >

</head>

<body>

<header class="encabezado">

    <div class="contenedor">
        <h1>Agenda Escolar</h1>
    </div>

</header>

<main class="contenedor">

    <div class="tarjeta">

        <h2>Crear nuevo período académico</h2>

        <p>
            Complete la información para registrar
            un nuevo período académico.
        </p>

        <?php if ($mensaje !== ''): ?>

            <div class="mensaje-error">

                <?php echo $mensaje; ?>

            </div>

        <?php endif; ?>


        <form method="POST">

            <div class="grupo-formulario">

                <label for="nombre">
                    Nombre del período
                </label>

                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    placeholder="Ejemplo: 2027"
                    required
                >

            </div>


            <div class="grupo-formulario">

                <label for="fecha_inicio">
                    Fecha de inicio
                </label>

                <input
                    type="date"
                    id="fecha_inicio"
                    name="fecha_inicio"
                    required
                >

            </div>


            <div class="grupo-formulario">

                <label for="fecha_fin">
                    Fecha de finalización
                </label>

                <input
                    type="date"
                    id="fecha_fin"
                    name="fecha_fin"
                    required
                >

            </div>


            <div class="grupo-formulario">

                <label for="estado">
                    Estado
                </label>

                <select
                    id="estado"
                    name="estado"
                    required
                >

                    <option value="ACTIVO">
                        ACTIVO
                    </option>

                    <option value="INACTIVO">
                        INACTIVO
                    </option>

                </select>

            </div>


            <div class="acciones-formulario">

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Guardar período
                </button>

                <a
                    href="index.php"
                    class="btn btn-secondary"
                >
                    Cancelar
                </a>

            </div>

        </form>

    </div>

</main>


<footer class="pie-pagina">

    <div class="contenedor">
        Agenda Escolar © 2026
    </div>

</footer>

</body>

</html>