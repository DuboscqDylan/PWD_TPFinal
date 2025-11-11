<?php
include_once BASE_URL."/configuracion.php";
$data = Funciones::data_submitted();

$resultado = (new AbmCompra())->listarMisCompras($data);

echo json_encode($resultado);
?>