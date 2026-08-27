<?php

require_once __DIR__ . "/../../config/conexion.php";

// Consulta para obtener las asignaturas
$sql = "SELECT * FROM asignaturas ORDER BY id DESC";

$stmt = $conexion->prepare($sql);

$stmt->execute();

$asignaturas = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gestión de Asignaturas</title>

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

            <div class="encabezado-seccion">

                <h2>Gestión de Asignaturas</h2>

                <a href="crear.php" class="btn btn-primario">
                    + Nueva asignatura
                </a>

            </div>

            <table>

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Nombre</th>

                        <th>Descripción</th>

                        <th>Estado</th>

                        <th>Acciones</th>

                    </tr>

                </thead>

                <tbody>

                    <?php if (count($asignaturas) > 0): ?>

                        <?php foreach ($asignaturas as $asignatura): ?>

                            <tr>

                                <td>
                                    <?php echo $asignatura["id"]; ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($asignatura["nombre"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($asignatura["descripcion"] ?? ""); ?>
                                </td>

                                <td>

                                    <?php if ($asignatura["estado"] == "ACTIVO"): ?>

                                        <span class="estado-activo">
                                            ACTIVO
                                        </span>

                                    <?php else: ?>

                                        <span class="estado-inactivo">
                                            INACTIVO
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <a
                                        href="editar.php?id=<?php echo $asignatura["id"]; ?>"
                                        class="btn btn-editar"
                                    >
                                        Editar
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="5">

                                No hay asignaturas registradas.

                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

            <br>

            <a href="../index.php" class="btn btn-secundario">

                Volver al panel

            </a>

        </div>

    </main>

    <footer>

        Agenda Escolar © 2026

    </footer>

</body>

</html>