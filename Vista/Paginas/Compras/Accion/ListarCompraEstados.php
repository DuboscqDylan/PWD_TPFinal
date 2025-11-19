<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD_TPFINAL/configuracion.php";
$data = data_submitted();

$salida = (new AbmCompraEstado())->listarCompraEstados($data); //['idcompraestadotipo']

echo json_encode($salida);
