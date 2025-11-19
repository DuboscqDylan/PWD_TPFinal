<?php

class AbmCompraEstado
{
    //['objCompra' => $objCompra, 'objCompraEstadoTipo' => $objCompraEstadoTipo, 'cefechaini' => $cefechaini, 'cefechafin' => $cefechafin]
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden
     * con los nombres de las variables instancias del objeto
     * @param array $param
     * @return CompraEstado
     */

    private function cargarObjeto($param)
    {
        $obj = null;
        if (array_key_exists('objCompra', $param) && array_key_exists('objCompraEstadoTipo', $param) && array_key_exists('cefechaini', $param)) {
            $idcompraestado = array_key_exists('idcompraestado', $param) ? $param['idcompraestado'] : null;
            $cefechafin = array_key_exists('cefechafin', $param) ? $param['cefechafin'] : null;
            $obj = new CompraEstado();
            $obj->cargarDatos($idcompraestado, $param['objCompra'], $param['objCompraEstadoTipo'], $param['cefechaini'], $cefechafin);
        }
        return $obj;
    }

    /**
     * Corrrobora que dentro del arreglo asociativo esten seteados los campos claves
     * @param array $param
     * @return boolean
     */
    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['idcompraestado'])) {
            $resp = true;
        }
        return $resp;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden
     * con los nombres de las variables instancias del objeto que son claves
     * @param array $param
     * @return CompraEstado
     */
    private function cargarObjetoConClave($param)
    {
        $obj = null;
        if ($this->seteadosCamposClaves($param)) {
            $obj = new CompraEstado();
            $obj->cargarDatos($param['idcompraestado']);
        }
        return $obj;
    }

    /**
     * Inserta un compraestado a la BD con atributos del arreglo ingresado
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
     * Elimina un compraestado de la BD con atributos del arreglo ingresado
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
     * Modifica un compraestado de la BD con atributos del arreglo ingresado
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
     * Retorna un array con los compraItems (en forma de array) de un estadocompra especificado en $param
     * @param array $param ['idcompraestado']
     */
    public function listarCarrito($param)
    {
        $items = [];
        $compraEstado = (new AbmCompraEstado())->buscar($param)[0];
        $compraItems = (new AbmCompraItem())->buscar(['compra' => $compraEstado->getObjCompra()]);

        foreach ($compraItems as $compraItem) {
            $producto = $compraItem->getObjProducto();
            //$objCompra = $compraItem->getObjCompra(); // No se usa, pero puede ser obtenido
            $nuevoElem['idcompraitem'] = $compraItem->getIdcompraitem();
            $nuevoElem['cicantidad'] = $compraItem->getCicantidad();
            $nuevoElem['icon'] = BASE_URL . "/Vista/Media/Producto/" . $producto->getIdproducto() . "/icon.png";
            $nuevoElem['idproducto'] = $producto->getIdproducto();
            $nuevoElem['pronombre'] = $producto->getPronombre();
            $nuevoElem['prodetalle'] = $producto->getProdetalle();
            $nuevoElem['procantstock'] = $producto->getProcantstock();
            $nuevoElem['proprecio'] = $producto->getProprecio();
            $nuevoElem['prodeshabilitado'] = $producto->getProdeshabilitado();
            array_push($items, $nuevoElem);
        }
        return $items;
    }

    /**
     * Lista las compras estados del tipo ingresado.
     * Retorna un array de compras (en forma de array) que cumplan $param
     * @param array $param ['idcompraestadotipo'] (1: Iniciadas, 2: Aceptadas, 3: Enviadas, 4: Canceladas)
     */
    public function listarCompraEstados($param)
    {
        $respuesta = [];
        $objCompraEstadoTipo = (new AbmCompraEstadoTipo())->buscar(['idcompraestadotipo' => $param['idcompraestadotipo']])[0];
        $compras = (new AbmCompraEstado())->buscar(['objCompraEstadoTipo' => $objCompraEstadoTipo, 'cefechafin' => 'null']);
        foreach ($compras as $compra) {
            $nuevoElem['idcompra'] = $compra->getObjCompra()->getIdcompra();
            $nuevoElem['estado'] = $compra->getObjCompraEstadoTipo()->getCetdescripcion();
            $nuevoElem['cefechaini'] = $compra->getCefechaini();
            $nuevoElem['cefechafin'] = $compra->getCefechafin();
            $nuevoElem['usuario'] = $compra->getObjCompra()->getObjUsuario()->getUsnombre();
            $nuevoElem['idcompraestado'] = $compra->getIdcompraestado();
            array_push($respuesta, $nuevoElem);
        }
        return $respuesta;
    }

    /**
     * Busca un compraestado en la BD 
     * Si $param es vacio, trae todos los compraestados 
     * @param array $param
     * @return array
     */
    public function buscar($param = null)
    {
        $where = " true ";
        if ($param != null) {
            if (isset($param['idcompraestado'])) {
                $where .= " AND idcompraestado = " . $param['idcompraestado'];
            }
            if (isset($param['objCompra'])) {
                $where .= " AND idcompra = " . $param['objCompra']->getIdcompra();
            }
            if (isset($param['objCompraEstadoTipo'])) {
                $where .= " AND idcompraestadotipo = " . $param['objCompraEstadoTipo']->getIdcompraestadotipo();
            }
            if (isset($param['cefechaini'])) {
                if ($param['cefechaini'] == "null") {
                    $where .= " AND cefechaini IS NULL";
                } else {
                    $where .= " AND cefechaini = '" . $param['cefechaini'] . "'";
                }
            }
            if (isset($param['cefechafin'])) {
                if ($param['cefechafin'] == "notnull") {
                    $where .= " AND cefechafin IS NOT NULL";
                } else {
                    if ($param['cefechafin'] == "null") {
                        $where .= " AND cefechafin IS NULL";
                    } else {
                        $where .= " AND cefechafin = '" . $param['cefechaini'] . "'";
                    }
                }
            }
        }
        $arreglo = (new CompraEstado())->listar($where);
        return $arreglo;
    }
}
