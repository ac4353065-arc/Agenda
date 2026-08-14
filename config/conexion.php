<?php

// Datos de conexión a la base de datos
$host = "localhost";
$nombre_bd = "agenda_escolar";
$usuario_bd = "root";
$password_bd = "";

// Intentamos realizar la conexión
try {

    $conexion = new PDO(
        "mysql:host=$host;dbname=$nombre_bd;charset=utf8mb4",
        $usuario_bd,
        $password_bd
    );

    // Configuramos PDO para que muestre los errores
    // mediante excepciones.
    $conexion->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    // Configuramos el modo de obtención de datos
    $conexion->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );

} catch (PDOException $e) {

    // Si existe un error de conexión, mostramos un mensaje sencillo.
    die("Error de conexión con la base de datos: " . $e->getMessage());
}
?>