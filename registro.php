<?php 
session_start();
include("Conexion.php");

$mensaje = "";
$tipo = ""; 
$registroIntentado = false; // 🔹 Control

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $registroIntentado = true;
    $nombre = trim($_POST["nombre"]);
    $correo = trim($_POST["correo"]);
    $contrasena = trim($_POST["contrasena"]);
    $rol = "usuario"; 

    // --- Validar si el correo ya existe ---
    $stmt = $conexion->prepare("SELECT id FROM usuario WHERE correo = ? OR contrasena = ?");
    $stmt->bind_param("ss", $correo, $contrasena);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $mensaje = "❌ El correo o la contraseña ya están registrados";
        $tipo = "error";
    } else {
        $stmt = $conexion->prepare("INSERT INTO usuario (nombre, correo, contrasena, rol) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nombre, $correo, $contrasena, $rol);

        if ($stmt->execute()) {
            $mensaje = "✅ Usuario registrado correctamente";
            $tipo = "success";
        } else {
            $mensaje = "❌ Error al registrar usuario";
            $tipo = "error";
        }
    }
    $stmt->close();
    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro</title>
  <style>
    body {margin:0;font-family:Arial;background:#f5f7fb;display:flex;justify-content:center;align-items:center;height:100vh;}
    .card {background:#fff;padding:24px;border-radius:16px;box-shadow:0 10px 25px rgba(0,0,0,.08);width:100%;max-width:420px;}
    .card h1{text-align:center;}
    .field{display:flex;flex-direction:column;margin-bottom:12px;}
    .field label{margin-bottom:6px;}
    .field input{padding:10px;border:1px solid #ccc;border-radius:8px;}
    button{padding:10px;background:#8b1e1e;color:#fff;border:none;border-radius:8px;cursor:pointer;}
    .hint{font-size:13px;text-decoration:none;}

    /* --- Modal --- */
    .modal-overlay {
      position: fixed;
      top:0; left:0; right:0; bottom:0;
      background: rgba(0,0,0,0.5);
      display:flex; justify-content:center; align-items:center;
      z-index:1000;
    }
    .modal {
      background:#fff;
      padding:20px;
      border-radius:14px;
      width:90%;
      max-width:400px;
      text-align:center;
      box-shadow:0 10px 25px rgba(0,0,0,0.2);
      animation: fadeIn .3s ease;
    }
    .modal h2 {margin-bottom:10px;}
    .modal.success h2 {color:green;}
    .modal.error h2 {color:red;}
    .modal button {
      margin-top:15px;
      padding:8px 16px;
      border:none;
      border-radius:8px;
      background:#8b1e1e;
      color:white;
      cursor:pointer;
    }
    @keyframes fadeIn {
      from {opacity:0; transform:scale(0.9);}
      to {opacity:1; transform:scale(1);}
    }
  </style>
</head>
<body>
  <div class="card">
    <h1>Registro</h1>
    <form method="POST">
      <div class="field">
        <label>Nombre</label>
        <input type="text" name="nombre" required>
      </div>
      <div class="field">
        <label>Correo</label>
        <input type="email" name="correo" required>
      </div>
      <div class="field">
        <label>Contraseña</label>
        <input type="password" name="contrasena" required>
      </div>
      <button type="submit">Registrarme</button>
      <p><a class="hint" href="login.php">¿Ya tienes cuenta? Inicia sesión</a></p>
    </form>
  </div>

  <?php if ($registroIntentado): ?> 
  <!-- Modal SOLO aparece si se intentó registrar -->
  <div class="modal-overlay" id="modalOverlay">
    <div class="modal <?php echo $tipo; ?>">
      <h2><?php echo $mensaje; ?></h2>
      <button id="closeBtn">Aceptar</button>
    </div>
  </div>
  <?php endif; ?>

  <script>
    const closeBtn = document.getElementById("closeBtn");
    if (closeBtn) {
      closeBtn.addEventListener("click", () => {
        document.getElementById("modalOverlay").style.display = "none";
        <?php if ($tipo == "success"): ?>
          window.location = "login.php";
        <?php endif; ?>
      });
    }
  </script>
</body>
</html>




