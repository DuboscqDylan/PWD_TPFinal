<?php
include_once BASE_URL.'/configuracion.php';
$data = Funciones::data_submitted();

$salida = (new AbmCompraEstado())->listarCompraEstados($data); //['idcompraestadotipo']

echo json_encode($salida);
?>