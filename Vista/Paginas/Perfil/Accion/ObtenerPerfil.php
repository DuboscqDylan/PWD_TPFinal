<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD_TPFINAL/configuracion.php";

$sesion = new Session();

$usuario = $sesion->getUsuario();

echo json_encode(['success' => true, 'data' => ['usnombre' => $usuario->getUsNombre(), 'usmail'   => $usuario->getUsMail()]]);
