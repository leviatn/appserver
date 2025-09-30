 <?php 
	error_reporting(E_ALL);
  ini_set('display_errors', 1);

	$conexion = mysqli_connect("localhost", "root", "admin123", "adoptame", 3306);

	if(!$conexion)
	{
		die("Conexion fallida: " . mysqli_connect_error());
	}

	mysqli_set_charset($conexion, "utf8")
?>