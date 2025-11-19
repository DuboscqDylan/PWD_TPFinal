<?php
include_once $_SERVER['DOCUMENT_ROOT']."/PWD_TPFINAL/configuracion.php";

$data = data_submitted();

$abmUsuario = new AbmUsuario();
$abmUsuarioRol = new AbmUsuarioRol();
$abmRol = new AbmRol();

if (!isset($data['user'], $data['email'], $data['password'], $data['rol'])) {
    echo json_encode(["success" => false, "message" => "Faltan datos obligatorios"]);
    exit;
}

$nombre = $data['user'];
$email = $data['email'];
$pass = $data['password'];
$rol = $data['rol'];

$existe = $abmUsuario->buscar(['usmail' => $email]);

if ($existe) {
    echo json_encode(["success" => false, "message" => "El email ya está registrado"]);
    exit;
}

$rol = $abmRol->buscar(['idrol' => $rol]);
if (!$rol) {
    echo json_encode(["success" => false, "message" => "Rol inválido"]);
    exit;
}

$idRol = $rol[0]->getIdRol();

$usuarioData = [
    "usnombre" => $nombre,
    "usmail" => $email,
    "uspass" => $pass,
    "usdeshabilitado" => null
];

$creado = $abmUsuario->alta($usuarioData);

if (!$creado) {
    echo json_encode(["success" => false, "message" => "Error al crear el usuario"]);
    exit;
}

$nuevoUsuario = $abmUsuario->buscar(['usmail' => $email]);
$idUsuario = $nuevoUsuario[0]->getIdUsuario();

$abmUsuarioRol->alta([
    "idusuario" => $idUsuario,
    "idrol" => $idRol
]);

echo json_encode(["success" => true, "message" => "Usuario creado exitosamente"]);