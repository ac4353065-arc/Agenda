<?php

require_once "../../config/conexion.php";
require_once "../../includes/sesion.php";
require_once "../../includes/funciones.php";

// Verificamos que solo el administrador pueda entrar.
exigirRol("ADMIN");

$error = "";

// Verificamos que llegue un ID válido.
if (!isset($_GET["id"]) || empty($_GET["id"])) {

    header("Location: index.php");
    exit;
}

$id = $_GET["id"];


// Buscamos los datos del docente.

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
    AND roles.nombre = 'DOCENTE'
";

$sentencia = $conexion->prepare($sql);

$sentencia->execute([
    ":id" => $id
]);

$docente = $sentencia->fetch();


// Si el docente no existe, regresamos al listado.

if (!$docente) {

    header("Location: index.php");
    exit;
}


// Procesamos los cambios.

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $documento = trim($_POST["documento"] ?? "");
    $nombres = trim($_POST["nombres"] ?? "");
    $apellidos = trim($_POST["apellidos"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $telefono = trim($_POST["telefono"] ?? "");
    $estado = $_POST["estado"] ?? "ACTIVO";


    // Validamos los campos obligatorios.

    if (
        empty($documento) ||
        empty($nombres) ||
        empty($apellidos)
    ) {

        $error = "Debe completar los campos obligatorios.";

    } else {

        try {

            // Verificamos que el documento no pertenezca
            // a otro usuario.

            $sqlVerificar = "
                SELECT id
                FROM usuarios
                WHERE documento = :documento
                AND id != :id
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

                // Actualizamos los datos del docente.

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


                // Regresamos al listado.

                header("Location: index.php");

                exit;
            }

        } catch (PDOException $e) {

            $error =
                "Ocurrió un error al actualizar el docente.";
        }
    }

} else {

    // Cargamos los datos actuales en las variables.

    $documento = $docente["documento"];
    $nombres = $docente["nombres"];
    $apellidos = $docente["apellidos"];
    $email = $docente["email"];
    $telefono = $docente["telefono"];
    $estado = $docente["estado"];

}


$tituloPagina = "Editar docente";

require_once "../../includes/header.php";

?>


<div class="card shadow-sm">

    <div class="card-body">

        <h1 class="mb-4">
            Editar docente
        </h1>


        <?php if (!empty($error)): ?>

            <div class="alert alert-danger">

                <?= escapar($error) ?>

            </div>

        <?php endif; ?>


        <form method="POST">

            <div class="row">


                <!-- Documento -->

                <div class="col-md-6 mb-3">

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
                        value="<?= escapar($documento ?? '') ?>"
                        required
                    >

                </div>


                <!-- Nombres -->

                <div class="col-md-6 mb-3">

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
                        value="<?= escapar($nombres ?? '') ?>"
                        required
                    >

                </div>

            </div>


            <div class="row">


                <!-- Apellidos -->

                <div class="col-md-6 mb-3">

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
                        value="<?= escapar($apellidos ?? '') ?>"
                        required
                    >

                </div>


                <!-- Teléfono -->

                <div class="col-md-6 mb-3">

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
                        value="<?= escapar($telefono ?? '') ?>"
                    >

                </div>

            </div>


            <!-- Correo -->

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
                    value="<?= escapar($email ?? '') ?>"
                >

            </div>


            <!-- Estado -->

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
                        <?= ($estado ?? '') === "ACTIVO"
                            ? "selected"
                            : "" ?>
                    >
                        ACTIVO
                    </option>

                    <option
                        value="INACTIVO"
                        <?= ($estado ?? '') === "INACTIVO"
                            ? "selected"
                            : "" ?>
                    >
                        INACTIVO
                    </option>

                </select>

            </div>


            <!-- Botones -->

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