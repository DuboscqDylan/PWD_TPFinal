<?php
include_once ROOT_PATH."/configuracion.php";
$data = Funciones::data_submitted();

$resultado = (new AbmCompra())->listarMisCompras($data);

echo json_encode($resultado);
?>