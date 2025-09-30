<?php
include("Conexion.php");
session_start();

// si no hay sesión de admin, regresar
if (!isset($_SESSION["admin"])) {
    header("Location: administrador.php");
    exit;
}

// --- FILTRO DE BUSQUEDA ---
$filtro = "";
if (isset($_GET['buscar']) && !empty($_GET['buscar'])) {
    $buscar = $conexion->real_escape_string($_GET['buscar']);
    $filtro = "WHERE nombre LIKE '%$buscar%'";
}

$sql = "SELECT * FROM mascotas $filtro";
$res = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel Administrador - Huellitas</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {font-family:'Segoe UI',sans-serif;background-color:#fffaf5;margin:0;padding:20px;}
    h1 {text-align:center;color:#d65784;margin-bottom:20px;}
    .top-bar {display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;}
    .acciones-left {display:flex;gap:8px;}
    .btn {padding:8px 14px;background:#f28cb3;border:none;border-radius:8px;color:white;cursor:pointer;text-decoration:none;font-weight:bold;transition:background .3s;}
    .btn:hover {background:#e16a97;}
    .buscador {display:flex;align-items:center;gap:6px;}
    .buscador input[type="text"] {padding:8px 14px;width:220px;border-radius:20px;border:1px solid #ccc;outline:none;font-size:14px;}
    .buscador button {background:#f28cb3;border:none;padding:8px 14px;border-radius:20px;cursor:pointer;color:#fff;font-size:14px;}
    .buscador button:hover {background:#e16a97;}
    table {width:100%;border-collapse:collapse;border-radius:12px;overflow:hidden;box-shadow:0 4px 10px rgba(0,0,0,0.1);}
    th,td {padding:12px;text-align:center;border-bottom:1px solid #f2c7d9;}
    th {background:#f28cb3;color:white;font-size:14px;}
    tr:nth-child(even){background:#ffe4ec;}
    tr:hover{background:#fdd6e0;}
    td img{width:60px;height:60px;object-fit:cover;border-radius:50%;border:2px solid #f28cb3;}
    td .acciones{display:flex;justify-content:center;gap:5px;}
    /* Modal estilos */
    .modal {display:flex;align-items:center;justify-content:center;position:fixed;z-index:9999;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,0.4);}
    .modal-content {background:#fff;padding:20px 40px;border-radius:12px;text-align:center;font-size:18px;color:#333;font-weight:bold;box-shadow:0 4px 10px rgba(0,0,0,0.2);animation:fadeIn .3s;}
    @keyframes fadeIn {from{opacity:0;transform:scale(0.9);}to{opacity:1;transform:scale(1);}}
  </style>
</head>
<body>

  <h1>Panel del Administrador 🐾</h1>
  
  <div class="top-bar">
    <div class="acciones-left">
      <a class="btn" href="Agregar.php">➕ Añadir Mascota</a>
      <a class="btn" href="Adoptantes.php">👤 Ver Adoptantes</a>
      <a class="btn" href="Solicitudes.php">📄 Ver Solicitudes</a>
      <a class="btn" href="Adoptados.php">🐾 Ver Mascotas Adoptadas</a>
      <a class="btn" href="AdoptaptesConMascotas.php">🐾 Ver Adopciones</a>
      <a class="btn" href="#" onclick="openLogoutModal()">🚪 Cerrar sesión</a>
    </div>

    <form method="GET" class="buscador">
      <input type="text" name="buscar" placeholder="Buscar mascota..." value="<?php echo isset($_GET['buscar']) ? $_GET['buscar'] : '' ?>">
      <button type="submit"><i class="fa fa-search"></i></button>
    </form>
  </div>

  <table>
    <tr>
      <th>ID</th><th>Nombre</th><th>Tipo</th><th>Edad</th><th>Sexo</th><th>Raza</th><th>Color</th><th>Características</th><th>Descripción</th><th>Estado</th><th>Imagen</th><th>Acciones</th>
    </tr>

    <?php
    if($res && $res->num_rows > 0){
        while ($row = $res->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['id_mascota']}</td>";
            echo "<td>{$row['nombre']}</td>";
            echo "<td>{$row['tipo']}</td>";
            echo "<td>{$row['edad']}</td>";
            echo "<td>{$row['sexo']}</td>";
            echo "<td>{$row['raza']}</td>";
            echo "<td>{$row['color']}</td>";
            echo "<td>{$row['caracteristicas']}</td>";
            echo "<td>{$row['descripcion']}</td>";
            
$color_estado = ($row['estado'] == 'disponible') ? '#c1f0c1' : '#b0d4ff'; // verde clarito o azul pastel
$color_texto = '#333';
echo "<td style='background:$color_estado; color:$color_texto; font-weight:bold; padding:5px 10px; border-radius:8px; text-transform:capitalize;'>{$row['estado']}</td>";




            echo "<td>".(!empty($row['foto']) ? "<img src='{$row['foto']}' alt='Mascota'>" : "")."</td>";
            echo "<td>
                    <div class='acciones'>
                      <a class='btn' href='Editar.php?id={$row['id_mascota']}'>✏ Editar</a>
                      <a class='btn' href='Eliminar.php?id={$row['id_mascota']}'>🗑 Eliminar</a>
                    </div>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='12'>No hay mascotas registradas</td></tr>";
    }
    ?>
  </table>

  <!-- Modal Bienvenida (solo aparece 1 vez) -->
  <?php if(isset($_SESSION["bienvenida"]) && $_SESSION["bienvenida"] === true): ?>
    <div id="welcomeModal" class="modal">
      <div class="modal-content">
        <h2>¡Bienvenido Administrador! 🎉</h2>
      </div>
    </div>
    <script>
      setTimeout(function(){
        document.getElementById("welcomeModal").style.display = "none";
      }, 3000);
    </script>
    <?php unset($_SESSION["bienvenida"]); ?>
  <?php endif; ?>

  <!-- Modal de Confirmación de Cierre de Sesión -->
  <div id="logoutModal" class="modal" style="display:none;">
    <div class="modal-content">
      <h2>¿Deseas cerrar sesión?</h2>
      <div style="margin-top:15px; display:flex; gap:10px; justify-content:center;">
        <button class="btn" onclick="confirmLogout()">Aceptar</button>
        <button class="btn" style="background:#aaa;" onclick="closeLogoutModal()">Cancelar</button>
      </div>
    </div>
  </div>

<script>
  function openLogoutModal() {
    document.getElementById("logoutModal").style.display = "flex";
  }
  function closeLogoutModal() {
    document.getElementById("logoutModal").style.display = "none";
  }
  function confirmLogout() {
    window.location.href = "index.php";
  }
</script>

</body>
</html>

