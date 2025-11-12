<?php
include_once ROOT_PATH."/configuracion.php";
$data = Funciones::data_submitted();  // Obtener los datos enviados

// Verificar que todos los campos necesarios están presentes
if (isset($data['usuarioID'])) {
    // buscar el usuario
    $usuarios = (new AbmUsuario())->buscar(['idusuario' => $data['usuarioID']]);
    
    if (!empty($usuarios)) {
        $usuario = $usuarios[0];
        $param['idusuario'] = $usuario->getIdusuario();
        if (isset($data['modNombre'])) {
            $param['usnombre'] = $data['modNombre'];
        } else {
            $param['usnombre'] = $usuario->getUsnombre();
        }
        if (isset($data['modEmail'])) {
            $param['usmail'] = $data['modEmail'];
        } else {
            $param['usmail'] = $usuario->getUsmail();
        }
        if (isset($data['modPass'])) {
            $param['uspass'] = $data['modPass'];
        } else {
            $param['uspass'] = $usuario->getUspass();
        }
        if (isset($data['modUsdeshabilitado'])) {
            $param['usdeshabilitado'] = $data['modUsdeshabilitado'];
        } else {
            $param['usdeshabilitado'] = $usuario->getUsdeshabilitado();
        }

        $subaRol = true;
        $bajaRol = true;
        if (isset($data['modRol'])) {
            $roles = (new AbmRol())->buscar(['rodescripcion' => $data['modRol']]);
            $usuarioRoles = (new AbmUsuarioRol())->buscar(['usuario' => $usuario]);
            $bajaRol = (new AbmUsuarioRol())->baja(['usuario' => $usuario, 'rol' => $usuarioRoles[0]->getObjRol()]); //Baja rol anterior
            $subaRol = (new AbmUsuarioRol())->alta(['usuario' => $usuario, 'rol' => $roles[0]]); //Sube nuevo rol            
        }

        $modificacion = (new AbmUsuario())->modificacion($param); //Modifica otros datos
        
        if ($subaRol && $bajaRol) {
            echo json_encode(['success' => true, 'message' => 'usuario modificado exitosamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al modificar el usuario.', 'data'=> $param]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'usuario no encontrado.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
}
