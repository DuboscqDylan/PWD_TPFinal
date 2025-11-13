<?php
include_once $_SERVER['DOCUMENT_ROOT']."/PWD_TPFINAL/configuracion.php";
$data = data_submitted();

$salida = (new AbmCompraEstado())->listarCarrito($data); 

echo json_encode($salida);
?>