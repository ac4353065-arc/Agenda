<?php

require_once "../../config/conexion.php";
require_once "../../includes/sesion.php";
require_once "../../includes/funciones.php";


// Verificamos que el usuario tenga sesión iniciada.
if (!isset($_SESSION["usuario_id"])) {

    header("Location: /Agenda/login.php");
    exit;
}


// Verificamos que sea administrador.
if ($_SESSION["rol"] !== "ADMIN") {

    header("Location: /Agenda/login.php");
    exit;
}


// Verificamos que llegue el ID del período.
if (!isset($_GET["id"]) || empty($_GET["id"])) {

    header("Location: index.php");
    exit;
}


$id = $_GET["id"];


// Buscamos el período académico.
$sql = "SELECT
            id,
            nombre,
            fecha_inicio,
            fecha_fin,
            estado
        FROM periodos_academicos
        WHERE id = :id";


$sentencia = $conexion->prepare($sql);

$sentencia->execute([
    ":id" => $id
]);


$periodo = $sentencia->fetch();


// Si no existe el período, regresamos.
if (!$periodo) {

    header("Location: index.php");
    exit;
}


$error = "";


// Procesamos el formulario.
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre"] ?? "");

    $fecha_inicio = $_POST["fecha_inicio"] ?? "";

    $fecha_fin = $_POST["fecha_fin"] ?? "";

    $estado = $_POST["estado"] ?? "";


    // Validamos los campos.
    if (
        empty($nombre) ||
        empty($fecha_inicio) ||
        empty($fecha_fin) ||
        empty($estado)
    ) {

        $error = "Todos los campos son obligatorios.";

    } elseif ($fecha_fin < $fecha_inicio) {

        $error = "La fecha de finalización no puede ser menor que la fecha de inicio.";

    } else {

        try {

            // Actualizamos el período.
            $sql = "UPDATE periodos_academicos
                    SET
                        nombre = :nombre,
                        fecha_inicio = :fecha_inicio,
                        fecha_fin = :fecha_fin,
                        estado = :estado
                    WHERE id = :id";


            $sentencia = $conexion->prepare($sql);


            $sentencia->execute([
                ":nombre" => $nombre,
                ":fecha_inicio" => $fecha_inicio,
                ":fecha_fin" => $fecha_fin,
                ":estado" => $estado,
                ":id" => $id
            ]);


            // Regresamos al listado.
            header("Location: index.php");
            exit;

        } catch (PDOException $e) {

            $error = "Ocurrió un error al actualizar el período académico.";
        }
    }

} else {

    // Si es la primera vez que abrimos la página,
    // cargamos los datos actuales.
    $nombre = $periodo["nombre"];

    $fecha_inicio = $periodo["fecha_inicio"];

    $fecha_fin = $periodo["fecha_fin"];

    $estado = $periodo["estado"];
}


$tituloPagina = "Editar período académico";

require_once "../../includes/header.php";

?>


<div class="container mt-4">

    <div class="card shadow-sm">

        <div class="card-body p-4">

            <h2 class="mb-4">
                Editar período académico
            </h2>


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
                        Nombre del período
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
                        for="fecha_inicio"
                        class="form-label"
                    >
                        Fecha de inicio
                    </label>

                    <input
                        type="date"
                        class="form-control"
                        id="fecha_inicio"
                        name="fecha_inicio"
                        value="<?= escapar($fecha_inicio) ?>"
                        required
                    >

                </div>


                <div class="mb-3">

                    <label
                        for="fecha_fin"
                        class="form-label"
                    >
                        Fecha de finalización
                    </label>

                    <input
                        type="date"
                        class="form-control"
                        id="fecha_fin"
                        name="fecha_fin"
                        value="<?= escapar($fecha_fin) ?>"
                        required
                    >

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
                        required
                    >

                        <option
                            value="ACTIVO"
                            <?= $estado === "ACTIVO" ? "selected" : "" ?>
                        >
                            ACTIVO
                        </option>

                        <option
                            value="INACTIVO"
                            <?= $estado === "INACTIVO" ? "selected" : "" ?>
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

</div>


<?php

require_once "../../includes/footer.php";

?>