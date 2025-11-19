<?php
include_once $_SERVER['DOCUMENT_ROOT']."/PWD_TPFINAL/configuracion.php";

$data = data_submitted();
$abmUsuario = new AbmUsuario();

echo json_encode($abmUsuario->darDeBajaConRol($data));
?>
