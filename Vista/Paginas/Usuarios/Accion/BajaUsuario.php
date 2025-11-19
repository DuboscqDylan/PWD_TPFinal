<?php
include_once $_SERVER['DOCUMENT_ROOT']."/PWD_TPFINAL/configuracion.php";

$data = data_submitted();

if (isset($data['idusuario']) && isset($data['rol'])) {
    $idUsuario = $data['idusuario'];
    $rolDescripcion = $data['rol'];

    $abmUsuario = new AbmUsuario();
    $abmUsuarioRol = new AbmUsuarioRol();
    $abmRol = new AbmRol();

    $usuario = $abmUsuario->buscar(['idusuario' => $idUsuario]);
    $usuarioObj = $usuario[0]; 

    $rol = $abmRol->buscar(['rodescripcion' => $rolDescripcion]);
    $rolObj = $rol[0]; 

    $bajaUsuario = $abmUsuario->baja(['idusuario' => $idUsuario]); 

    if ($bajaUsuario) {
        echo json_encode(['success' => true, 'message' => 'Usuario dado de baja correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al dar de baja el usuario.']);
    }
}
?>
