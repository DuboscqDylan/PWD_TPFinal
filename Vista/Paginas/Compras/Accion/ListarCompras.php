<?php
include_once $_SERVER['DOCUMENT_ROOT']."/PWD_TPFINAL/configuracion.php";
$data = data_submitted();

$salida = (new AbmCompraEstado())->ListarCompras($data); 

echo json_encode($salida);
?>