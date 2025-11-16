<?php
include_once $_SERVER['DOCUMENT_ROOT']."/PWD_TPFINAL/configuracion.php";

$data = data_submitted();

$idusuario = $sesion->getUsuario()->getIdusuario();
$nombre     = $data['nombre'];
$email      = $data['email'];
$passActual = $data['passActual'] ;
$passNueva  = $data['passNueva'];
$passConfirm= $data['passConfirm'];


$usuarios = (new AbmUsuario())->buscar(['idusuario'=>$idusuario]);

if (!is_array($usuarios) || count($usuarios) == 0) {
    echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.']);
    exit;
}

$usuario = $usuarios[0];

// 2) Obtener password actual y campo usdeshabilitado desde el objeto Usuario
$passBD = $usuario->getUspass(); 
$deshabilitado = $usuario->getUsdeshabilitado(); 

// Si no encontramos password en el objeto, abortamos con info para debug
if ($passBD === null) {
    echo json_encode(['success' => false, 'message' => 'No se pudo obtener la contraseña desde el objeto Usuario (getter desconocido).']);
    exit;
}

// 3) Lógica para cambio de contraseña: si los campos vienen vacíos, no cambiamos
$nuevoPass = $passBD; // por defecto, conservar la actual

if ($passActual !== '' || $passNueva !== '' || $passConfirm !== '') {

    // Verificar que el usuario envió los 3 campos
    if ($passActual === '' || $passNueva === '' || $passConfirm === '') {
        echo json_encode(['success' => false, 'message' => 'Para cambiar contraseña envíe: contraseña actual, nueva y confirmación.']);
        exit;
    }

    // Verificar password actual (asumimos que en BD está el MD5)
    if (md5($passActual) !== $passBD) {
var_dump($passBD . "POST: ".md5($passActual)."\n");
        echo json_encode(['success' => false, 'message' => 'La contraseña actual es incorrecta.']);
        exit;
    }
    // Coincidencia nueva/confirm
    if ($passNueva !== $passConfirm) {
        echo json_encode(['success' => false, 'message' => 'La nueva contraseña y su confirmación no coinciden.']);
        exit;
    }

    // Podés agregar acá validaciones de fuerza de contraseña (longitud, etc.)
    $nuevoPass = md5($passNueva);
}

// Revisar: este tipo de cosas deberían ir al abm creo
if ($nombre === '') {
    $nombre =$usuario->getUsnombre();
    if ($nombre === null) $nombre = '';
}
if ($email === '') {
    $email = $usuario->getUsmail();
    if ($email === null) $email = '';
}

$param = [
    'idusuario'       => $idusuario,
    'usnombre'        => $nombre,
    'uspass'          => $nuevoPass,
    'usmail'          => $email,
    'usdeshabilitado' => $deshabilitado
];

// 6) Ejecutar modificación
$ok = (new AbmUsuario())->modificacion($param);
if ($ok) {
    echo json_encode(['success' => true, 'message' => 'Perfil actualizado correctamente.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al actualizar el perfil.']);
}