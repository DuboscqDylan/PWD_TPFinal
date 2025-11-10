<?php

class AbmCompraEstadoTipo {
    
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden 
     * con los nombres de las variables instancias del objeto
     * @param array $param
     * @return CompraEstadoTipo
     */
    private function cargarObjeto($param) {
        $obj = null;
        if (array_key_exists('cetdescripcion', $param) AND array_key_exists('cetdetalle', $param)) {
            // Solo asignamos 'idcompraestadotipo' si está definido y es distinto de null
            $idcompraestadotipo = array_key_exists('idcompraestadotipo', $param) ? $param['idcompraestadotipo'] : null;
            $obj = new CompraEstadoTipo();
            $obj->cargarDatos($idcompraestadotipo, $param['cetdescripcion'], $param['cetdetalle']);
        }
        return $obj;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden 
     * con los nombres de las variables instancias del objeto que son claves
     * @param array $param
     * @return CompraEstadoTipo
     */
    private function cargarObjetoConClave($param) {
        $obj = null;
        if ($this->seteadosCamposClaves($param)) {
            $obj = new CompraEstadoTipo();
            $obj->cargarDatos($param['idcompraestadotipo']);
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
        if (isset($param['idcompraestadotipo'])) {
            $resp = true;
        }
        return $resp;
    }

    /**
     * Inserta un compraestadotipo a la BD con atributos del arreglo ingresado
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
     * Elimina un compraestadotipo de la BD
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
     * Modifica un compraestadotipo de la BD
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
     * Busca compraestadotipo en la BD
     * Si $param es vacío, trae todos los compraestadotipo
     * @param array $param
     * @return array
     */
    public function buscar ($param = null) {
        $where = " true ";
        if ($param != null) {
            if (isset($param['idcompraestadotipo'])) {
                $where .= " AND idcompraestadotipo = ".$param['idcompraestadotipo'];
            }
            if (isset($param['cetdescripcion'])) {
                $where .= " AND cetdescripcion = '".$param['cetdescripcion']."'";
            }
            if (isset($param['cetdetalle'])) {
                $where .= " AND cetdetalle = '".$param['cetdetalle']."'";
            }
        }
        $arreglo = (new CompraEstadoTipo())->listar($where);
        return $arreglo;
    }
}
?>