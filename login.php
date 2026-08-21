<?php

require_once "config/conexion.php";
require_once "includes/sesion.php";
require_once "includes/funciones.php";

// Si el usuario ya inició sesión, lo enviamos
// a la página correspondiente según su rol.
if (isset($_SESSION['usuario_id'])) {

    switch ($_SESSION['rol']) {

        case "ADMIN":
            header("Location: /Agenda/admin/index.php");
            exit;

        case "DOCENTE":
            header("Location: /Agenda/docente/index.php");
            exit;

        case "ESTUDIANTE":
            header("Location: /Agenda/estudiante/index.php");
            exit;
    }
}


$error = "";

// Procesamos el formulario.
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $documento = trim($_POST["documento"] ?? "");
    $password = $_POST["password"] ?? "";

    // Validamos que los campos no estén vacíos.
    if (empty($documento) || empty($password)) {

        $error = "Debe ingresar el documento y la contraseña.";

    } else {

        try {

            // Buscamos el usuario utilizando el documento.
            $sql = "SELECT
                        usuarios.id,
                        usuarios.documento,
                        usuarios.nombres,
                        usuarios.apellidos,
                        usuarios.password,
                        usuarios.primer_login,
                        usuarios.estado,
                        usuarios.requiere_cambio_password,
                        roles.nombre AS rol
                    FROM usuarios
                    INNER JOIN roles
                        ON usuarios.rol_id = roles.id
                    WHERE usuarios.documento = :documento
                    LIMIT 1";

            $sentencia = $conexion->prepare($sql);

            $sentencia->execute([
                ":documento" => $documento
            ]);

            $usuario = $sentencia->fetch();

            // Verificamos que el usuario exista.
            if (!$usuario) {

                $error = "Documento o contraseña incorrectos.";

            // Verificamos que el usuario esté activo.
            } elseif ($usuario["estado"] !== "ACTIVO") {

                $error = "Este usuario se encuentra inactivo.";

            // Verificamos la contraseña usando el hash.
            } elseif (!password_verify($password, $usuario["password"])) {

                $error = "Documento o contraseña incorrectos.";

            } else {

                // Creamos las variables de sesión.
                $_SESSION["usuario_id"] = $usuario["id"];

                $_SESSION["documento"] = $usuario["documento"];

                $_SESSION["nombres"] = $usuario["nombres"];

                $_SESSION["apellidos"] = $usuario["apellidos"];

                $_SESSION["rol"] = $usuario["rol"];

                $_SESSION["requiere_cambio_password"] =
                    $usuario["requiere_cambio_password"];


                /*
                 * Por ahora dejamos preparado el punto donde
                 * posteriormente registraremos el acceso en
                 * la tabla log_accesos.
                 *
                 * Primero vamos a comprobar que el login y
                 * las sesiones funcionen correctamente.
                 */


                // Si debe cambiar la contraseña,
                // lo enviamos a esa página.
                if (
                    $usuario["requiere_cambio_password"] == 1
                ) {

                    header(
                        "Location: /Agenda/cambiar_password.php"
                    );

                    exit;
                }


                // Redirigimos según el rol.
                switch ($usuario["rol"]) {

                    case "ADMIN":

                        header(
                            "Location: /Agenda/admin/index.php"
                        );

                        exit;

                    case "DOCENTE":

                        header(
                            "Location: /Agenda/docente/index.php"
                        );

                        exit;

                    case "ESTUDIANTE":

                        header(
                            "Location: /Agenda/estudiante/index.php"
                        );

                        exit;

                    default:

                        $error = "El usuario tiene un rol no válido.";

                        cerrarSesion();
                }
            }

        } catch (PDOException $e) {

            $error = "Ocurrió un error al intentar iniciar sesión.";
        }
    }
}


$tituloPagina = "Iniciar sesión";

require_once "includes/header.php";

?>

<div class="row justify-content-center">

    <div class="col-md-6 col-lg-5">

        <div class="card shadow-sm mt-4">

            <div class="card-body p-4">

                <h2 class="text-center mb-4">
                    Iniciar sesión
                </h2>


                <?php if (!empty($error)): ?>

                    <div class="alert alert-danger">

                        <?= escapar($error) ?>

                    </div>

                <?php endif; ?>


                <form method="POST">

                    <div class="mb-3">

                        <label
                            for="documento"
                            class="form-label"
                        >
                            Número de documento
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="documento"
                            name="documento"
                            value="<?= escapar($documento ?? '') ?>"
                            required
                            autofocus
                        >

                    </div>


                    <div class="mb-4">

                        <label
                            for="password"
                            class="form-label"
                        >
                            Contraseña
                        </label>

                        <input
                            type="password"
                            class="form-control"
                            id="password"
                            name="password"
                            required
                        >

                    </div>


                    <div class="d-grid">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Iniciar sesión
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

<?php require_once "includes/footer.php"; ?>