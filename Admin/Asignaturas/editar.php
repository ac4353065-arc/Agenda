<?php

require_once __DIR__ . "/../../config/conexion.php";

// Verificar que llegue el ID
if (!isset($_GET["id"])) {

    header("Location: index.php");
    exit();

}

$id = $_GET["id"];


// Buscar la asignatura
$sql = "SELECT * FROM asignaturas WHERE id = :id";

$stmt = $conexion->prepare($sql);

$stmt->execute([
    ":id" => $id
]);

$asignatura = $stmt->fetch();


// Si no existe la asignatura
if (!$asignatura) {

    header("Location: index.php");
    exit();

}


// Guardar cambios
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST["nombre"]);
    $descripcion = trim($_POST["descripcion"]);
    $estado = $_POST["estado"];

    if ($nombre == "") {

        $mensaje = "El nombre de la asignatura es obligatorio.";

    } else {

        $sql = "UPDATE asignaturas
                SET nombre = :nombre,
                    descripcion = :descripcion,
                    estado = :estado
                WHERE id = :id";

        $stmt = $conexion->prepare($sql);

        $stmt->execute([
            ":nombre" => $nombre,
            ":descripcion" => $descripcion,
            ":estado" => $estado,
            ":id" => $id
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

    <title>Editar asignatura</title>

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

            <h2>Editar asignatura</h2>

            <?php if (isset($mensaje)): ?>

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
                        value="<?php echo htmlspecialchars($asignatura["nombre"]); ?>"
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
                    ><?php echo htmlspecialchars($asignatura["descripcion"] ?? ""); ?></textarea>

                </div>


                <div class="grupo-formulario">

                    <label for="estado">

                        Estado

                    </label>

                    <select
                        id="estado"
                        name="estado"
                    >

                        <option
                            value="ACTIVO"
                            <?php
                            if ($asignatura["estado"] == "ACTIVO") {
                                echo "selected";
                            }
                            ?>
                        >

                            ACTIVO

                        </option>


                        <option
                            value="INACTIVO"
                            <?php
                            if ($asignatura["estado"] == "INACTIVO") {
                                echo "selected";
                            }
                            ?>
                        >

                            INACTIVO

                        </option>

                    </select>

                </div>


                <br>


                <button
                    type="submit"
                    class="btn btn-primario"
                >

                    Guardar cambios

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