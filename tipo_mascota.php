<?php
include("Conexion.php");

if(isset($_GET['id'])){
    $id = intval($_GET['id']);
    $res = mysqli_query($conexion, "SELECT tipo FROM mascotas WHERE id_mascota = $id");
    if($fila = mysqli_fetch_assoc($res)){
        echo $fila['tipo'];
    } else {
        echo "Perro"; // default
    }
}
?>
