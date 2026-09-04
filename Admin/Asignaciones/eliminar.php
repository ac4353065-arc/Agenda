<?php

require_once "../../config/conexion.php";
require_once "../../includes/sesion.php";
require_once "../../includes/funciones.php";

// Solo el administrador puede acceder.
exigirRol("ADMIN");

// Verificamos que llegue un ID válido.
if (!isset($_GET["id"]) || empty($_GET["id"])) {

    header("Location: index.php");
    exit;
}

$id = $_GET["id"];

try {

    // Eliminamos la asignación seleccionada.
    $sql = "
        DELETE FROM docente_asignacion
        WHERE id = :id
    ";

    $sentencia = $conexion->prepare($sql);

    $sentencia->execute([
        ":id" => $id
    ]);

    // Regresamos al listado.
    header("Location: index.php");
    exit;

} catch (PDOException $e) {

    echo "No se pudo eliminar la asignación.";
}