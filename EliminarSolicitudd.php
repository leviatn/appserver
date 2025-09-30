<?php
session_start();
include("Conexion.php");

$mensaje = '';

if(isset($_POST["Eliminar"])) {
    $id = $_POST['id_solicitud'];

    // Verificar si la solicitud existe
    $check = $conexion->prepare("SELECT id_mascota, estado FROM solicitud_adopcion WHERE id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $check->store_result();
    $check->bind_result($id_mascota, $estado);
    $check->fetch();

    if($check->num_rows > 0){
        // Si la solicitud estaba aceptada, cambiamos estado de la mascota a disponible
        if($estado == 'Aceptada'){
            $conexion->query("UPDATE mascotas SET estado='Disponible' WHERE id_mascota='$id_mascota'");
        }

        // Eliminamos la solicitud
        $stmt = $conexion->prepare("DELETE FROM solicitud_adopcion WHERE id = ?");
        $stmt->bind_param("i", $id);

        if($stmt->execute()){
            $mensaje = "✅ Solicitud eliminada correctamente";
        } else {
            $mensaje = "❌ Error al eliminar: " . $stmt->error;
        }
    } else {
        $mensaje = "❌ No se encontró ninguna solicitud con ese ID";
    }

    $check->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Eliminar Solicitud</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: beige;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
    }
    .container {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        width: 380px;
        text-align: center;
        position: relative;
    }
    h2 { margin-bottom: 20px; font-size: 22px; color: #333; }
    label { display: block; text-align: left; margin-bottom: 6px; font-weight: bold; }
    input[type="text"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 8px;
    }
    .btn {
        display: inline-block;
        width: 45%;
        background-color: #d8b894;
        color: white;
        padding: 12px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        margin: 8px;
        transition: background 0.3s ease;
        text-decoration: none;
        text-align: center;
    }
    .btn:hover { background-color: #c5a67c; }
    .btn-container { display: flex; justify-content: space-between; }

    /* Mensaje flotante */
    .mensaje-flotante {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        padding: 20px 30px;
        border-radius: 12px;
        font-weight: bold;
        font-size: 18px;
        z-index: 1000;
        text-align: center;
        animation: fadein 0.5s, fadeout 0.5s 3s;
    }
    .exito { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

    @keyframes fadein { from {opacity:0;} to {opacity:1;} }
    @keyframes fadeout { from {opacity:1;} to {opacity:0;} }
</style>
</head>
<body>
<div class="container">
    <h2>🗑️ Eliminar Solicitud</h2>

    <?php
    if(!empty($mensaje)){
        $clase = (strpos($mensaje, '❌') === 0) ? 'error' : 'exito';
        echo "<div class='mensaje-flotante $clase' id='mensaje'>$mensaje</div>";
    }
    ?>

    <form method="POST">
        <label>ID de la solicitud a eliminar:</label>
        <input type="text" name="id_solicitud" placeholder="Ingrese el ID" required>

        <div class="btn-container">
            <input type="submit" value="Eliminar" name="Eliminar" class="btn">
            <a href="Solicitudes.php" class="btn">🔙 Regresar</a>
        </div>
    </form>
</div>

<script>
    // Ocultar el mensaje después de 3.5 segundos
    setTimeout(function(){
        const msg = document.getElementById('mensaje');
        if(msg) msg.style.display = 'none';
    }, 3500);
</script>
</body>
</html>
