<?php
include("Conexion.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = trim($_POST["correo"]);
    $contrasena = trim($_POST["contrasena"]);

    $stmt = $conexion->prepare("SELECT id, nombre, rol FROM usuario WHERE correo = ? AND contrasena = ?");
    $stmt->bind_param("ss", $correo, $contrasena);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $_SESSION["id"] = $row["id"];
        $_SESSION["nombre"] = $row["nombre"];
        $_SESSION["rol"] = $row["rol"];
        $_SESSION["bienvenida"] = true;

        if (isset($_GET["redirect"]) && !empty($_GET["redirect"])) {
            $redirect = urldecode($_GET["redirect"]);
            header("Location: " . $redirect . "?fromAdopt=1");
        } else {
            header("Location: index.php"); 
        }
        exit;
    } else {
        echo "<script>alert('❌ Correo o contraseña incorrectos'); window.history.back();</script>";
    }
    $stmt->close();
    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar sesión</title>
  <style>
    body {background:#f5f7fb;display:flex;justify-content:center;align-items:center;height:100vh;font-family:Arial;}
    .card{background:#fff;padding:24px;border-radius:16px;box-shadow:0 10px 25px rgba(0,0,0,.08);width:100%;max-width:420px;}
    .field{display:flex;flex-direction:column;margin-bottom:12px;}
    .field input{padding:10px;border:1px solid #ccc;border-radius:8px;}
    button{padding:10px;background:#3b82f6;color:#fff;border:none;border-radius:8px;cursor:pointer;}
  </style>
</head>
<body>
  <div class="card">
    <h1>Iniciar sesión</h1>
    <form method="POST">
      <div class="field">
        <label>Correo</label>
        <input type="email" name="correo" required>
      </div>
      <div class="field">
        <label>Contraseña</label>
        <input type="password" name="contrasena" required>
      </div>
      <button type="submit">Entrar</button>
      <p><a href="registro.php">¿No tienes cuenta? Regístrate</a></p>
    </form>
  </div>
</body>
</html>



