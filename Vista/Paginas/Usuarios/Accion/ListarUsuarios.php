<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD_TPFINAL/configuracion.php";

$data = data_submitted();

$abmUsuarioRol = new AbmUsuarioRol();
$salida = $abmUsuarioRol->listarUsuariosFormateados($data);

echo json_encode($salida);
