<?php 

class AbmUsuario {
    
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
     * @param array $param
     * @return Usuario
     */

     private function cargarObjeto($param) {
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
    private function cargarObjetoConClave($param) {
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
    private function seteadosCamposClaves($param) {
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
    public function alta($param) {
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
    public function baja($param) {
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
     * Modificar un usuario de la BD
     * @param array $param
     * @return boolean
     */
    public function modificacion($param) {
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
    public function registrarUsuario($param) {
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
                } else{
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
    public function buscar($param = null) {
        $where = " true ";
        if ($param != null) {
            if (isset($param['idusuario'])) {
                $where .= " AND idusuario = ".$param['idusuario'];
            }
            if (isset($param['usnombre'])) {
                $where .= " AND usnombre = '".$param['usnombre']."'"; 
            }
            if (isset($param['uspass'])) {
                $where .= " AND uspass = '".$param['uspass']."'"; 
            }
            if (isset($param['usmail'])) {
                $where .= " AND usmail = '".$param['usmail']."'"; 
            }
            if (isset($param['usdeshabilitado'])) {
                if (is_null($param['usdeshabilitado'])) {
                    $where .= " AND usdeshabilitado IS NULL";
                } else {
                    $where .= " AND usdeshabilitado = '".$param['usdeshabilitado']."'";
                } 
            }
            //echo "WHERE generado: " . $where . "<br>";
        $arreglo = (new Usuario())->listar($where);
        return $arreglo;
    }
}
}