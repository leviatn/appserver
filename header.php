<?php
session_start();n
if (isset($_SESSION['nombre'])) {
    $foto = isset($_SESSION['foto']) && $_SESSION['foto'] !== '' ? $_SESSION['foto'] : 'default.png';
    $ruta = 'img/' . basename($foto);
    if (!file_exists(__DIR__ . '/' . $ruta)) {
        $ruta = 'img/default.png';
    }
    echo '<div style="position:fixed; top:12px; right:12px; display:flex; align-items:center; gap:10px;">';
    echo '<img src="'. htmlspecialchars($ruta) .'" alt="Foto" style="width:40px; height:40px; border-radius:50%; object-fit:cover; border:1px solid #e5e7eb;">';
    echo '<span style="font-size:14px; background:#fff; padding:6px 10px; border-radius:999px; box-shadow:0 2px 8px rgba(0,0,0,.08)">' . htmlspecialchars($_SESSION['nombre']) . '</span>';
    echo '</div>';
}
?>
