<?php
include_once ROOT_PATH."/configuracion.php";
$data = Funciones::data_submitted();

$salida = (new AbmCompraEstado())->listarCompraEstados($data); //['idcompraestadotipo']

echo json_encode($salida);
?>