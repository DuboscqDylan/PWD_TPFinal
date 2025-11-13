<?php
include_once ROOT_PATH."/configuracion.php";
$data = data_submitted(); 

$respuesta = (new AbmCompra())->cambiarEstado($data); 

echo json_encode($respuesta);
?>