<?php
include_once $_SERVER['DOCUMENT_ROOT']."/PWD_TPFINAL/configuracion.php";

$data = data_submitted(); 
$salida = [];

$usuarioRoles = (new AbmUsuarioRol())->buscar($data);

if (!empty($usuarioRoles)) {
    foreach ($usuarioRoles as $cadaUsuarioRol) {
        $nuevoElem['idusuario'] = $cadaUsuarioRol->getObjUsuario()->getIdusuario();
        $nuevoElem['usnombre'] = $cadaUsuarioRol->getObjUsuario()->getUsnombre();
        $nuevoElem['usmail'] = $cadaUsuarioRol->getObjUsuario()->getUsmail();
        $nuevoElem['usdeshabilitado'] = $cadaUsuarioRol->getObjUsuario()->getUsdeshabilitado(); // NULL = Habilitado, fecha = Deshabilitado
        $nuevoElem['rol'] = $cadaUsuarioRol->getObjRol()->getRodescripcion(); 
        array_push($salida, $nuevoElem);
    }
}
echo json_encode($salida);
?>
