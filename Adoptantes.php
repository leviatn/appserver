<?php
include("Conexion.php");

// --- FILTRO DE BÚSQUEDA ---
$filtro = "";
if (isset($_GET['buscar']) && !empty($_GET['buscar'])) {
    $buscar = $conexion->real_escape_string($_GET['buscar']);
    $filtro = "WHERE nombres LIKE '%$buscar%'";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Adoptantes - Huellitas</title>
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

    .menu {
      display: flex;
      justify-content: flex-start;
      gap: 10px;
      margin-bottom: 20px;
    }

    .buscar {
      text-align: right;
      margin-bottom: 15px;
    }

   .buscar {
  text-align: right;
  margin-bottom: 15px;
}

.buscar form {
  display: inline-flex;
  align-items: center;
  background: white;
  border-radius: 50px;
  overflow: hidden;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.buscar input[type="text"] {
  border: none;
  padding: 10px 15px;
  outline: none;
  font-size: 14px;
  border-radius: 50px 0 0 50px;
}

.buscar button {
  background-color: #f28cb3;
  border: none;
  padding: 10px 14px;
  cursor: pointer;
  font-size: 16px;
  color: white;
  border-radius: 0 50px 50px 0;
  transition: background 0.3s;
}

.buscar button:hover {
  background-color: #e16a97;
}


    .btn {
      padding: 8px 12px;
      background-color: #f28cb3;
      border: none;
      border-radius: 8px;
      color: white;
      cursor: pointer;
      text-decoration: none;
      font-weight: bold;
      margin: 2px;
      transition: background 0.3s;
    }

    .btn:hover {
      background-color: #e16a97;
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

    tr:nth-child(even) {
      background-color: #ffe4ec;
    }

    tr:hover {
      background-color: #fdd6e0;
    }

    td .acciones {
      display: flex;
      justify-content: center;
      gap: 5px;
    }
  </style>
</head>
<body>

  <h1>Lista de Adoptantes 👤</h1>
  
  <div class="menu">
    <a class="btn" href="aparte.php">⬅ Volver</a>
    <a class="btn" href="registro_adoptante.php">➕ Añadir Adoptante</a>
  </div>

 <!-- 🔍 Buscador arriba a la derecha -->
<div class="buscar">
  <form method="GET">
    <input type="text" name="buscar" placeholder="Buscar adoptante..." value="<?php echo isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : ''; ?>">
    <button type="submit">
      🔍
    </button>
  </form>
</div>

  <table>
    <tr>
      <th>ID</th>
      <th>Nombres</th>
      <th>Apellidos</th>
      <th>Teléfono</th>
      <th>Correo</th>
      <th>DUI</th>
      <th>Dirección</th>
      <th>Acciones</th>
    </tr>

    <?php
    $sql = "SELECT * FROM adoptante $filtro";
    $res = $conexion->query($sql);
    if($res && $res->num_rows > 0){
        while ($row = $res->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['id_adoptante']}</td>";
            echo "<td>{$row['nombres']}</td>";
            echo "<td>{$row['apellidos']}</td>";
            echo "<td>{$row['telefono']}</td>";
            echo "<td>{$row['correo']}</td>";
            echo "<td>{$row['DUI']}</td>";
            echo "<td>{$row['direccion']}</td>";
            echo "<td>
                    <div class='acciones'>
                      <a class='btn' href='EditarAdoptante.php?id={$row['id_adoptante']}'>✏ Editar</a>
                      <a class='btn' href='EliminarAdoptante.php?id={$row['id_adoptante']}'>🗑 Eliminar</a>
                    </div>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='8'>No hay adoptantes registrados</td></tr>";
    }
    ?>
  </table>

</body>
</html>

