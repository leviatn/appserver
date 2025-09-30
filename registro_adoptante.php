<?php
include("Conexion.php");
session_start();

$registro_exitoso = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombres = $_POST["nombres"];
    $apellidos = $_POST["apellidos"];
    $telefono = $_POST["telefono"];
    $correo = $_POST["correo"];
    $dui = $_POST["dui"];
    $direccion = $_POST["direccion"];

    $stmt = $conexion->prepare("INSERT INTO adoptante (nombres, apellidos, telefono, correo, DUI, direccion) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nombres, $apellidos, $telefono, $correo, $dui, $direccion);

    if ($stmt->execute()) {
        $registro_exitoso = true;
    } else {
        $error = "No se pudo registrar. Por favor inténtalo nuevamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Adoptante</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff9f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .card {
            background: #ffe4c4;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
        }
        h2 {
            text-align: center;
            color: #6d3d14;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-weight: bold;
            color: #6d3d14;
        }
        input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            outline: none;
        }
        input:focus {
            border-color: #ff914d;
            box-shadow: 0 0 5px rgba(255,145,77,0.5);
        }
        .btn-container {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        .btn {
            padding: 12px;
            border: none;
            background: #ff914d;
            color: white;
            font-weight: bold;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
            width: 48%;
        }
        .btn:hover {
            background: #ff6f1f;
        }
        .btn-regresar {
            background: #6d3d14;
        }
        .btn-regresar:hover {
            background: #4b2910;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Registro de Adoptante</h2>
        <form method="POST">
            <label>Nombres:</label>
            <input type="text" name="nombres" required>
            <label>Apellidos:</label>
            <input type="text" name="apellidos" required>
            <label>Teléfono:</label>
            <input type="text" name="telefono" required>
            <label>Correo:</label>
            <input type="email" name="correo" required>
            <label>DUI:</label>
            <input type="text" name="dui" required>
            <label>Dirección:</label>
            <input type="text" name="direccion" required>
            
            <div class="btn-container">
                <button type="submit" class="btn">Registrar</button>
                <button type="button" class="btn btn-regresar" onclick="alertaYaTienesCuenta()">
                    Ya tienes cuenta 🐶
                </button>
            </div>
        </form>
    </div>

    <script>
    function alertaYaTienesCuenta() {
        Swal.fire({
            icon: 'info',
            title: 'Solicitud de adopción',
            text: 'Debes enviar una solicitud de adopción para poder adoptar 🐶',
            confirmButtonText: 'Ir a solicitud'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'solicitud_adopcion.php';
            }
        });
    }
    </script>

    <?php if ($registro_exitoso): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Registro exitoso!',
            text: 'Ya estás registrado como adoptante. Ahora podrás enviar solicitudes de adopción 🐶',
            confirmButtonText: 'Aceptar'
        }).then(() => {
            window.location.href = 'solicitud_adopcion.php';
        });
    </script>
    <?php elseif(isset($error)): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '<?= $error ?>',
            confirmButtonText: 'Aceptar'
        });
    </script>
    <?php endif; ?>
</body>
</html>





