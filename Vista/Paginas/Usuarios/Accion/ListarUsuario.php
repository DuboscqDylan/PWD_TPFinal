<?php
include_once $_SERVER['DOCUMENT_ROOT']."/PWD_TPFINAL/configuracion.php";

$data = Funciones::data_submitted(); 
$salida = [];

// Obtener los usuarios con su rol
$usuarioRoles = (new AbmUsuarioRol())->buscar($data);

if (!empty($usuarioRoles)) {
    foreach ($usuarioRoles as $cadaUsuarioRol) {
        // Obtener los detalles del usuario y su rol
        $nuevoElem['idusuario'] = $cadaUsuarioRol->getObjUsuario()->getIdusuario();
        $nuevoElem['usnombre'] = $cadaUsuarioRol->getObjUsuario()->getUsnombre();
        $nuevoElem['usmail'] = $cadaUsuarioRol->getObjUsuario()->getUsmail();
        $nuevoElem['usdeshabilitado'] = $cadaUsuarioRol->getObjUsuario()->getUsdeshabilitado(); // NULL = Habilitado, fecha = Deshabilitado
        $nuevoElem['rol'] = $cadaUsuarioRol->getObjRol()->getRodescripcion(); 
        array_push($salida, $nuevoElem);
    }
}
// Retornar los resultados en formato JSON
echo json_encode($salida);
?>
