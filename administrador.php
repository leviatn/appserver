<?php
include("Conexion.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $contrasena = trim($_POST["contrasena"]);

    $stmt = $conexion->prepare("SELECT * FROM administrador WHERE contrasena = ?");
    $stmt->bind_param("s", $contrasena);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION["admin"] = true;
        $_SESSION["bienvenida"] = true; // activar modal de bienvenida
        header("Location: aparte.php");
        exit;
    } else {
        echo "<script>alert('❌ Contraseña incorrecta'); window.history.back();</script>";
    }
    $stmt->close();
    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Acceso Administrador</title>
  <style>
    body {display:flex;justify-content:center;align-items:center;height:100vh;background:#fffaf5;font-family:Arial;}
    .formulario{background:#ffe7ef;padding:24px;border-radius:12px;box-shadow:0 0 10px rgba(0,0,0,.1);}
    input{width:100%;padding:10px;margin:10px 0;border:1px solid #ccc;border-radius:8px;}
    button{width:100%;padding:10px;background:#f28cb3;border:none;color:white;font-weight:bold;border-radius:8px;cursor:pointer;}
  </style>
</head>
<body>
  <form class="formulario" method="POST">
    <h2>Administrador</h2>
    <input type="password" name="contrasena" placeholder="Contraseña" required>
    <button type="submit">Entrar</button>
  </form>
</body>
</html>

