<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD_TPFINAL/configuracion.php";
$data = data_submitted();

$respuesta = (new AbmCompraItem())->quitarCompraItem($data);
echo json_encode($respuesta);
?>
