<?php
include("Conexion.php");
session_start();

// Mostrar modal de bienvenida solo si se acaba de iniciar sesión
$mostrarBienvenida = false;
if (isset($_SESSION["id"]) && !isset($_SESSION["bienvenida_mostrada"])) {
    $mostrarBienvenida = true;
    $_SESSION["bienvenida_mostrada"] = true;
}

// Consulta de todos los gatos
$sql = "SELECT * FROM mascotas WHERE tipo='Gato' AND estado='disponible'";

$resultado = mysqli_query($conexion, $sql);
$cantidad_gatos = mysqli_num_rows($resultado);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Gatos en adopción</title>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
body { font-family: Arial, sans-serif; background-color: #fff9f5; margin: 0; padding: 0; text-align: center; }
.header { display: flex; justify-content: space-between; align-items: center; padding: 20px 30px; }
.btn-volver { background:#ff914d; color:white; border:none; padding:10px 20px; border-radius:10px; cursor:pointer; font-weight:bold; transition:0.3s; }
.btn-volver:hover { background:#ff6f1f; }
.contador { background: #ff914d; color: white; padding: 8px 12px; border-radius: 10px; font-weight: bold; display: flex; align-items: center; gap: 5px; }
.contenedor { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; padding: 20px; }
.card { background: #fff; border-radius: 15px; box-shadow: 0px 4px 10px rgba(0,0,0,0.1); overflow: hidden; padding: 10px; }
.card img { width: 100%; height: 200px; object-fit: cover; border-radius: 10px; }
.nombre { font-size: 20px; font-weight: bold; margin: 10px 0; color: #6d3d14; }
.btn { padding: 8px 15px; border: none; background: #ff914d; color: white; border-radius: 8px; cursor: pointer; transition: 0.3s; margin-top: 8px; }
.btn:hover { background: #ff6f1f; }
.modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); overflow-y: auto; }
.modal-content { background: white; margin: 50px auto; padding: 20px; border-radius: 15px; width: 90%; max-width: 500px; text-align: center; overflow-y: auto; max-height: 80vh; }
.close { color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
.close:hover { color: black; }
</style>
</head>
<body>

<div class="header">
  <button class="btn-volver" onclick="window.location.href='index.php'">← Volver</button>
  <div class="contador"> Amigos gatunos 🐾🐱: <?= $cantidad_gatos ?></div>
</div>

<h1>🐾 Nuestros gatitos esperan un hogar 🐱</h1>

<div class="contenedor">
<?php if ($cantidad_gatos > 0): ?>
  <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
    <div class="card">
      <img src="<?= $fila['foto'] ?? 'default.jpg' ?>" alt="Foto de <?= $fila['nombre'] ?>">
      <p class="nombre"><?= $fila['nombre'] ?></p>
      <button class="btn" onclick="abrirModal('modal<?= $fila['id_mascota'] ?>')">Detalles</button>
    </div>

    <div id="modal<?= $fila['id_mascota'] ?>" class="modal">
      <div class="modal-content">
        <span class="close" onclick="cerrarModal('modal<?= $fila['id_mascota'] ?>')">&times;</span>
        <h2><?= $fila['nombre'] ?></h2>
        <img src="<?= $fila['foto'] ?? 'default.jpg' ?>" alt="Foto de <?= $fila['nombre'] ?>" style="width:100%; max-height:250px; object-fit:cover; border-radius:10px; margin-bottom:10px;">
        <p><b>Edad:</b> <?= $fila['edad'] ?? 'Desconocida' ?> años</p>
        <p><b>Sexo:</b> <?= $fila['sexo'] ?? 'Desconocido' ?></p>
        <p><b>Raza:</b> <?= $fila['raza'] ?? 'Desconocida' ?></p>
        <p><b>Características:</b> <?= $fila['caracteristicas'] ?? 'Sin información' ?></p>
        <p><b>Descripción:</b> <?= $fila['descripcion'] ?? 'Sin información' ?></p>
        <button class="btn btn-adoptar" data-id="<?= $fila['id_mascota'] ?>">Adoptar</button>
      </div>
    </div>
  <?php endwhile; ?>
<?php else: ?>
  <p>No hay gatitos disponibles por ahora 🐈💔</p>
<?php endif; ?>
</div>

<?php if ($mostrarBienvenida): ?>
<div id="bienvenidaModal" style="position:fixed;top:0;left:0;width:100%;height:100%;
     background:rgba(0,0,0,0.5);display:flex;align-items:center;justify-content:center;z-index:999;">
  <div style="background:#fff0f6;padding:25px 35px;border-radius:16px;text-align:center;
              box-shadow:0 6px 18px rgba(0,0,0,0.25);max-width:400px;">
    <h2 style="color:#d63384;margin-bottom:10px;">¡Bienvenido amiguito! 🐱🐾</h2>
    <p style="color:#555;margin-bottom:20px;">Nuestros gatitos esperan un hogar lleno de amor 💕</p>
    <button onclick="cerrarModalBienvenida()" style="padding:8px 16px;border:none;border-radius:8px; background:#a3d8f4;color:#000;cursor:pointer;">¡Vamos!</button>
  </div>
</div>
<?php endif; ?>

<script>
function abrirModal(id) { document.getElementById(id).style.display = "block"; }
function cerrarModal(id) { document.getElementById(id).style.display = "none"; }
function cerrarModalBienvenida() { document.getElementById("bienvenidaModal").style.display = "none"; }

document.addEventListener("DOMContentLoaded", function() {
  document.querySelectorAll(".btn-adoptar").forEach(function(btn) {
    btn.addEventListener("click", function() {
      let idMascota = this.getAttribute("data-id");
      Swal.fire({
        title: 'Debes registrarte como adoptante',
        text: 'Para poder adoptar este gatito, primero debes registrarte.',
        icon: 'info',
        confirmButtonText: 'Registrarme'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = 'registro_adoptante.php?id_mascota=' + idMascota;
        }
      });
    });
  });
});
</script>

</body>
</html>

