<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD_TPFINAL/configuracion.php";

$data = data_submitted();

$abmUsuario = new AbmUsuario();

$result = $abmUsuario->crearUsuarioConRol($data);

echo json_encode($result);
