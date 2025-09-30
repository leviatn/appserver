<?php
include("Conexion.php");
session_start();

// Si no hay sesión de admin, regresar
if (!isset($_SESSION["admin"])) {
    header("Location: administrador.php");
    exit;
}

// --- FILTRO DE BUSQUEDA ---
$filtro = "";
if (isset($_GET['buscar']) && !empty($_GET['buscar'])) {
    $buscar = $conexion->real_escape_string($_GET['buscar']);
    $filtro = "AND (a.nombres LIKE '%$buscar%' OR a.apellidos LIKE '%$buscar%')";
}

// Consulta que une adoptante y mascota adoptada
$sql = "SELECT a.nombres, a.apellidos, a.telefono, a.DUI, a.direccion,
               m.nombre AS nombre_mascota, m.sexo, m.raza, m.edad, m.foto
        FROM adoptante a
        JOIN solicitud_adopcion s ON a.id_adoptante = s.id_adoptante
        JOIN mascotas m ON s.id_mascota = m.id_mascota
        WHERE s.estado='Aceptada' $filtro
        ORDER BY a.nombres ASC";

$res = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Adoptantes y sus Mascotas</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
body { font-family:'Segoe UI',sans-serif; background-color:#fffaf5; margin:0; padding:20px; }
h1 { text-align:center; color:#d65784; margin-bottom:20px; }

/* --- Botones --- */
.btn {
    display:inline-block;
    padding:8px 15px;
    border:none;
    border-radius:20px;
    color:white;
    cursor:pointer;
    text-decoration:none;
    font-weight:bold;
    transition:0.3s;
}
.btn:hover { opacity:0.85; }
.btn-editar { background-color:#f28cb3; }
.btn-editar:hover { background-color:#e16a97; }

/* --- Buscador --- */
.buscador { display:flex; justify-content:flex-end; margin-bottom:15px; gap:10px; }
.buscador input[type="text"] { padding:8px 10px; border-radius:8px; border:1px solid #ccc; outline:none; }
.buscador button { padding:8px 15px; border-radius:8px; border:none; background:#f28cb3; color:white; cursor:pointer; font-weight:bold; }
.buscador button:hover { background:#e16a97; }

/* --- Tabla --- */
table { width:100%; border-collapse:collapse; border-radius:12px; overflow:hidden; box-shadow:0 4px 10px rgba(0,0,0,0.1);}
th, td { padding:10px; text-align:center; border-bottom:1px solid #f2c7d9; }
th { background:#f28cb3; color:white; font-size:14px; }
tr:nth-child(even){ background:#ffe4ec; }
tr:hover{ background:#fdd6e0; }
td img { width:50px; height:50px; object-fit:cover; border-radius:50%; border:2px solid #f28cb3; }
</style>
</head>
<body>

<h1>🐾 Adoptantes y sus Mascotas Adoptadas</h1>

<div class="menu">
    <a class="btn btn-editar" href="aparte.php">⬅ Volver</a>
</div>

<form method="GET" class="buscador">
    <input type="text" name="buscar" placeholder="Buscar por nombre del adoptante..." value="<?php echo isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : '' ?>">
    <button type="submit"><i class="fa fa-search"></i> Buscar</button>
</form>

<table>
<tr>
    <th>Nombre</th>
    <th>Apellido</th>
    <th>Teléfono</th>
    <th>DUI</th>
    <th>Dirección</th>
    <th>Mascota</th>
    <th>Sexo</th>
    <th>Raza</th>
    <th>Edad</th>
    <th>Foto</th>
</tr>

<?php
if($res && $res->num_rows > 0){
    while($row = $res->fetch_assoc()){
        echo "<tr>";
        echo "<td>{$row['nombres']}</td>";
        echo "<td>{$row['apellidos']}</td>";
        echo "<td>{$row['telefono']}</td>";
        echo "<td>{$row['DUI']}</td>";
        echo "<td>{$row['direccion']}</td>";
        echo "<td>{$row['nombre_mascota']}</td>";
        echo "<td>{$row['sexo']}</td>";
        echo "<td>{$row['raza']}</td>";
        echo "<td>{$row['edad']}</td>";
        echo "<td>".(!empty($row['foto']) ? "<img src='{$row['foto']}' alt='Mascota'>" : "")."</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='10' style='color:#555;'>No se encontraron registros</td></tr>";
}
?>
</table>

</body>
</html>

