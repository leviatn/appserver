<?php
session_start();
include("Conexion.php");
$conexion = mysqli_connect("localhost", "root", "admin123", "adoptame", 3306);

if (!$conexion) {
    die("Error en la conexión: " . mysqli_connect_error());
}

$mensaje = '';

// Obtener el ID desde GET
$id = isset($_GET['id']) ? $_GET['id'] : '';

if($id != '') {
    $stmt = $conexion->prepare("SELECT nombre, tipo, edad, sexo, raza, color, caracteristicas, descripcion, foto FROM mascotas WHERE id_mascota = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $stmt->bind_result($nombre, $tipo, $edad, $sexo, $raza, $color, $caracteristicas, $descripcion, $foto);
    $stmt->fetch();
    $stmt->close();
}

if(isset($_POST["Editar"])) {
    $nombre = $_POST['nombre'];
    $tipo = $_POST['tipo'];
    $edad = $_POST['edad'];
    $sexo = $_POST['sexo'];
    $raza = $_POST['raza'];
    $color = $_POST['color'];
    $caracteristicas = $_POST['caracteristicas'];
    $descripcion = $_POST['descripcion'];

    // Manejo de foto
    if(isset($_FILES['foto']) && $_FILES['foto']['name'] != '') {
        $archivo = $_FILES['foto']['name'];
        $tmp = $_FILES['foto']['tmp_name'];
        $ruta = "Perros/" . $archivo;

        if(move_uploaded_file($tmp, $ruta)) {
            $foto = $ruta;
        } else {
            $mensaje = "❌ Error al subir la foto";
        }
    }

    $sql = "UPDATE mascotas SET nombre=?, tipo=?, edad=?, sexo=?, raza=?, color=?, caracteristicas=?, descripcion=?, foto=? WHERE id_mascota=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssisssssss", $nombre, $tipo, $edad, $sexo, $raza, $color, $caracteristicas, $descripcion, $foto, $id);

    if($stmt->execute()){
        $mensaje = "✅ Mascota actualizada correctamente";
    } else {
        $mensaje = "❌ Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Mascota</title>
<style>
    body { font-family: Arial, sans-serif; background-color: beige; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
    .container { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.2); width: 380px; text-align: center; position: relative; }
    h2 { margin-bottom: 20px; font-size: 22px; color: #333; }
    label { display: block; text-align: left; margin-bottom: 6px; font-weight: bold; }
    input[type="text"], input[type="number"], input[type="file"] { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 8px; }
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
    <h2>✏️ Editar Mascota</h2>

    <?php
    if(!empty($mensaje)){
        $clase = (strpos($mensaje, '❌') === 0) ? 'error' : 'exito';
        echo "<div class='mensaje-flotante $clase' id='mensaje'>$mensaje</div>";
    }
    ?>

    <form class="formulario" method="POST" enctype="multipart/form-data">
        <label>ID de la mascota:</label>
        <input type="text" name="id_mascota" value="<?php echo $id; ?>" readonly>

        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?php echo $nombre; ?>" required>

        <label>Tipo:</label>
        <input type="text" name="tipo" value="<?php echo $tipo; ?>" required>

        <label>Edad:</label>
        <input type="number" name="edad" value="<?php echo $edad; ?>" required>

        <label>Sexo:</label>
        <input type="text" name="sexo" value="<?php echo $sexo; ?>" required>

        <label>Raza:</label>
        <input type="text" name="raza" value="<?php echo $raza; ?>" required>

        <label>Color:</label>
        <input type="text" name="color" value="<?php echo $color; ?>" required>

        <label>Características:</label>
        <input type="text" name="caracteristicas" value="<?php echo $caracteristicas; ?>" required>

        <label>Descripción:</label>
        <input type="text" name="descripcion" value="<?php echo $descripcion; ?>" required>

        <label>Foto actual:</label>
        <?php if(!empty($foto)) echo "<img src='$foto' width='120' style='display:block;margin-bottom:10px;border-radius:8px;'>"; ?>

        <label>Actualizar Foto:</label>
        <input type="file" name="foto" accept="image/*">

        <div class="btn-container">
            <input type="submit" value="Editar" name="Editar" class="btn">
            <a href="aparte.php" class="btn">🔙 Regresar</a>
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