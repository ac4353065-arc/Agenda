<?php

require_once "config/conexion.php";
require_once "includes/sesion.php";
require_once "includes/funciones.php";

// Verificamos que el usuario haya iniciado sesión.
exigirLogin();

// Solo ADMIN y DOCENTE pueden cambiar la contraseña obligatoria.
if (
    $_SESSION["rol"] !== "ADMIN" &&
    $_SESSION["rol"] !== "DOCENTE"
) {
    header("Location: /Agenda/login.php");
    exit;
}

$error = "";

// Procesamos el formulario.
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $passwordActual = $_POST["password_actual"] ?? "";
    $passwordNueva = $_POST["password_nueva"] ?? "";
    $confirmarPassword = $_POST["confirmar_password"] ?? "";

    // Verificamos que todos los campos estén completos.
    if (
        empty($passwordActual) ||
        empty($passwordNueva) ||
        empty($confirmarPassword)
    ) {

        $error = "Debe completar todos los campos.";

    } elseif ($passwordNueva !== $confirmarPassword) {

        $error = "La nueva contraseña y la confirmación no coinciden.";

    } elseif (strlen($passwordNueva) < 4) {

        $error = "La nueva contraseña debe tener mínimo 4 caracteres.";

    } else {

        try {

            // Consultamos el usuario actual.
            $sql = "SELECT id, password
                    FROM usuarios
                    WHERE id = :usuario_id";

            $sentencia = $conexion->prepare($sql);

            $sentencia->execute([
                ":usuario_id" => $_SESSION["usuario_id"]
            ]);

            $usuario = $sentencia->fetch();

            if (!$usuario) {

                $error = "No fue posible encontrar el usuario.";

            } elseif (
                !password_verify(
                    $passwordActual,
                    $usuario["password"]
                )
            ) {

                $error = "La contraseña actual es incorrecta.";

            } else {

                // Generamos el hash de la nueva contraseña.
                $nuevoPassword = password_hash(
                    $passwordNueva,
                    PASSWORD_DEFAULT
                );

                // Actualizamos la contraseña.
                $sqlActualizar = "UPDATE usuarios
                                  SET password = :password,
                                      primer_login = 0,
                                      requiere_cambio_password = 0
                                  WHERE id = :usuario_id";

                $actualizar = $conexion->prepare($sqlActualizar);

                $actualizar->execute([
                    ":password" => $nuevoPassword,
                    ":usuario_id" => $_SESSION["usuario_id"]
                ]);

                // Actualizamos la sesión.
                $_SESSION["requiere_cambio_password"] = 0;

                // Redirigimos según el rol.
                if ($_SESSION["rol"] === "ADMIN") {

                    header("Location: /Agenda/admin/index.php");
                    exit;

                } elseif ($_SESSION["rol"] === "DOCENTE") {

                    header("Location: /Agenda/docente/index.php");
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
                    Por seguridad, debes cambiar tu contraseña antes de continuar.
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