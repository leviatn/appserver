<?php
include("Conexion.php");

// Consultamos las mascotas adoptadas
$sql = "SELECT * FROM mascotas WHERE estado='adoptado'";

$res = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mascotas Adoptadas - Huellitas</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #fffaf5;
      margin: 0;
      padding: 20px;
    }
    h1 {
      text-align: center;
      color: #d65784;
      margin-bottom: 20px;
    }
    .contenedor {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 20px;
    }
    .btn:hover { opacity: 0.8; }
    .btn-editar { background-color: #f28cb3; }
    
    .card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      overflow: hidden;
      text-align: center;
      padding: 15px;
    }
    .card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 12px;
    }
    .card h3 {
      color: #d65784;
      margin: 10px 0 5px;
    }
    .card p {
      margin: 5px 0;
      font-size: 14px;
      color: #555;
    }
    .btn {
      display: inline-block;
      margin-top: 10px;
      padding: 6px 12px;
      background-color: #999;
      border: none;
      border-radius: 6px;
      color: white;
      font-weight: bold;
      text-decoration: none;
    }
    .btn:hover {
      background-color: #777;
    }
  </style>
</head>
<body>
  <div class="menu">
    <a class="btn btn-editar" href="aparte.php">⬅ Volver</a>
  </div>

  <h1>🐾 Mascotas Adoptadas</h1>

  <div class="contenedor">
    <?php
    if($res && $res->num_rows > 0){
        while ($row = $res->fetch_assoc()) {
            echo "<div class='card'>";
            echo "<img src='{$row['foto']}' alt='Foto de {$row['nombre']}'>";
            echo "<h3>{$row['nombre']}</h3>";
            echo "<p><b>Edad:</b> {$row['edad']} años</p>";
            echo "<p><b>Sexo:</b> {$row['sexo']}</p>";
            echo "<p><b>Raza:</b> {$row['raza']}</p>";
            echo "<p><b>Carascterísticas:</b> {$row['caracteristicas']}</p>";
            echo "<p><b>Descripción:</b> {$row['descripcion']}</p>";
            echo "<span class='btn'>Adoptado ❤️</span>";
            echo "</div>";
        }
    } else {
        echo "<p style='text-align:center; color:#555;'>No hay mascotas adoptadas aún 🐶🐱</p>";
    }
    ?>
  </div>

</body>
</html>
