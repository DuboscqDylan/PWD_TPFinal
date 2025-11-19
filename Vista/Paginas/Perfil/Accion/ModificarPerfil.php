<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD_TPFINAL/configuracion.php";

$sesion = new Session();

$idusuario = $sesion->getUsuario()->getIdusuario();
$data = data_submitted();

$abm = new AbmUsuario();
$resultado = $abm->actualizarPerfil($idusuario, $data);

echo json_encode($resultado);
