<?php
session_start();
include("Conexion.php");

$mensaje = '';
if(isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener datos del adoptante
    $stmt = $conexion->prepare("SELECT * FROM adoptante WHERE id_adoptante = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $adoptante = $resultado->fetch_assoc();
}

if(isset($_POST["Actualizar"])) {
    $id = $_POST['id_adoptante'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $dui = $_POST['dui'];
    $direccion = $_POST['direccion'];

    $stmt = $conexion->prepare("UPDATE adoptante SET nombres=?, apellidos=?, telefono=?, correo=?, DUI=?, direccion=? WHERE id_adoptante=?");
    $stmt->bind_param("ssssssi", $nombres, $apellidos, $telefono, $correo, $dui, $direccion, $id);

    if($stmt->execute()){
        $mensaje = "✅ Adoptante actualizado correctamente";
    } else {
        $mensaje = "❌ Error al actualizar: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Adoptante</title>
<style>
    body { font-family: Arial, sans-serif; background-color: beige; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
    .container { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.2); width: 400px; text-align: center; position: relative; }
    h2 { margin-bottom: 20px; font-size: 22px; color: #333; }
    label { display: block; text-align: left; margin-bottom: 6px; font-weight: bold; }
    input[type="text"], input[type="email"] { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 8px; }
    .btn { display: inline-block; width: 45%; background-color: #d8b894; color: white; padding: 12px; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; margin: 8px; transition: background 0.3s ease; text-decoration: none; text-align: center; }
    .btn:hover { background-color: #c5a67c; }
    .btn-container { display: flex; justify-content: space-between; }
    .mensaje-flotante { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); padding: 20px 30px; border-radius: 12px; font-weight: bold; font-size: 18px; z-index: 1000; text-align: center; animation: fadein 0.5s, fadeout 0.5s 3s; }
    .exito { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    @keyframes fadein { from {opacity:0;} to {opacity:1;} }
    @keyframes fadeout { from {opacity:1;} to {opacity:0;} }
</style>
</head>
<body>
<div class="container">
    <h2>✏️ Editar Adoptante</h2>

    <?php
    if(!empty($mensaje)){
        $clase = (strpos($mensaje, '❌') === 0) ? 'error' : 'exito';
        echo "<div class='mensaje-flotante $clase' id='mensaje'>$mensaje</div>";
    }
    ?>

    <form method="POST">
        <input type="hidden" name="id_adoptante" value="<?= $adoptante['id_adoptante'] ?? '' ?>">
        <label>Nombres:</label>
        <input type="text" name="nombres" value="<?= $adoptante['nombres'] ?? '' ?>" required>
        <label>Apellidos:</label>
        <input type="text" name="apellidos" value="<?= $adoptante['apellidos'] ?? '' ?>" required>
        <label>Teléfono:</label>
        <input type="text" name="telefono" value="<?= $adoptante['telefono'] ?? '' ?>" required>
        <label>Correo:</label>
        <input type="email" name="correo" value="<?= $adoptante['correo'] ?? '' ?>" required>
        <label>DUI:</label>
        <input type="text" name="dui" value="<?= $adoptante['DUI'] ?? '' ?>" required>
        <label>Dirección:</label>
        <input type="text" name="direccion" value="<?= $adoptante['direccion'] ?? '' ?>" required>

        <div class="btn-container">
            <input type="submit" value="Actualizar" name="Actualizar" class="btn">
            <a href="Adoptantes.php" class="btn">🔙 Regresar</a>
        </div>
    </form>
</div>

<script>
    setTimeout(function(){
        const msg = document.getElementById('mensaje');
        if(msg) msg.style.display = 'none';
    }, 3500);
</script>
</body>
</html>
