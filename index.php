<?php
session_start();

// Mostrar modal de bienvenida solo si se acaba de iniciar sesión
$mostrarBienvenida = false;
if (isset($_SESSION["id"]) && !isset($_SESSION["bienvenida_mostrada"])) {
    $mostrarBienvenida = true;
    $_SESSION["bienvenida_mostrada"] = true; // Evita que salga siempre
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Huellitas por un hogar</title>
  <link rel="stylesheet" href="diseño.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
</head>
<body>

  <header>
    <!-- Botón ☰ -->
    <div class="menu-icon" onclick="toggleMenu()">☰</div>

    <!-- Menú lateral -->
    <div class="side-menu" id="sideMenu">
      <a href="login.php">Iniciar sesión</a>
      <a href="registro.php">Registrarse</a>
      <a href="administrador.php">Administrador</a>
    </div>

    <h1>Huellitas por un hogar 🐾</h1>

    <!-- Botones de navegación -->
    <nav>
      <ul>
        <li><a href="#inicio">Inicio</a></li>
        <li><a href="#mascotas">Mascotas</a></li>
        <li><a href="#contacto">Contacto</a></li>
      </ul>
    </nav>

    <!-- Perfil si hay sesión -->
    <?php if (isset($_SESSION["id"])): ?>
      <div style="position:absolute;top:15px;right:20px;display:flex;align-items:center;gap:15px;">
        <!-- Botón cerrar sesión -->
        <button onclick="abrirModalLogout()" 
          style="padding:6px 12px;border:none;border-radius:6px;
                 background:#a3d8f4;color:#000;cursor:pointer;font-size:14px;transition:0.3s;">
          Cerrar sesión
        </button>
        <!-- Foto + nombre -->
        <div style="display:flex;flex-direction:column;align-items:center;">
          <img src="img/perfil.png" alt="perfil" style="width:60px;height:60px;border-radius:50%;object-fit:cover;">
          <span style="margin-top:5px;font-size:14px;font-weight:bold;">
            <?php echo htmlspecialchars($_SESSION["nombre"]); ?>
          </span>
        </div>
      </div>
    <?php endif; ?>
  </header>

  <!-- Carrusel -->
  <section class="carrusel">
    <div class="slides">
      <img src="carrusel/Perrito.jpg" alt="Perrito 1">
      <img src="carrusel/Gatito.jpg" alt="Gatito 2">
      <img src="carrusel/Ave.jpg" alt="Ave 3">
      <img src="carrusel/Hamster.jpg" alt="Hamster 4">
    </div>
  </section>

  <!-- Bienvenida -->
  <section class="bienvenida" id="inicio">
    <h2>Bienvenido a Huellitas por un hogar</h2>
    <p>Ayudamos a animalitos a encontrar un hogar lleno de amor. ¡Tú puedes hacer la diferencia!</p>
    <img src="Perros/Logo.jpg" alt="Perros" style="width:200px; height:auto;">
  </section>

  <!-- Galería de mascotas --> 
  <section class="galeria" id="mascotas"> 
    <h2>Nuestras mascotas</h2> 

    <h3>Perros</h3> 
    <div class="tarjetas"> 
      <div class="card"> 
        <img src="Perros/Perrito Representativo.jpg" alt="Perros"><br><br> 
        <?php if (isset($_SESSION["id"])): ?>
          <a href="perros.php"><button>Ver más</button></a>
        <?php else: ?>
          <button onclick="abrirModalLogin('perros.php')">Ver más</button>
        <?php endif; ?>
      </div>
    </div>

    <h3>Gatos</h3> 
    <div class="tarjetas"> 
      <div class="card"> 
        <img src="Perros/Gato Representativo.jpg" alt="Gatos"><br><br> 
        <?php if (isset($_SESSION["id"])): ?>
          <a href="Gatos.php"><button>Ver más</button></a>
        <?php else: ?>
          <button onclick="abrirModalLogin('Gatos.php')">Ver más</button>
        <?php endif; ?>
      </div> 
    </div> 

    <h3>Aves</h3> 
    <div class="tarjetas"> 
      <div class="card"> 
        <img src="Perros/Ave Representativo.jpg" alt="Aves"><br><br> 
        <?php if (isset($_SESSION["id"])): ?>
          <a href="Aves.php"><button>Ver más</button></a>
        <?php else: ?>
          <button onclick="abrirModalLogin('Aves.php')">Ver más</button>
        <?php endif; ?>
      </div>
    </div> 

    <h3>Hamster</h3> 
    <div class="tarjetas"> 
      <div class="card"> 
        <img src="Perros/Hamster Representativo.jpg" alt="Hamsters"><br><br> 
        <?php if (isset($_SESSION["id"])): ?>
          <a href="Hamsters.php"><button>Ver más</button></a>
        <?php else: ?>
          <button onclick="abrirModalLogin('Hamsters.php')">Ver más</button>
        <?php endif; ?>
      </div> 
    </div>
  </section>

  <!-- Pie de página -->
  <footer id="contacto">
    <p>© 2025 Huellitas por un hogar 🐾 | Síguenos en nuestras redes sociales</p>
    <a href="https://www.instagram.com/huellitas_hogar/" target="_blank" rel="noopener" style="margin: 0 15px; color: #e4405f;">
      <i class="fab fa-instagram"></i>
    </a>
    <a href="https://wa.me/50378636703b" target="_blank" rel="noopener" style="margin: 0 15px; color: #25D366;">
  <i class="fab fa-whatsapp"></i>
</a>

  </footer>

  <!-- Modal Cerrar Sesión -->
  <div id="logoutModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
       background:rgba(0,0,0,0.5); align-items:center; justify-content:center; z-index:999;">
    <div style="background:#fff;padding:20px 30px;border-radius:12px;text-align:center;
                box-shadow:0 4px 15px rgba(0,0,0,0.3);max-width:300px;">
      <h3 style="margin-bottom:15px;">¿Cerrar sesión?</h3>
      <p style="font-size:14px;margin-bottom:20px;">Tu sesión actual se cerrará y volverás a la página principal.</p>
      <div style="display:flex;justify-content:space-around;">
        <button onclick="cerrarModalLogout()" style="padding:6px 12px;border:none;border-radius:6px;background:#ccc;cursor:pointer;">Cancelar</button>
        <button onclick="confirmarLogout()" style="padding:6px 12px;border:none;border-radius:6px;background:#a3d8f4;color:#000;cursor:pointer;">Sí, salir</button>
      </div>
    </div>
  </div>

  <!-- Modal Bienvenida -->
  <?php if ($mostrarBienvenida): ?>
  <div id="bienvenidaModal" style="position:fixed;top:0;left:0;width:100%;height:100%;
       background:rgba(0,0,0,0.5);display:flex;align-items:center;justify-content:center;z-index:999;">
    <div style="background:#fff0f6;padding:25px 35px;border-radius:16px;text-align:center;
                box-shadow:0 6px 18px rgba(0,0,0,0.25);max-width:400px;">
      <h2 style="color:#d63384;margin-bottom:10px;">¡Bienvenido amiguito! 🐶🐱</h2>
      <p style="color:#555;margin-bottom:20px;">Nuestras mascotas esperan un hogar lleno de amor 💕</p>
      <button onclick="cerrarModalBienvenida()" style="padding:8px 16px;border:none;border-radius:8px;background:#a3d8f4;color:#000;cursor:pointer;">
        ¡Vamos!
      </button>
    </div>
  </div>
  <?php endif; ?>

  <!-- Modal Inicio de Sesión -->
  <div id="loginModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
       background:rgba(0,0,0,0.5); align-items:center; justify-content:center; z-index:999;">
    <div style="background:#fff;padding:20px 30px;border-radius:12px;text-align:center;
                box-shadow:0 4px 15px rgba(0,0,0,0.3);max-width:350px;width:90%;">
      <h3 style="margin-bottom:15px;">Inicia sesión 🐾</h3>
      <p style="font-size:14px;margin-bottom:20px;">Debes iniciar sesión para ver a nuestras mascotas</p>
      <div style="display:flex;justify-content:space-around;">
        <button onclick="cerrarModalLogin()" style="padding:6px 12px;border:none;border-radius:6px;background:#ccc;cursor:pointer;">Cancelar</button>
        <button onclick="redirigirLogin()" style="padding:6px 12px;border:none;border-radius:6px;background:#a3d8f4;color:#000;cursor:pointer;">Iniciar sesión</button>
      </div>
    </div>
  </div>

<script>
  function toggleMenu() {
    const menu = document.getElementById("sideMenu");
    menu.style.display = (menu.style.display === "flex") ? "none" : "flex";
  }

  // Logout modal
  function abrirModalLogout() { document.getElementById("logoutModal").style.display = "flex"; }
  function cerrarModalLogout() { document.getElementById("logoutModal").style.display = "none"; }
  function confirmarLogout() {
    cerrarModalLogout();
    setTimeout(() => { window.location.href = "logout.php"; }, 200);
  }

  // Bienvenida modal
  function cerrarModalBienvenida() { document.getElementById("bienvenidaModal").style.display = "none"; }

  // Login modal
  function abrirModalLogin(redirectPage) { 
    document.getElementById("loginModal").style.display = "flex"; 
    window.redirectAfterLogin = redirectPage; 
  }
  function cerrarModalLogin() { document.getElementById("loginModal").style.display = "none"; }
  function redirigirLogin() {
    if (window.redirectAfterLogin) {
      window.location.href = "login.php?redirect=" + window.redirectAfterLogin;
    } else {
      window.location.href = "login.php";
    }
  }
</script>

</body>
</html>


