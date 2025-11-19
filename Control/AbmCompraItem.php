<?php

class AbmCompraItem
{

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden 
     * con los nombres de las variables instancias del objeto
     * @param array $param
     * @return CompraItem
     */
    private function cargarObjeto($param)
    {
        $obj = null;
        if (array_key_exists('producto', $param) and array_key_exists('compra', $param) and array_key_exists('cicantidad', $param)) {
            // Solo asignamos 'idcompraitem' si está definido y es distinto de null
            $idcompraitem = array_key_exists('idcompraitem', $param) ? $param['idcompraitem'] : null;
            $obj = new CompraItem();
            $obj->cargarDatos($idcompraitem, $param['producto'], $param['compra'], $param['cicantidad']);
        }
        return $obj;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden 
     * con los nombres de las variables instancias del objeto que son claves
     * @param array $param
     * @return CompraItem
     */
    private function cargarObjetoConClave($param)
    {
        $obj = null;
        if ($this->seteadosCamposClaves($param)) {
            $obj = new CompraItem();
            $obj->cargarDatos($param['idcompraitem']);
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
        if (isset($param['idcompraitem'])) {
            $resp = true;
        }
        return $resp;
    }

    /**
     * Inserta un compraItem a la BD con atributos del arreglo ingresado
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
     * Elimina un compraItem de la BD
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
     * Modifica un compraItem de la BD
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
     * Busca compraItem en la BD
     * Si $param es vacío, trae todos los compraItem
     * @param array $param
     * @return array
     */
    public function buscar($param = null)
    {
        $where = " true ";
        if ($param != null) {
            if (isset($param['idcompraitem'])) {
                $where .= " AND idcompraitem = " . $param['idcompraitem'];
            }
            if (isset($param['producto'])) {
                $where .= " AND idproducto = " . $param['producto']->getIdproducto();
            }
            if (isset($param['compra'])) {
                $where .= " AND idcompra = " . $param['compra']->getIdcompra();
            }
            if (isset($param['cicantidad'])) {
                $where .= " AND cicantidad = " . $param['cicantidad'];
            }
        }
        $arreglo = (new CompraItem())->listar($where);
        return $arreglo;
    }

    /**
     * Agrega CompraItem a la bd segun parametros
     */
    public function agregarCompraItem($param)
    {
        $idproducto = intval($param['idproducto']);
        $idcompra = intval($param['idcompra']);
        $cicantidad = intval($param['cicantidad']);

        $producto = (new AbmProducto)->buscar(['idproducto' => $idproducto])[0]; // Busca producto (por id producto ingresado)

        $exito = $producto->getProcantstock() >= $cicantidad; // Verifica que haya suficiente stock
        if ($exito) {
            $nuevoStock = ($producto->getProcantstock() - $cicantidad);
            $producto->setProcantstock($nuevoStock);
            $producto->modificar(); // Disminuye stock del producto agregado

            $compra = (new AbmCompra)->buscar(['idcompra' => $idcompra])[0]; // Busca compra (por id compra ingresado)
            $compraItems = $this->buscar(['producto' => $producto, 'compra' => $compra]); // Verificar que el carrito posee previamente el producto 

            if (empty($compraItems)) {  //Si dicho producto no esta en el carro, agrega CompraItem
                $exito = $this->alta(['producto' => $producto, 'compra' => $compra, 'cicantidad' => $cicantidad]);
                $msj = "Producto agregado al carro.";
            } else { //Si dicho producto ya esta en el carro, modifica CompraItem
                $compraItem = $compraItems[0];
                $nuevaCicantidad = ($compraItem->getCicantidad() + $cicantidad);
                $exito = $this->modificacion(['idcompraitem' => $compraItem->getIdcompraitem(), 'producto' => $producto, 'compra' => $compra, 'cicantidad' => $nuevaCicantidad]);
                $msj = "Se aumento cantidad del producto al carro.";
            }
        } else {
            $msj = "No hay stock suficiente.";
        }
        return ['success' => $exito, 'message' => $msj];
    }

    /**
     * Quita CompraItem a la bd segun parametros
     */
    public function quitarCompraItem($param)
    {
        $idproducto = intval($param['idproducto']);
        $idcompra = intval($param['idcompra']);
        $cicantidad = intval($param['cicantidad']);

        $producto = (new AbmProducto)->buscar(['idproducto' => $idproducto])[0]; // Busca producto (por id producto ingresado)

        $compra = (new AbmCompra)->buscar(['idcompra' => $idcompra])[0]; // Busca compra (por id compra ingresado)
        $compraItems = $this->buscar(['producto' => $producto, 'compra' => $compra]); // Busca CompraItem a modificar

        $exito = !empty($compraItems);
        if ($exito) { //Solo aumenta stock si antes estaba en el carro
            $nuevoStock = ($producto->getProcantstock() + $cicantidad);
            $producto->setProcantstock($nuevoStock);
            $producto->modificar(); // Disminuye stock del producto agregado

            $compraItem = $compraItems[0];
            $nuevaCicantidad = ($compraItem->getCicantidad() - $cicantidad);
            if ($nuevaCicantidad > 0) { // Si al disminuir cicantidad es mayor a 0 (sigue estando item en carro)
                $exito = $this->modificacion(['idcompraitem' => $compraItem->getIdcompraitem(), 'producto' => $producto, 'compra' => $compra, 'cicantidad' => $nuevaCicantidad]);
                $msj = "Se disminuyo cantidad del producto.";
            } else {
                $exito = $compraItem->eliminar();
                $msj = "Item eliminado del carrito.";
            }
        } else {
            $msj = "Item no se encuentra en el carrito";
        }

        return ['success' => $exito, 'message' => $msj];
    }


    /**
     * Quita todo compraItem para un idcompra dado (reestockea)
     */
    public function vaciarCarrito($param)
    {
        $compra = (new AbmCompra())->buscar($param)[0]; //Busco compra con idcompra en $param
        $compraItems = $this->buscar(['compra' => $compra]); //Obtiene todo compraItem de dicha compra

        $msj = 'Carrito vaciado con exito.';
        $exito = true;
        if (!empty($compraItems)) {
            $respuestas = [];
            foreach ($compraItems as $compraItem) {
                $idprod = $compraItem->getObjProducto()->getIdproducto();
                $idcomp = $compraItem->getObjCompra()->getIdcompra();
                $cicant = $compraItem->getCicantidad();
                $rta = $this->quitarCompraItem(['idproducto' => $idprod, 'idcompra' => $idcomp, 'cicantidad' => $cicant]); //Quita toda cicant
                array_push($respuestas, $rta);
            }

            $i = 0;
            while ($exito && $i < count($respuestas)) {
                $exito = $respuestas[$i]['success'];
                if (!$exito) {
                    $msj = "Hubo un problema al eliminar un item del carro.";
                }
                $i++;
            }
        } else {
            $msj = 'No hay items en el carrito.';
        }
        return ['success' => $exito, 'message' => $msj];
    }
}
