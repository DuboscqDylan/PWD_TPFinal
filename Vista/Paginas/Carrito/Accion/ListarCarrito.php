<?php
include_once $_SERVER['DOCUMENT_ROOT']."/PWD_TPFINAL/configuracion.php";
$data = Funciones::data_submitted(); 

$respuesta = (new ABMCompraEstado())->listarCarrito($data);

echo json_encode($respuesta);
?>