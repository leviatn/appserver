<?php
include("Conexion.php");

if (isset($_GET['id']) && isset($_GET['accion'])) {
    $id_solicitud = $_GET['id'];
    $accion = $_GET['accion'];

    // Actualizar el estado de la solicitud
    $estado = ($accion == 'aceptar') ? 'aceptada' : 'rechazada';
    $conexion->query("UPDATE solicitud_adopcion SET estado='$estado' WHERE id='$id_solicitud'");

    if ($accion == 'aceptar') {
        // Obtener el id_mascota de la solicitud
        $sqlMascota = "SELECT id_mascota FROM solicitud_adopcion WHERE id='$id_solicitud'";
        $resMascota = $conexion->query($sqlMascota);
        if ($resMascota && $row = $resMascota->fetch_assoc()) {
            $id_mascota = $row['id_mascota'];

            // Cambiar el estado del perrito a adoptado
            $conexion->query("UPDATE mascotas SET estado='adoptado' WHERE id_mascota='$id_mascota'");
        }
    }

    header("Location: Solicitudes.php");
    exit;
}
?>
