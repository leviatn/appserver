<?php
include("Conexion.php");

if(isset($_GET['id'])){
    $id = $_GET['id'];

    $stmt = $conexion->prepare("DELETE FROM solicitud_adopcion WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: Solicitudes.php"); // o el archivo donde muestras la tabla
    exit;
}
?>
