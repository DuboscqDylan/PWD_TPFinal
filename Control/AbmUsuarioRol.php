<?php

class AbmUsuarioRol
{

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden
     * con los nombres de las variables instancias del objeto
     * @param array $param
     * @return UsuarioRol
     */ private function cargarObjeto($param)
    {
        $objUsuarioRol = null;

        if (isset($param['idusuario']) && isset($param['idrol'])) {

            $AbmUsuario = new AbmUsuario();
            $objUsuario = $AbmUsuario->buscar(["idusuario" => $param['idusuario']])[0];

            $AbmRol = new AbmRol();
            $objRol = $AbmRol->buscar(["idrol" => $param['idrol']])[0];

            $objUsuarioRol = new UsuarioRol();
            $objUsuarioRol->cargarDatos($objUsuario, $objRol);
        }
        return $objUsuarioRol;
    }

    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves
     * @param array $param
     * @return boolean
     */
    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['usuario'], $param['rol'])) {
            $resp = true;
        }
        return $resp;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden
     * con los nombres de las variables instancias del objeto que son claves
     * @param array $param
     * @return UsuarioRol
     */
    public function cargarObjetoConClave($param)
    {
        $obj = null;
        if ($this->seteadosCamposClaves($param)) {
            $obj = new UsuarioRol();
            $obj->cargarDatos($param['usuario'], $param['rol']);
        }
        return $obj;
    }

    /**
     * Inserta un UsuarioRol a la BD con atributos del arreglo ingresado
     * @param array $param
     * @return boolean
     */
    public function alta($param)
    {
        $resp = false;
        $obj = $this->cargarObjeto($param);
        if ($obj != null && $obj->insertar()) {
            $resp = true;
        }
        return $resp;
    }

    /**
     * Elimina un UsuarioRol de la BD con atributos del arreglo ingresado
     * @param array $param
     * @return boolean
     */
    public function baja($param)
    {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $obj = $this->cargarObjetoConClave($param);
            if ($obj != null && $obj->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * modifica un UsuarioRol de la BD con atributos del arreglo ingresado
     * @param array $param
     * @return boolean
     */
    public function modificacion($param)
    {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $obj = $this->cargarObjeto($param);
            if ($obj != null && $obj->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * Busca un UsuarioRol en la BD
     * Si $param es vacío, trae todos los UsuarioRol
     * @param array $param
     * @return array
     */
    public function buscar($param = null)
    {
        $where = " true ";
        if ($param != null) {
            if (isset($param['usuario'])) {
                $where .= " and idusuario = " . $param['usuario']->getIdusuario();
            }
            if (isset($param['rol'])) {
                $where .= " and idrol = " . $param['rol']->getIdrol();
            }
        }
        $arreglo = (new UsuarioRol())->listar($where);
        return $arreglo;
    }

public function listarUsuariosFormateados($param = [])
{
    // Si viene un ID → listar solo ese
    if (isset($param['idusuario'])) {
        return $this->listarUsuarioFormateadoPorID($param['idusuario']);
    }

    // Si NO viene ID → listar todos (NO se rompe nada)
    $usuarios = (new Usuario())->listar("true");
    $salida = [];

    foreach ($usuarios as $usuario) {

        $usuarioRol = (new AbmUsuarioRol())->buscar(['usuario' => $usuario]);
        $rol = count($usuarioRol) > 0
            ? $usuarioRol[0]->getObjRol()->getRodescripcion()
            : "Sin rol";

        $salida[] = [
            'idusuario' => $usuario->getIdusuario(),
            'usnombre' => $usuario->getUsnombre(),
            'usmail' => $usuario->getUsmail(),
            'usdeshabilitado' => $usuario->getUsdeshabilitado(),
            'rol' => $rol
        ];
    }

    return $salida;
}


public function listarUsuarioFormateadoPorID($idusuario)
{
    if (!$idusuario) return [];

    $usuarios = (new Usuario())->listar("idusuario = " . intval($idusuario));
    if (empty($usuarios)) return [];

    $usuario = $usuarios[0];

    // Obtener el rol del usuario
    $usuarioRol = $this->buscar(['usuario' => $usuario]);
    $rol = count($usuarioRol) > 0
        ? $usuarioRol[0]->getObjRol()->getRodescripcion()
        : "Sin rol";

    return [[
        'idusuario' => $usuario->getIdusuario(),
        'usnombre' => $usuario->getUsnombre(),
        'usmail' => $usuario->getUsmail(),
        'usdeshabilitado' => $usuario->getUsdeshabilitado(),
        'rol' => $rol
    ]];
}


}
