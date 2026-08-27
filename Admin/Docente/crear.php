<?php

require_once "../../config/conexion.php";
require_once "../../includes/sesion.php";
require_once "../../includes/funciones.php";

// Verificamos que solo el administrador pueda entrar.
exigirRol("ADMIN");

$error = "";

// Procesamos el formulario.
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Recibimos los datos.
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

            // Verificamos que el documento no exista.
            $sqlVerificar = "
                SELECT id
                FROM usuarios
                WHERE documento = :documento
            ";

            $sentenciaVerificar =
                $conexion->prepare($sqlVerificar);

            $sentenciaVerificar->execute([
                ":documento" => $documento
            ]);

            $usuarioExistente =
                $sentenciaVerificar->fetch();

            if ($usuarioExistente) {

                $error =
                    "Ya existe un usuario registrado con este número de documento.";

            } else {

                // Buscamos el ID del rol DOCENTE.
                $sqlRol = "
                    SELECT id
                    FROM roles
                    WHERE nombre = 'DOCENTE'
                    LIMIT 1
                ";

                $sentenciaRol =
                    $conexion->prepare($sqlRol);

                $sentenciaRol->execute();

                $rol = $sentenciaRol->fetch();

                if (!$rol) {

                    $error =
                        "No se encontró el rol DOCENTE en la base de datos.";

                } else {

                    /*
                     * La contraseña inicial será el número
                     * de documento, pero se guarda encriptada.
                     */
                    $password =
                        password_hash(
                            $documento,
                            PASSWORD_DEFAULT
                        );

                    // Insertamos el nuevo docente.
                    $sqlInsertar = "
                        INSERT INTO usuarios
                        (
                            documento,
                            nombres,
                            apellidos,
                            email,
                            telefono,
                            password,
                            rol_id,
                            primer_login,
                            estado,
                            requiere_cambio_password
                        )
                        VALUES
                        (
                            :documento,
                            :nombres,
                            :apellidos,
                            :email,
                            :telefono,
                            :password,
                            :rol_id,
                            1,
                            :estado,
                            1
                        )
                    ";

                    $sentenciaInsertar =
                        $conexion->prepare($sqlInsertar);

                    $sentenciaInsertar->execute([

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

                        ":password" => $password,

                        ":rol_id" => $rol["id"],

                        ":estado" => $estado

                    ]);

                    // Regresamos al listado.
                    header(
                        "Location: index.php"
                    );

                    exit;
                }
            }

        } catch (PDOException $e) {

            $error =
                "Ocurrió un error al registrar el docente.";
        }
    }
}


$tituloPagina = "Registrar docente";

require_once "../../includes/header.php";

?>

<div class="card shadow-sm">

    <div class="card-body">

        <h1 class="mb-4">
            Registrar nuevo docente
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


            <!-- Email -->

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
                        <?= ($estado ?? 'ACTIVO') === 'ACTIVO'
                            ? 'selected'
                            : '' ?>
                    >
                        ACTIVO
                    </option>

                    <option
                        value="INACTIVO"
                        <?= ($estado ?? '') === 'INACTIVO'
                            ? 'selected'
                            : '' ?>
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
                Guardar docente
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

<?php require_once "../../includes/footer.php"; ?>