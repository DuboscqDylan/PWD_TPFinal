<?php

class AbmMenuRol
{

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden 
     * con los nombres de las variables instancias del objeto
     * @param array $param
     * @return MenuRol
     */
    private function cargarObjeto($param)
    {
        $obj = null;
        if (array_key_exists('rol', $param) and array_key_exists('menu', $param)) {
            $obj = new MenuRol();
            $obj->cargarDatos($param['rol'], $param['menu']);
        }
        return $obj;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden 
     * con los nombres de las variables instancias del objeto que son claves
     * @param array $param
     * @return MenuRol
     */

    // ¿no es redundante con cargarObjeto siendo que las dos son primarias y no hay otras claves?
    private function cargarObjetoConClave($param)
    {
        $obj = null;
        if ($this->seteadosCamposClaves($param)) {
            $obj = new MenuRol();
            $obj->cargarDatos($param['rol'], $param['menu']);
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
        if (isset($param['rol'], $param['menu'])) {
            $resp = true;
        }
        return $resp;
    }

    /**
     * Inserta un MenuRol a la BD con atributos del arreglo ingresado
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
     * Elimina un MenuRol de la BD
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
     * Modifica un MenuRol de la BD
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
     * Busca MenuRol en la BD
     * Si $param es vacío, trae todos los MenuRol
     * @param array $param
     * @return array
     */
    public function buscar($param = null)
    {
        $where = " true ";
        if ($param != null) {
            if (isset($param['rol'])) {
                $where .= " AND idrol = " . $param['rol']->getIdrol();
            }
            if (isset($param['menu'])) {
                $where .= " AND idmenu = " . $param['menu']->getIdmenu();
            }
        }
        $arreglo = (new MenuRol())->listar($where);
        return $arreglo;
    }
}
