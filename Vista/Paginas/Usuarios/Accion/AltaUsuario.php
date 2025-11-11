<?php
include_once "../../../../configuracion.php";

$data = Funciones::data_submitted();
$abmUsuario = new ABMUsuario();
$abmUsuarioRol = new ABMUsuarioRol();
$abmRol = new ABMRol();

if (isset($data['user']) && isset($data['password']) && isset($data['email']) && isset($data['rol'])) {
    $param['usnombre'] = $data['user'];
    $param['usmail'] = $data['email'];
    $param['uspass'] = $data['password'];

    $resultadoUsuario = $abmUsuario->buscar(['usnombre' => $data['user']]);
    if (count($resultadoUsuario) > 0) {
        echo 'Este usuario ya se encuentra en uso';
        exit; 
    }
    $resultadoEmail = $abmUsuario->buscar(['usmail' => $data['email']]);
    if (count($resultadoEmail) > 0) {
        echo 'Este email ya se encuentra en uso';
        exit; 
    }

    $alta = $abmUsuario->alta($param);
    if ($alta) {
        $usuario = $abmUsuario->buscar(['usnombre' => $data['user']])[0]; // recuperamos el array con el obj recien creado
        $rol = $abmRol->buscar(['rodescripcion' => $data['rol']]); // recuperamos el array con el obj del rol seleccionado
        $altaUsuarioRol = $abmUsuarioRol->alta(['usuario' => $usuario, 'rol' => $rol[0]]); // pasamos el obj
        if ($altaUsuarioRol) {
            echo 'success';
        } else {
            echo 'Error en la asignación del rol al usuario';
        }
        
    } else {
        echo 'Error al registrar el usuario';
    }
} else {
    echo 'Datos incompletos';
}
?>
