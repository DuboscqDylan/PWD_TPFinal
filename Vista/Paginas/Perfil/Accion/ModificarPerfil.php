<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD_TPFINAL/configuracion.php";

/** ---------------------------------------------
 *  Inicializar objeto sesión
 *  (NO llamar iniciar(), tu sistema lo usa solo para login)
 * --------------------------------------------- */
$sesion = new Session();

/* Verificar sesión activa */
if (!$sesion->activa()) {
    echo json_encode(['success' => false, 'message' => 'Sesión no iniciada.']);
    exit;
}

$data = data_submitted();

$idusuario = $sesion->getUsuario()->getIdusuario();
$nombre     = $data['nombre'] ?? '';
$email      = $data['email'] ?? '';
$passActual = $data['passActual'] ?? '';
$passNueva  = $data['passNueva'] ?? '';
$passConfirm= $data['passConfirm'] ?? '';

$usuarios = (new AbmUsuario())->buscar(['idusuario' => $idusuario]);

if (!is_array($usuarios) || count($usuarios) == 0) {
    echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.']);
    exit;
}

$usuario = $usuarios[0];
$passBD = $usuario->getUspass(); 
$deshabilitado = $usuario->getUsdeshabilitado();

/* -------- Validar si falla el getter -------- */
if ($passBD === null) {
    echo json_encode(['success' => false, 'message' => 'No se pudo obtener la contraseña actual.']);
    exit;
}

/* -------- Cambio de contraseña (opcional) -------- */
$nuevoPass = $passBD;

if ($passActual !== '' || $passNueva !== '' || $passConfirm !== '') {

    if ($passActual === '' || $passNueva === '' || $passConfirm === '') {
        echo json_encode(['success' => false, 'message' => 'Complete todos los campos de contraseña.']);
        exit;
    }

    if (md5($passActual) !== $passBD) {
        echo json_encode(['success' => false, 'message' => 'La contraseña actual es incorrecta.']);
        exit;
    }

    if ($passNueva !== $passConfirm) {
        echo json_encode(['success' => false, 'message' => 'La nueva contraseña no coincide.']);
        exit;
    }

    $nuevoPass = md5($passNueva);
}

/* -------- Si nombre y email vienen vacíos, conservar -------- */
if ($nombre === '') $nombre = $usuario->getUsnombre();
if ($email === '') $email = $usuario->getUsmail();

/* -------- Armar parámetros -------- */
$param = [
    'idusuario'       => $idusuario,
    'usnombre'        => $nombre,
    'uspass'          => $nuevoPass,
    'usmail'          => $email,
    'usdeshabilitado' => $deshabilitado
];

/* -------- Guardar -------- */
$ok = (new AbmUsuario())->modificacion($param);

if ($ok) {
    echo json_encode(['success' => true, 'message' => 'Perfil actualizado correctamente.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al actualizar el perfil.']);
}
