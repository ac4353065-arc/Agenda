<?php

require_once "config/conexion.php";
require_once "includes/sesion.php";
require_once "includes/funciones.php";

// Verificamos que exista una sesión iniciada.
exigirLogin();

$error = "";
$exito = "";

// Solo ADMIN y DOCENTE necesitan utilizar
// el cambio obligatorio de contraseña.
if (
    $_SESSION["rol"] !== "ADMIN" &&
    $_SESSION["rol"] !== "DOCENTE"
) {

    header("Location: /Agenda/login.php");
    exit;
}


// Procesamos el formulario.
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $passwordActual = $_POST["password_actual"] ?? "";
    $passwordNueva = $_POST["password_nueva"] ?? "";
    $confirmarPassword = $_POST["confirmar_password"] ?? "";


    // Validamos que todos los campos estén completos.
    if (
        empty($passwordActual) ||
        empty($passwordNueva) ||
        empty($confirmarPassword)
    ) {

        $error = "Debe completar todos los campos.";

    // Verificamos que las nuevas contraseñas coincidan.
    } elseif ($passwordNueva !== $confirmarPassword) {

        $error = "La nueva contraseña y la confirmación no coinciden.";

    // Validamos una longitud mínima sencilla.
    } elseif (strlen($passwordNueva) < 4) {

        $error = "La nueva contraseña debe tener mínimo 4 caracteres.";

    } else {

        try {

            // Consultamos nuevamente el usuario en la base de datos.
            $sql = "SELECT id, password
                    FROM usuarios
                    WHERE id = :usuario_id
                    LIMIT 1";

            $sentencia = $conexion->prepare($sql);

            $sentencia->execute([
                ":usuario_id" => $_SESSION["usuario_id"]
            ]);

            $usuario = $sentencia->fetch();


            if (!$usuario) {

                $error = "No fue posible encontrar el usuario.";

            // Verificamos la contraseña actual.
            } elseif (
                !password_verify(
                    $passwordActual,
                    $usuario["password"]
                )
            ) {

                $error = "La contraseña actual es incorrecta.";

            } else {

                // Generamos el hash de la nueva contraseña.
                $nuevoPasswordHash = password_hash(
                    $passwordNueva,
                    PASSWORD_DEFAULT
                );


                // Actualizamos la contraseña y quitamos
                // la obligación de cambiarla nuevamente.
                $sqlActualizar = "UPDATE usuarios
                                  SET password = :password,
                                      primer_login = 0,
                                      requiere_cambio_password = 0
                                  WHERE id = :usuario_id";

                $actualizar = $conexion->prepare($sqlActualizar);

                $actualizar->execute([
                    ":password" => $nuevoPasswordHash,
                    ":usuario_id" => $_SESSION["usuario_id"]
                ]);


                // Actualizamos la información de la sesión.
                $_SESSION["requiere_cambio_password"] = 0;


                // Redirigimos según el rol.
                if ($_SESSION["rol"] === "ADMIN") {

                    header(
                        "Location: /Agenda/admin/index.php"
                    );

                    exit;

                } elseif ($_SESSION["rol"] === "DOCENTE") {

                    header(
                        "Location: /Agenda/docente/index.php"
                    );

                    exit;
                }
            }

        } catch (PDOException $e) {

            $error = "Ocurrió un error al cambiar la contraseña.";
        }
    }
}


$tituloPagina = "Cambiar contraseña";

require_once "includes/header.php";

?>

<div class="row justify-content-center">

    <div class="col-md-7 col-lg-6">

        <div class="card shadow-sm mt-4">

            <div class="card-body p-4">

                <h2 class="text-center mb-3">
                    Cambio de contraseña
                </h2>

                <p class="text-center text-muted">

                    Por seguridad, debes cambiar
                    tu contraseña antes de continuar.

                </p>


                <?php if (!empty($error)): ?>

                    <div class="alert alert-danger">

                        <?= escapar($error) ?>

                    </div>

                <?php endif; ?>


                <form method="POST">

                    <div class="mb-3">

                        <label
                            for="password_actual"
                            class="form-label"
                        >
                            Contraseña actual
                        </label>

                        <input
                            type="password"
                            class="form-control"
                            id="password_actual"
                            name="password_actual"
                            required
                        >

                    </div>


                    <div class="mb-3">

                        <label
                            for="password_nueva"
                            class="form-label"
                        >
                            Nueva contraseña
                        </label>

                        <input
                            type="password"
                            class="form-control"
                            id="password_nueva"
                            name="password_nueva"
                            required
                        >

                    </div>


                    <div class="mb-4">

                        <label
                            for="confirmar_password"
                            class="form-label"
                        >
                            Confirmar nueva contraseña
                        </label>

                        <input
                            type="password"
                            class="form-control"
                            id="confirmar_password"
                            name="confirmar_password"
                            required
                        >

                    </div>


                    <div class="d-grid">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Guardar nueva contraseña
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

<?php require_once "includes/footer.php"; ?>