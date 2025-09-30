<?php
include("Conexion.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_adoptante = $_POST["id_adoptante"];
    $id_mascota = $_POST["id_mascota"];
    $fecha = $_POST["fecha"];
    $mensaje = $_POST["mensaje"] ?? "";

    // Verificar si ya existe solicitud para este adoptante y mascota
    $check = $conexion->prepare("SELECT COUNT(*) FROM solicitud_adopcion WHERE id_adoptante=? AND id_mascota=?");
    $check->bind_param("is", $id_adoptante, $id_mascota);
    $check->execute();
    $check->bind_result($count);
    $check->fetch();
    $check->close();

    if ($count > 0) {
        echo "exists"; // ya envió solicitud
        exit;
    }

    // Insertar solicitud
    $stmt = $conexion->prepare("INSERT INTO solicitud_adopcion (id_adoptante,id_mascota,fecha,mensaje,estado) VALUES (?,?,?,?, 'Pendiente')");
    $stmt->bind_param("isss",$id_adoptante,$id_mascota,$fecha,$mensaje);

    if ($stmt->execute()) echo "success";
    else echo "error: " . $stmt->error;
    exit;
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Solicitud de Adopción</title>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
/* Tu CSS permanece igual */
body { font-family: Arial, sans-serif; background-color: #fff9f5; display:flex; justify-content:center; align-items:center; height:100vh; }
.card { background:#fff; padding:30px; border-radius:20px; box-shadow:0 10px 25px rgba(0,0,0,0.1); width:100%; max-width:500px; }
h2 { color:#6d3d14; text-align:center; }
.field { margin-bottom:15px; display:flex; flex-direction:column; }
.field label { margin-bottom:5px; color:#9c5a2a; font-weight:bold; }
.field select, .field textarea, .field input { padding:10px; border:1px solid #ccc; border-radius:8px; }
button { padding:12px; background:#ff914d; color:white; border:none; border-radius:10px; font-weight:bold; cursor:pointer; width:100%; transition:0.3s; }
button:hover { background:#ff6f1f; }
</style>
</head>
<body>
<div class="card">
<h2>Solicitud de Adopción</h2>
<form id="solicitudForm" method="POST">
    <div class="field">
        <label>¿Quién eres?</label>
        <select name="id_adoptante" required>
            <?php
            $sql = "SELECT id_adoptante, CONCAT(nombres,' ',apellidos) AS nombre_completo FROM adoptante";
            $res = mysqli_query($conexion, $sql);
            while ($fila = mysqli_fetch_assoc($res)) {
                echo "<option value='{$fila['id_adoptante']}'>{$fila['nombre_completo']}</option>";
            }
            ?>
        </select>
    </div>

    <div class="field">
        <label>¿Cuál amiguito deseas adoptar?</label>
        <select name="id_mascota" required>
            <?php
            $sql = "SELECT id_mascota, nombre, tipo FROM mascotas WHERE estado='disponible'";
            $res = mysqli_query($conexion, $sql);
            while ($fila = mysqli_fetch_assoc($res)) {
                echo "<option value='{$fila['id_mascota']}' data-tipo='{$fila['tipo']}'>{$fila['nombre']}</option>";
            }
            ?>
        </select>
        <input type="hidden" name="tipo_mascota" id="tipo_mascota">
    </div>

    <div class="field">
        <label>Fecha de adopción deseada</label>
        <input type="date" name="fecha" required min="<?= date('Y-m-d') ?>">
    </div>

    <div class="field">
        <label>Mensaje (opcional)</label>
        <textarea name="mensaje" rows="4" placeholder="Escribe un mensaje para el refugio..."></textarea>
    </div>

    <button type="submit">Enviar Solicitud</button>
</form>
</div>

<script>
const selectMascota = document.querySelector('select[name="id_mascota"]');
const tipoMascotaInput = document.getElementById('tipo_mascota');

selectMascota.addEventListener('change', function() {
    const tipo = this.selectedOptions[0].dataset.tipo;
    tipoMascotaInput.value = tipo;
});

document.getElementById('solicitudForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('solicitud_adopcion.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        const tipo = formData.get('tipo_mascota');
        let url = '';
        if(tipo === 'Perro') url = 'perros.php';
        else if(tipo === 'Gato') url = 'gatos.php';
        else if(tipo === 'Ave') url = 'aves.php';
        else if(tipo === 'Hamster') url = 'hamster.php';

        if(data.trim() === "success"){
            Swal.fire({
                icon: 'success',
                title: 'Solicitud enviada!',
                text: 'Tu solicitud de adopción ha sido registrada. Te contactaremos pronto ❤️',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                if(url) window.location.href = url;
            });
        } else if(data.trim() === "exists") {
            Swal.fire({
                icon: 'info',
                title: 'Solicitud ya enviada',
                text: 'Ya habías enviado previamente una solicitud para esta mascota 🐾',
                confirmButtonText: 'Aceptar'
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data,
                confirmButtonText: 'Aceptar'
            });
        }
    })
    .catch(() => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo enviar la solicitud'
        });
    });
});
</script>
</body>
</html>






