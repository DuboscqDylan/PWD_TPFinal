<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD_TPFINAL/configuracion.php";

session_start();

// 📌 Verificar sesión
if (!isset($_SESSION['idusuario'])) {
    echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
    exit;
}

$id = $_SESSION['idusuario'];

$objUsuario = new AbmUsuario();
$usuario = $objUsuario->buscar(['idusuario' => $id]);

if ($usuario) {
    $u = $usuario[0];
    echo json_encode([
        "success" => true,
        "data" => [
            "usnombre" => $u->getUsNombre(),
            "usmail" => $u->getUsMail()
        ]
    ]);
} else {
    echo json_encode(["success" => false, "message" => "No se encontró el usuario"]);
}