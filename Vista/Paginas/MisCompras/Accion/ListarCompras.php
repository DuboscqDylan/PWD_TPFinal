<?php
include_once $_SERVER['DOCUMENT_ROOT']."/PWD_TPFINAL/configuracion.php";
$data = Funciones::data_submitted();

$resultado = (new AbmCompra())->listarMisCompras($data);

echo json_encode($resultado);
?>