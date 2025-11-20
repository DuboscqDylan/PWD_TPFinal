<?php

class AbmUsuario
{

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
     * @param array $param
     * @return Usuario
     */

    private function cargarObjeto($param)
    {
        $obj = null;
        if (array_key_exists('usnombre', $param) && array_key_exists('uspass', $param) && array_key_exists('usmail', $param)) {
            // Solo asignamos 'idusuario' si está definido y es distinto de null
            $idusuario = array_key_exists('idusuario', $param) ? $param['idusuario'] : null;
            $usdeshabilitado = array_key_exists('usdeshabilitado', $param) ? $param['usdeshabilitado'] : null;

            $obj = new Usuario();
            $obj->cargarDatos($idusuario, $param['usnombre'], $param['uspass'], $param['usmail'], $usdeshabilitado);
        }
        return $obj;
    }


    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto que son claves
     * @param array $param
     * @return Usuario
     */
    private function cargarObjetoConClave($param)
    {
        $obj = null;
        if ($this->seteadosCamposClaves($param)) {
            $obj = new Usuario();
            $obj->cargarDatos($param['idusuario']);
        }
        return $obj;
    }

    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves
     * @param array $param
     * @return boolean
     */
    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['idusuario'])) {
            $resp = true;
        }
        return $resp;
    }

    /**
     * Inserta un usuario a la BD con atributos del arreglo ingresado
     * @param array $param
     * @return boolean
     */
    public function alta($param)
    {
        $resp = false;
        if (!array_key_exists('idusuario', $param)) {
            $usuario = $this->cargarObjeto($param);
            if ($usuario != null && $usuario->insertar()) {
                $resp = true;
            }
        }
        return $resp;
    }


    /**
     * Pone la fecha de deshabilitado del usuario
     * @param array $param
     * @return boolean
     */
    public function baja($param)
    {
        $resp = false;

        if ($this->seteadosCamposClaves($param)) {

            // Obtener OBJETO COMPLETO del usuario
            $usuarioArr = $this->buscar(['idusuario' => $param['idusuario']]);
            if (count($usuarioArr) === 0) {
                return false;
            }

            $usuario = $usuarioArr[0];

            // Setear fecha de deshabilitación
            $usuario->setUsdeshabilitado(date("Y-m-d H:i:s"));

            // Modificar usuario en la BD sin borrar datos
            if ($usuario->modificar()) {
                $resp = true;
            }
        }

        return $resp;
    }
    /**
     * Se encarga de habilitar un usuario y retorna un arreglo con el mensaje de confirmación
     * @param array $param ['idusuario]
     */
    public function habilitarUsuario($param = null)
    {

        $respuesta = [];
        if (isset($param['idusuario'])) {
            // buscar el usuario
            $usuarios = (new AbmUsuario())->buscar(['idusuario' => $param['idusuario']]);

            if (!empty($usuarios)) {
                $usuario = $usuarios[0];
                $param['idusuario'] = $usuario->getIdusuario();
                $param['usnombre'] = $usuario->getUsnombre();
                $param['uspass'] = $usuario->getUspass();
                $param['usmail'] = $usuario->getUsmail();
                $param['usdeshabilitado'] = null;

                $modificacion = (new AbmUsuario())->modificacion($param);

                if ($modificacion) {
                    $respuesta = ['success' => true, 'message' => 'Usuario habilitado exitosamente.'];
                } else {
                    $respuesta = ['success' => false, 'message' => 'Error al deshabilitar el usuario.'];
                }
            } else {
                $respuesta = ['success' => false, 'message' => 'Usuario no encontrado.'];
            }
        } else {
            $respuesta = ['success' => false, 'message' => 'Datos incompletos.'];
        }
        return $respuesta;
    }


    /**
     * Modificar un usuario de la BD
     * @param array $param
     * @return boolean
     */
    public function modificacion($param)
    {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $usuario = $this->cargarObjeto($param);
            if ($usuario != null and $usuario->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * Realiza el alta de un usuario a la bd
     * @param array $param ['usnombre'=> $nombre, 'uspass'=> $pass , 'usmail'=> $mail, 'idrol'=> $idrol]
     */
    public function registrarUsuario($param)
    {
        // Verifica rol existente
        $roles = (new AbmRol())->buscar(['idrol' => intval($param['idrol'])]);
        $exito = !empty($roles);
        if ($exito) {
            //Verifica datos de entrada completos
            $exito = isset($param['usnombre']) && isset($param['uspass']) && isset($param['usmail']);
            if ($exito) {
                //Verifica que usnombre no se repita
                $usuarios = $this->buscar(['usnombre' => $param['usnombre']]);
                $exito = empty($usuarios);
                if ($exito) {
                    //Verifica que usmail no se repita
                    $usuarios = $this->buscar(['usmail' => $param['usmail']]);
                    $exito = empty($usuarios);
                    if ($exito) {
                        //Intenta dar de alta el usuario
                        $exito = $this->alta($param);
                        if ($exito) {
                            //Obtiene usuario dado de alta para darle rol
                            $usuario = $this->buscar(['usnombre' => $param['usnombre']])[0];
                            //Intenta asignar rol
                            $exito = (new AbmUsuarioRol())->alta(['usuario' => $usuario, 'rol' => $roles[0]]);
                            if ($exito) {
                                $respuesta = 'success';
                            } else {
                                $respuesta = 'Error en la asignación del rol al usuario.';
                            }
                        } else {
                            $respuesta = 'Error al registrar el usuario.';
                        }
                    } else {
                        $respuesta = 'Este email ya se encuentra en uso.';
                    }
                } else {
                    $respuesta = 'Este nombre usuario ya se encuentra en uso.';
                }
            } else {
                $respuesta = 'Datos incompletos.';
            }
        } else {
            $respuesta = 'Idrol no existe.';
        }
        return $respuesta;
    }

    /**
     * Busca usuarios en la BD
     * si $param == null, trae todos los usuarios
     * @param array $param
     * @return array
     */
    public function buscar($param = null)
    {
        $where = " true ";
        if ($param != null) {
            if (isset($param['idusuario'])) {
                $where .= " AND idusuario = " . $param['idusuario'];
            }
            if (isset($param['usnombre'])) {
                $where .= " AND usnombre = '" . $param['usnombre'] . "'";
            }
            if (isset($param['uspass'])) {
                $where .= " AND uspass = '" . $param['uspass'] . "'";
            }
            if (isset($param['usmail'])) {
                $where .= " AND usmail = '" . $param['usmail'] . "'";
            }
            if (array_key_exists('usdeshabilitado', $param)) {
                if (is_null($param['usdeshabilitado'])) {
                    $where .= " AND usdeshabilitado IS NULL";
                } else {
                    $where .= " AND usdeshabilitado = '" . $param['usdeshabilitado'] . "'";
                }
            }

            // echo "WHERE generado: " . $where . "<br>";
            $arreglo = (new Usuario())->listar($where);
            return $arreglo;
        }
    }
    public function actualizarPerfil($idusuario, $data)
    {
        $resp = ['success' => false, 'message' => ''];

        $usuarios = $this->buscar(['idusuario' => $idusuario]);

        if (!is_array($usuarios) || count($usuarios) == 0) {
            $resp['message'] = 'Usuario no encontrado.';
            return $resp;
        }

        $usuario = $usuarios[0];

        $nombre        = $data['nombre'] ?? $usuario->getUsnombre();
        $email         = $data['email'] ?? $usuario->getUsmail();
        $passActual    = $data['passActual'] ?? '';
        $passNueva     = $data['passNueva'] ?? '';
        $passConfirm   = $data['passConfirm'] ?? '';
        $passBD        = $usuario->getUspass();
        $deshabilitado = $usuario->getUsdeshabilitado();

        if ($passBD === null || $passBD != $passActual) {
            return ['success' => false, 'message' => 'Error, contraseña actual inválida.'];
        }

        $nuevoPass = $passBD;

        if ($passActual !== '' || $passNueva !== '' || $passConfirm !== '') {

            if ($passActual === '' || $passNueva === '' || $passConfirm === '') {
                return ['success' => false, 'message' => 'Complete todos los campos de contraseña.'];
            }

            if (md5($passActual) !== $passBD) {
                return ['success' => false, 'message' => 'La contraseña actual es incorrecta.'];
            }

            if ($passNueva !== $passConfirm) {
                return ['success' => false, 'message' => 'Las nuevas contraseñas no coinciden.'];
            }

            $nuevoPass = md5($passNueva);
        }

        /* ---- Armar parámetros ---- */
        $param = [
            'idusuario'       => $idusuario,
            'usnombre'        => $nombre,
            'uspass'          => $nuevoPass,
            'usmail'          => $email,
            'usdeshabilitado' => $deshabilitado
        ];

        /* ---- Ejecutar modificación ---- */
        $ok = $this->modificacion($param);

        if ($ok) {
            return ['success' => true, 'message' => 'Perfil actualizado correctamente.'];
        } else {
            return ['success' => false, 'message' => 'Error al actualizar el perfil.'];
        }
    }

    public function crearUsuarioConRol($param)
    {
        $resp = ["success" => false, "message" => "Error desconocido"];

        if (!isset($param['user'], $param['email'], $param['password'], $param['rol'])) {
            return ["success" => false, "message" => "Faltan datos obligatorios"];
        }

        $abmUsuarioRol = new AbmUsuarioRol();
        $abmRol = new AbmRol();

        if ($this->buscar(['usmail' => $param['email']])) {
            return ["success" => false, "message" => "El email ya está registrado"];
        }

        $rolObj = $abmRol->buscar(['idrol' => $param['rol']]);
        if (!$rolObj) {
            return ["success" => false, "message" => "Rol inválido"];
        }

        $usuarioData = [
            "usnombre" => $param['user'],
            "usmail" => $param['email'],
            "uspass" => $param['password'],
            "usdeshabilitado" => null
        ];

        $altaUsuario = $this->alta($usuarioData);

        if (!$altaUsuario) {
            return ["success" => false, "message" => "Error al crear el usuario"];
        }

        $nuevo = $this->buscar(['usmail' => $param['email']]);
        if (!$nuevo) {
            return ["success" => false, "message" => "No se pudo obtener el ID del usuario"];
        }

        $idUsuario = $nuevo[0]->getIdUsuario();
        $idRol = $rolObj[0]->getIdRol();

        $okRol = $abmUsuarioRol->alta([
            "idusuario" => $idUsuario,
            "idrol" => $idRol
        ]);

        if (!$okRol) {
            return ["success" => false, "message" => "Usuario creado, pero no se pudo asignar el rol"];
        }

        return ["success" => true, "message" => "Usuario creado exitosamente"];
    }

    public function darDeBajaConRol($param)
    {
        if (!isset($param['idusuario'])) {
            return ["success" => false, "message" => "Falta el ID del usuario"];
        }

        $idUsuario = $param['idusuario'];

        $usuario = $this->buscar(['idusuario' => $idUsuario]);

        if (!$usuario) {
            return ["success" => false, "message" => "Usuario no encontrado"];
        }

        $resp = $this->baja(['idusuario' => $idUsuario]);

        if ($resp) {
            return ["success" => true, "message" => "Usuario dado de baja correctamente."];
        }

        return ["success" => false, "message" => "Error al dar de baja el usuario."];
    }

    public function modificarUsuarioCompleto($data)
{
    if (!isset($data['usuarioID'])) {
        return ['success' => false, 'message' => 'Datos incompletos.'];
    }

    $usuarios = $this->buscar(['idusuario' => $data['usuarioID']]);
    if (empty($usuarios)) {
        return ['success' => false, 'message' => 'Usuario no encontrado.'];
    }

    $usuario = $usuarios[0];

    $param = [
        'idusuario' => $usuario->getIdusuario(),
        'usnombre'  => $data['modNombre'] ?? $usuario->getUsnombre(),
        'usmail'    => $data['modEmail'] ?? $usuario->getUsmail(),
        'uspass'    => $data['modPass'] ?? $usuario->getUspass(),
        'usdeshabilitado' => $data['modUsdeshabilitado'] ?? $usuario->getUsdeshabilitado(),
    ];

    $subaRol = true;
    $bajaRol = true;

    if (isset($data['modRol'])) {

        $abmRol = new AbmRol();
        $abmUsuarioRol = new AbmUsuarioRol();

        $roles = $abmRol->buscar(['rodescripcion' => $data['modRol']]);
        if (empty($roles)) {
            return ['success' => false, 'message' => 'Rol inválido.'];
        }

        $rolNuevo = $roles[0];

        $usuarioRoles = $abmUsuarioRol->buscar(['usuario' => $usuario]);
        if (!empty($usuarioRoles)) {
            $bajaRol = $abmUsuarioRol->baja([
                'usuario' => $usuario,
                'rol' => $usuarioRoles[0]->getObjRol()
            ]);
        }
        $subaRol = $abmUsuarioRol->alta([
            'usuario' => $usuario,
            'rol' => $rolNuevo
        ]);
    }
    $modificacion = $this->modificacion($param);

    if ($modificacion && $subaRol && $bajaRol) {
        return ['success' => true, 'message' => 'Usuario modificado exitosamente.'];
    }

    return [
        'success' => false,
        'message' => 'Error al modificar el usuario.',
        'data' => $param
    ];
}
}
