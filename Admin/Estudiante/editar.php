<?php

require_once "../../config/conexion.php";
require_once "../../includes/sesion.php";
require_once "../../includes/funciones.php";

// Solo el administrador puede acceder.
exigirRol("ADMIN");


// Obtener el ID del estudiante desde la URL.
$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;


// Si no se recibió un ID válido, regresamos al listado.
if ($id <= 0) {

    header("Location: index.php");
    exit;

}


$error = "";


// Variables del estudiante.
$documento = "";
$nombres = "";
$apellidos = "";
$email = "";
$telefono = "";
$estado = "ACTIVO";


// ---------------------------------------------------------
// BUSCAR EL ESTUDIANTE
// ---------------------------------------------------------

$sql = "
    SELECT
        usuarios.id,
        usuarios.documento,
        usuarios.nombres,
        usuarios.apellidos,
        usuarios.email,
        usuarios.telefono,
        usuarios.estado

    FROM usuarios

    INNER JOIN roles
        ON usuarios.rol_id = roles.id

    WHERE usuarios.id = :id
      AND roles.nombre = 'ESTUDIANTE'

    LIMIT 1
";

$sentencia = $conexion->prepare($sql);

$sentencia->execute([
    ":id" => $id
]);

$estudiante = $sentencia->fetch();


if (!$estudiante) {

    header("Location: index.php");
    exit;

}


// Cargamos los datos actuales.
$documento = $estudiante["documento"];
$nombres = $estudiante["nombres"];
$apellidos = $estudiante["apellidos"];
$email = $estudiante["email"] ?? "";
$telefono = $estudiante["telefono"] ?? "";
$estado = $estudiante["estado"];


// ---------------------------------------------------------
// PROCESAR EL FORMULARIO
// ---------------------------------------------------------

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $documento = trim($_POST["documento"] ?? "");
    $nombres = trim($_POST["nombres"] ?? "");
    $apellidos = trim($_POST["apellidos"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $telefono = trim($_POST["telefono"] ?? "");
    $estado = $_POST["estado"] ?? "ACTIVO";


    // Validar campos obligatorios.
    if (
        empty($documento) ||
        empty($nombres) ||
        empty($apellidos)
    ) {

        $error = "Debe completar los campos obligatorios.";

    } else {

        try {

            // -------------------------------------------------
            // VERIFICAR QUE EL DOCUMENTO NO ESTÉ REPETIDO
            // -------------------------------------------------

            $sqlVerificar = "
                SELECT id
                FROM usuarios
                WHERE documento = :documento
                  AND id <> :id
                LIMIT 1
            ";

            $sentenciaVerificar =
                $conexion->prepare($sqlVerificar);

            $sentenciaVerificar->execute([

                ":documento" => $documento,

                ":id" => $id

            ]);

            $usuarioExistente =
                $sentenciaVerificar->fetch();


            if ($usuarioExistente) {

                $error =
                    "Ya existe otro usuario con este número de documento.";

            } else {

                // -------------------------------------------------
                // ACTUALIZAR LOS DATOS
                // -------------------------------------------------

                $sqlActualizar = "
                    UPDATE usuarios

                    SET
                        documento = :documento,
                        nombres = :nombres,
                        apellidos = :apellidos,
                        email = :email,
                        telefono = :telefono,
                        estado = :estado

                    WHERE id = :id
                ";


                $sentenciaActualizar =
                    $conexion->prepare($sqlActualizar);


                $sentenciaActualizar->execute([

                    ":documento" => $documento,

                    ":nombres" => $nombres,

                    ":apellidos" => $apellidos,

                    ":email" =>
                        !empty($email)
                            ? $email
                            : null,

                    ":telefono" =>
                        !empty($telefono)
                            ? $telefono
                            : null,

                    ":estado" => $estado,

                    ":id" => $id

                ]);


                // Regresar al listado.
                header("Location: index.php");

                exit;

            }

        } catch (PDOException $e) {

            $error =
                "Ocurrió un error al actualizar el estudiante.";

        }

    }

}


$tituloPagina = "Editar estudiante";

require_once "../../includes/header.php";

?>


<div class="card shadow-sm">

    <div class="card-body">

        <h1 class="mb-4">
            Editar estudiante
        </h1>


        <?php if (!empty($error)): ?>

            <div class="alert alert-danger">

                <?= escapar($error) ?>

            </div>

        <?php endif; ?>


        <form method="POST">


            <!-- DOCUMENTO -->

            <div class="mb-3">

                <label
                    for="documento"
                    class="form-label"
                >
                    Número de documento *
                </label>

                <input
                    type="text"
                    class="form-control"
                    id="documento"
                    name="documento"
                    value="<?= escapar($documento) ?>"
                    required
                >

            </div>


            <!-- NOMBRES -->

            <div class="mb-3">

                <label
                    for="nombres"
                    class="form-label"
                >
                    Nombres *
                </label>

                <input
                    type="text"
                    class="form-control"
                    id="nombres"
                    name="nombres"
                    value="<?= escapar($nombres) ?>"
                    required
                >

            </div>


            <!-- APELLIDOS -->

            <div class="mb-3">

                <label
                    for="apellidos"
                    class="form-label"
                >
                    Apellidos *
                </label>

                <input
                    type="text"
                    class="form-control"
                    id="apellidos"
                    name="apellidos"
                    value="<?= escapar($apellidos) ?>"
                    required
                >

            </div>


            <!-- CORREO -->

            <div class="mb-3">

                <label
                    for="email"
                    class="form-label"
                >
                    Correo electrónico
                </label>

                <input
                    type="email"
                    class="form-control"
                    id="email"
                    name="email"
                    value="<?= escapar($email) ?>"
                >

            </div>


            <!-- TELÉFONO -->

            <div class="mb-3">

                <label
                    for="telefono"
                    class="form-label"
                >
                    Teléfono
                </label>

                <input
                    type="text"
                    class="form-control"
                    id="telefono"
                    name="telefono"
                    value="<?= escapar($telefono) ?>"
                >

            </div>


            <!-- ESTADO -->

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