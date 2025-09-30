<?php
include("Conexion.php");
session_start();

// Aceptar solicitud
if(isset($_GET['accion']) && $_GET['accion']=='aceptar' && isset($_GET['id']) && isset($_GET['id_mascota'])) {
    $id = intval($_GET['id']);
    $id_mascota = $_GET['id_mascota'];

    // 1. Cambiar estado de la solicitud
    $conexion->query("UPDATE solicitud_adopcion SET estado='Aceptada' WHERE id=$id");

    // 2. Cambiar estado de la mascota
    $conexion->query("UPDATE mascotas SET estado='adoptado' WHERE id_mascota='$id_mascota'");
}

// Rechazar solicitud
if(isset($_GET['accion']) && $_GET['accion']=='rechazar' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conexion->query("UPDATE solicitud_adopcion SET estado='Rechazada' WHERE id=$id");
}



// Consultamos solicitudes con info del adoptante y la mascota
$sql = "SELECT s.id, 
               CONCAT(a.nombres,' ',a.apellidos) AS nombre_adoptante, 
               m.nombre AS nombre_mascota, 
               m.tipo,
               s.fecha, 
               s.mensaje,
               s.estado,
               m.id_mascota
        FROM solicitud_adopcion s
        JOIN adoptante a ON s.id_adoptante = a.id_adoptante
        JOIN mascotas m ON s.id_mascota = m.id_mascota
        ORDER BY s.id DESC";

$res = $conexion->query($sql);
?>



<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Solicitudes de Adopción - Huellitas</title>
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
    .btn {
      padding: 6px 12px;
      border: none;
      border-radius: 6px;
      color: white;
      cursor: pointer;
      text-decoration: none;
      font-weight: bold;
      transition: background 0.3s;
    }
    .btn:hover { opacity: 0.8; }
    .btn-editar { background-color: #f28cb3; }
    .btn-eliminar { background-color: #f44336; }
    .btn-aceptar { background-color: #4CAF50; }
    .btn-rechazar { background-color: #e16a97; }

    .menu {
      display: flex;
      justify-content: flex-start;
      gap: 10px;
      margin-bottom: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    th, td {
      padding: 12px;
      text-align: center;
      border-bottom: 1px solid #f2c7d9;
    }
    th {
      background-color: #f28cb3;
      color: white;
      font-size: 14px;
    }
    tr:nth-child(even) { background-color: #ffe4ec; }
    tr:hover { background-color: #fdd6e0; }
    td .acciones {
      display: flex;
      justify-content: center;
      gap: 5px;
    }
  </style>
</head>
<body>

  <div class="menu">
    <a class="btn btn-editar" href="aparte.php">⬅ Volver</a>
  </div>

  <h1>📄 Solicitudes de Adopción</h1>

  <table>
    <tr>
      <th>ID</th>
      <th>Adoptante</th>
      <th>Mascota</th>
      <th>Fecha</th>
      <th>Mensaje</th>
      <th>Estado</th>
      <th>Acciones</th>
    </tr>

    <?php
    if($res && $res->num_rows > 0){
        while ($row = $res->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['nombre_adoptante']}</td>";
            echo "<td>{$row['nombre_mascota']}</td>";
            echo "<td>{$row['fecha']}</td>";
            echo "<td>{$row['mensaje']}</td>";
            echo "<td>{$row['estado']}</td>";
            echo "<td>
                    <div class='acciones'>";
            // Si está pendiente, mostramos los botones
            if ($row['estado'] == 'Pendiente') {
                echo "<a class='btn btn-aceptar' href='?accion=aceptar&id={$row['id']}&id_mascota={$row['id_mascota']}'>Aceptar</a>";
                echo "<a class='btn btn-rechazar' href='?accion=rechazar&id={$row['id']}'>Rechazar</a>";
            }
            // Siempre mostrar editar y eliminar
            echo "<a class='btn btn-editar' href='EditarSolicitud.php?id={$row['id']}'>✏ Editar</a>";
            echo "<a class='btn btn-eliminar' href='EliminarSolicitudd.php?id={$row['id']}'>🗑 Eliminar</a>";
            echo "</div>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No hay solicitudes registradas</td></tr>";
    }
    ?>
  </table>

</body>
</html>



