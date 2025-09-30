<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Huellitas por un hogar</title>
  <link rel="stylesheet" href="diseño.css">

  <link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
/>

</head>
<body>

  <!-- Encabezado y Menú -->
  <header>

  <!-- Este div es el botón ☰ -->
  <div class="menu-icon" onclick="toggleMenu()">
    ☰
  </div>

  <!-- Este es el panel lateral oculto -->
  <div class="side-menu" id="sideMenu">
    <a href="login.php">Iniciar sesión</a>
    <a href="registro.php">Registrarse</a>
    <a href="administrador.php">Administrador</a>
  </div>

    <h1>Huellitas por un hogar 🐾</h1>
    <nav>
      <ul>
        <li><a href="#inicio">Inicio</a></li>
        <li><a href="#mascotas">Mascotas</a></li>
        <li><a href="#contacto">Contacto</a></li>
      </ul>
    </nav>
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
  <section class="bienvenida"  id="inicio">
    <h2>Bienvenido a Huellitas por un hogar</h2>
    <p>Ayudamos a perritos y gatitos a encontrar un hogar lleno de amor. ¡Tú puedes hacer la diferencia!</p>
  </section>

  <!-- Galería de mascotas -->  <section class="galeria" id="mascotas">
    <h2>Nuestras mascotas</h2>
    <h3>Perros</h3>
    <div class="tarjetas">
      <div class="card">
        <img src="https://place-puppy.com/200x200" alt="Perro"><br><br>
        <button>Ver más</button>
      </div>
    </div>
    <h3>Gatos</h3>
    <div class="tarjetas">
      <div class="card">
        <img src="https://placekitten.com/200/200" alt="Gato"><br><br>
        <button>Ver más</button>
      </div>
    </div>
    <h3>Aves</h3>
    <div class="tarjetas">
      <div class="card">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fb/Blue_Parakeet.jpg/200px-Blue_Parakeet.jpg" alt="Ave"><br><br>
        <button>Ver más</button>
      </div>
    </div>
    <h3>Hamsters</h3>
    <div class="tarjetas">
      <div class="card">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/6f/Hamster_brown.jpg/200px-Hamster_brown.jpg" alt="Hamster"><br><br>
        <button>Ver más</button>
      </div>
    </div>
  </section> 

  <!-- Pie de página -->
  <footer id="contacto">
    <p>© 2025 Huellitas por un hogar 🐾 | Síguenos en nuestras redes sociales</p>
    
    <a href="https://www.facebook.com/susana.munoz.353805" target="_blank" rel="noopener" style="margin: 0 15px; color: #3b5998;">
      <i class="fab fa-facebook"></i>
    </a>

<a href="https://www.instagram.com/iam_susanix_" target="_blank" rel="noopener" style="margin: 0 15px; color: #e4405f;">
      <i class="fab fa-instagram"></i>
    </a>

  </footer>

<script>
  function toggleMenu() {
    const menu = document.getElementById("sideMenu");
    menu.style.display = (menu.style.display === "flex") ? "none" : "flex";
  }
</script>


</body>
</html>