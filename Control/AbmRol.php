<?php 

class AbmRol {
    /**
     * Espera como parametro un arreglo asociativo donde 
     * las claves coinciden con los nombres de las variables instancias del objeto
     * @param array $param
     * @return Rol
     */
    private function cargarObjeto($param) {
        $obj = null;
        if (array_key_exists('rodescripcion', $param)) {
            // Solo asignamos 'idrol' si está definido y es distinto de null
            $idrol = array_key_exists('idrol', $param) ? $param['idrol'] : null;
            $obj = new Rol();
            $obj->cargarDatos($idrol, $param['rodescripcion']);
        }
        return $obj;
    }

    /**
     * Espera como parametro un arreglo asociativo donde 
     * las claves coinciden con los nombres de las variables instancias del objeto que son claves
     * @param array $param
     * @return Rol
     */
    private function cargarObjetoConClave($param) {
        $obj = null;
        if ($this->seteadosCamposClaves($param)) {
            $obj = new Rol();
            $obj->cargarDatos($param['idrol']);
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
        if (isset($param['idrol'])) {
            $resp = true;
        }
        return $resp;
    }

    /**
     * Inserta un rol a la BD con atributos del arreglo ingresado
     * @param array $param
     * @return boolean
     */
    public function alta($param) {
        $resp = false;
        $obj = $this->cargarObjeto($param);
        if ($obj != null && $obj->insertar()) {
            $resp = true;
        }
        return $resp;
    }

    /**
     * Elimina un rol de la BD
     * @param array $param
     * @return boolean
     */
    public function baja($param) {
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
     * Modifica un rol de la BD
     * @param array $param
     * @return boolean
     */
    public function modificacion($param) {
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
     * Busca roles en la BD
     * Si $param es vacío, trae todos los roles
     * @param array $param
     * @return array
     */
    public function buscar ($param = null) {
        $where = " true ";
        if ($param != null) {
            if (isset($param['idrol'])) {
                $where .= " AND idrol = ".$param['idrol'];
            }
            if (isset($param['rodescripcion'])) {
                $where .= " AND rodescripcion = '".$param['rodescripcion']."'";
            }
        }
        $arreglo = (new Rol())->listar($where);
        return $arreglo;
    }
}
?>