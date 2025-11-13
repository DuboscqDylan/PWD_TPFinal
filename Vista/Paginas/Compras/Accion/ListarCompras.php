<?php
include_once ROOT_PATH."/configuracion.php";
$data = data_submitted();

$salida = (new AbmCompraEstado())->ListarCompras($data); 

echo json_encode($salida);
?>