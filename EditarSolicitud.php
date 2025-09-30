<?php
include("Conexion.php");

if (!isset($_GET['id'])) {
    header("Location: Solicitudes.php");
    exit;
}

$id = intval($_GET['id']);

// Obtener los datos de la solicitud
$sql = "SELECT * FROM solicitud_adopcion WHERE id = $id";
$res = $conexion->query($sql);
if ($res->num_rows == 0) {
    echo "Solicitud no encontrada";
    exit;
}
$solicitud = $res->fetch_assoc();

$exito = false; // bandera para saber si mostrar modal

// Actualizar la solicitud al enviar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_adoptante = $_POST['id_adoptante'];
    $id_mascota = $_POST['id_mascota'];
    $fecha = $_POST['fecha'];
    $mensaje = $_POST['mensaje'];
    $estado = $_POST['estado'];

    $stmt = $conexion->prepare("UPDATE solicitud_adopcion SET id_adoptante=?, id_mascota=?, fecha=?, mensaje=?, estado=? WHERE id=?");
    $stmt->bind_param("issssi", $id_adoptante, $id_mascota, $fecha, $mensaje, $estado, $id);

    if ($stmt->execute()) {
        $exito = true; // activa el modal de éxito
    } else {
        $error = "Error al actualizar la solicitud";
    }
}

// Obtener lista de adoptantes y mascotas para los select
$adoptantes = $conexion->query("SELECT id_adoptante, CONCAT(nombres,' ',apellidos) AS nombre_completo FROM adoptante");
$mascotas = $conexion->query("SELECT id_mascota, nombre FROM mascotas");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Solicitud</title>
<style>
    body { font-family: Arial; background:#fff9f5; display:flex; justify-content:center; align-items:center; height:100vh; }
    .card { background:#fff; padding:30px; border-radius:20px; box-shadow:0 10px 25px rgba(0,0,0,0.1); width:100%; max-width:500px; }
    h2 { color:#6d3d14; text-align:center; }
    label { font-weight:bold; margin-top:10px; display:block; color:#9c5a2a; }
    input, select, textarea { width:100%; padding:10px; margin-top:5px; border-radius:8px; border:1px solid #ccc; }
    button { margin-top:15px; padding:12px; background:#ff914d; color:white; border:none; border-radius:10px; font-weight:bold; cursor:pointer; width:100%; }
    button:hover { background:#ff6f1f; }

    /* --- Modal --- */
    .modal {
        display: none; 
        position: fixed; 
        z-index: 1000; 
        left: 0; 
        top: 0; 
        width: 100%; 
        height: 100%; 
        background: rgba(0,0,0,0.5); 
        justify-content: center; 
        align-items: center;
    }
    .modal-content {
        background: #fff;
        padding: 30px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        width: 300px;
    }
    .modal-content h3 {
        color: #28a745;
        margin-bottom: 15px;
    }
    .modal-content button {
        padding: 10px 20px;
        background: #28a745;
        border: none;
        color: #fff;
        border-radius: 8px;
        cursor: pointer;
    }
    .modal-content button:hover {
        background: #218838;
    }
</style>
</head>
<body>
<div class="card">
<h2>Editar Solicitud</h2>

<?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

<form method="POST">
    <label>Adoptante</label>
    <select name="id_adoptante" required>
        <?php while($fila = $adoptantes->fetch_assoc()): ?>
            <option value="<?= $fila['id_adoptante'] ?>" <?= $fila['id_adoptante'] == $solicitud['id_adoptante'] ? 'selected' : '' ?>>
                <?= $fila['nombre_completo'] ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label>Mascota</label>
    <select name="id_mascota" required>
        <?php while($fila = $mascotas->fetch_assoc()): ?>
            <option value="<?= $fila['id_mascota'] ?>" <?= $fila['id_mascota'] == $solicitud['id_mascota'] ? 'selected' : '' ?>>
                <?= $fila['nombre'] ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label>Fecha</label>
    <input type="date" name="fecha" value="<?= $solicitud['fecha'] ?>" required>

    <label>Mensaje</label>
    <textarea name="mensaje"><?= $solicitud['mensaje'] ?></textarea>

    <label>Estado</label>
    <select name="estado" required>
        <option value="Pendiente" <?= $solicitud['estado']=='Pendiente' ? 'selected' : '' ?>>Pendiente</option>
        <option value="Aceptada" <?= $solicitud['estado']=='Aceptada' ? 'selected' : '' ?>>Aceptada</option>
        <option value="Rechazada" <?= $solicitud['estado']=='Rechazada' ? 'selected' : '' ?>>Rechazada</option>
    </select>

    <button type="submit">Guardar Cambios</button>
</form>
</div>

<!-- Modal de éxito -->
<div id="modalExito" class="modal">
    <div class="modal-content">
        <h3>¡Solicitud actualizada!</h3>
        <p>Los cambios se guardaron correctamente.</p>
        <button onclick="window.location.href='Solicitudes.php'">OK</button>
    </div>
</div>

<?php if($exito): ?>
<script>
    document.getElementById("modalExito").style.display = "flex";
</script>
<?php endif; ?>

</body>
</html>

