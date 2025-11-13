<?php

Class AbmProducto
{
    private function cargarObjeto($param)
    {
        $obj = null;
        if (array_key_exists('proprecio', $param) && array_key_exists('pronombre', $param) && array_key_exists('prodetalle', $param) && array_key_exists('procantstock', $param)) {
            $idproducto = array_key_exists('idproducto', $param) ? $param['idproducto'] : null;
            $prodeshabilitado = array_key_exists('prodeshabilitado', $param) ? $param['prodeshabilitado'] : null;
            $obj = new Producto();
            $obj->cargarDatos($idproducto, $param['pronombre'], $param['prodetalle'], $param['procantstock'], $param['proprecio'], $prodeshabilitado);
        }
    
        return $obj;
    }
    
    private function cargarObjetoConClave($param) {
        $obj = null;

        if ($this->seteadosCamposClaves($param)) {
            $obj = new Producto();
            $obj->cargarDatos($param['idproducto']);
        }
        return $obj;
    }

    private function seteadosCamposClaves($param) {
        $resp = false;
        if (isset($param['idproducto'])) {
            $resp = true;
        }
        return $resp;
    }

    public function alta($param) {
        $resp = false;
        if (!array_key_exists('idproducto', $param)) {
            $producto = $this->cargarObjeto($param);
            if ($producto != null and $producto->insertar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function baja($param) {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $elObjtProducto = $this->cargarObjetoConClave($param);
            if ($elObjtProducto != null and $elObjtProducto->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function modificacion($param) {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $elObjtProducto = $this->cargarObjeto($param);
            if ($elObjtProducto != null and $elObjtProducto->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * Lista productos con su informacion necesaria para ser mostrados en Catalogo.
     * Retorna un array de productos (en forma de array) que cumplan $param
     * @param array $param ['idproducto', 'proprecio', 'pronombre', 'prodetalle', 'procantstock', 'deshabilitado'
     */
    public function listarProductos($param = null) {
        $respuesta = [];
        $productos = (new AbmProducto())->buscar($param); //Recupera productos
        foreach($productos as $producto) {
            if (!$producto->getProdeshabilitado()) { //Convierte en forma de array los que estan habilitados
                $prod['icon'] = BASE_URL."/View/Media/Product/".$producto->getIdproducto()."/icon.png";
                $prod['idproducto'] = $producto->getIdproducto();
                $prod['pronombre'] = $producto->getPronombre();
                $prod['prodetalle'] = $producto->getProdetalle();
                $prod['procantstock'] = $producto->getProcantstock();
                $prod['proprecio'] = $producto->getProprecio();
                $prod['prodeshabilitado'] = $producto->getProdeshabilitado();
                array_push($respuesta, $prod);
            }
        }
        return $respuesta;
    }

    /**
     * Se encarga de realizar el alta de un producto y retorna un arreglo con el mensaje de confirmación
     * @param array $param ['nombre], $param ['stock], $param ['detalle], $param ['precio]
     */
    public function altaProducto($param = null){
        $respuesta = [];
        if (isset($param['nombre']) && isset($param['stock']) && isset($param['detalle']) && isset($param['precio'])) {
            $param['pronombre'] = $param['nombre'];
            $param['prodetalle'] = $param['detalle'];
            $param['procantstock'] = $param['stock'];
            $param['proprecio'] = $param['precio'];
        
            if ((new ABMProducto())->alta($param)) {
                $respuesta = ['success' => true, 'message' => 'Producto agregado exitosamente.'];
            } else {
                $respuesta = ['success' => false, 'message' => 'Error al agregar el producto.'];
            }
        } else {
            $respuesta = ['success' => false, 'message' => 'Datos incompletos.'];
        }

        return $respuesta;
    }

    /**
     * Se encarga de realizar la baja de un producto y retorna un arreglo con el mensaje de confirmación
     * @param array $param ['idproducto]
     */
    public function bajaProducto($param = null){
        $respuesta = [];
        if (isset($param['idproducto'])) {
            $idproducto = $param['idproducto'];
            $abmProducto = new AbmProducto();
            $baja = $abmProducto->baja(['idproducto' => $idproducto]);
            if ($baja) {
                $respuesta = ['success' => true, 'message' => 'Producto dado de baja correctamente.'];
            } else {
                $respuesta = ['success' => false, 'message' => 'Error al dar de baja el producto.'];
            }
        
        }
        return $respuesta;
    }

    
    /**
     * Se encarga de deshabilitar un producto y retorna un arreglo con el mensaje de confirmación
     * @param array $param ['idproducto]
     */
    public function deshabilitarProducto($param = null){

        $respuesta = [];
        if (isset($param['idproducto'])) {
            // buscar el producto
            $productos = (new AbmProducto())->buscar(['idproducto' => $param['idproducto']]);
        
            if (!empty($productos)) {
                $producto = $productos[0];
                $param['idproducto'] = $producto->getIdproducto();
                $param['pronombre'] = $producto->getPronombre();
                $param['prodetalle'] = $producto->getProdetalle();
                $param['procantstock'] = $producto->getProcantstock();
                $param['proprecio'] = $producto->getProprecio();
                
                
                $datetime = new DateTime('now');
                $datetime->setTime(0, 0, 0); // Hora: 00:11:12
                $param['prodeshabilitado'] = $datetime->format('Y-m-d H:i:s'); 
                // se setea a  00:00:00 por la zona horaria del servidor
        
        
                $modificacion = (new AbmProducto())->modificacion($param);
        
                if ($modificacion) {
                    $respuesta =['success' => true, 'message' => 'Producto deshabilitado exitosamente.'];
                } else {
                    $respuesta =['success' => false, 'message' => 'Error al deshabilitar el producto.'];
                }
            } else {
                $respuesta =['success' => false, 'message' => 'Producto no encontrado.'];
            }
        } else {
           $respuesta =['success' => false, 'message' => 'Datos incompletos.'];
        }
        return $respuesta;
    }

    /**
     * Se encarga de habilitar un producto y retorna un arreglo con el mensaje de confirmación
     * @param array $param ['idproducto]
     */
    public function habilitarProducto($param = null){

        $respuesta = [];
        if (isset($param['idproducto'])) {
            // buscar el producto
            $productos = (new AbmProducto())->buscar(['idproducto' => $param['idproducto']]);
        
            if (!empty($productos)) {
                $producto = $productos[0];
                $param['idproducto'] = $producto->getIdproducto();
                $param['pronombre'] = $producto->getPronombre();
                $param['prodetalle'] = $producto->getProdetalle();
                $param['procantstock'] = $producto->getProcantstock();
                $param['proprecio'] = $producto->getProprecio();
                $param['prodeshabilitado'] = null;
        
                $modificacion = (new AbmProducto())->modificacion($param);
        
                if ($modificacion) {
                    $respuesta = ['success' => true, 'message' => 'Producto habilitado exitosamente.'];
                } else {
                    $respuesta = ['success' => false, 'message' => 'Error al deshabilitar el producto.'];
                }
            } else {
                $respuesta = ['success' => false, 'message' => 'Producto no encontrado.'];
            }
        } else {
            $respuesta = ['success' => false, 'message' => 'Datos incompletos.'];
        }
        return $respuesta;
    }

    /**
     * Lista productos con su informacion necesaria para ser mostrados en Administrar productos.
     * Retorna un array de productos (en forma de array) que cumplan $param
     * @param array $param ['idproducto', 'proprecio', 'pronombre', 'prodetalle', 'procantstock', 'deshabilitado'
     */
    public function listarProductosAdministrar($param = null) {
        $respuesta = [];
        $productos = (new AbmProducto())->buscar($param); //Recupera productos
        foreach($productos as $producto) {
            $prod['icon'] = BASE_URL."/View/Media/Product/".$producto->getIdproducto()."/icon.png";
            $prod['idproducto'] = $producto->getIdproducto();
            $prod['pronombre'] = $producto->getPronombre();
            $prod['prodetalle'] = $producto->getProdetalle();
            $prod['procantstock'] = $producto->getProcantstock();
            $prod['proprecio'] = $producto->getProprecio();
            $prod['prodeshabilitado'] = $producto->getProdeshabilitado();
            array_push($respuesta, $prod);            
        }
        return $respuesta;
    }

    /**
     * Modifica los productos y retorna un arreglo con el mensaje del estado de la operación
     * @param array $param['idproducto'], $param['pronombre'], $param['prodetalle'], $param['procantstock'], $param['proprecio']
     */
    public function modificarProductos($param = null){
        $respuesta = [];
        if (isset($param['idproducto'])) {
            // buscar el producto
            $productos = (new AbmProducto())->buscar(['idproducto' => $param['idproducto']]);
            
            if (!empty($productos)) {
                $producto = $productos[0];
                $param['idproducto'] = $producto->getIdproducto();
                if (isset($param['nombre'])) {
                    $param['pronombre'] = $param['nombre'];
                } else {
                    $param['pronombre'] = $producto->getPronombre();
                }
                if (isset($param['detalle'])) {
                    $param['prodetalle'] = $param['detalle'];
                } else {
                    $param['prodetalle'] = $producto->getProdetalle();
                }
                if (isset($param['stock'])) {
                    $param['procantstock'] = $param['stock'];
                } else {
                    $param['procantstock'] = $producto->getProcantstock();
                }
                if (isset($param['precio'])) {
                    $param['proprecio'] = $param['precio'];
                } else {
                    $param['proprecio'] = $producto->getProprecio();
                }

                $modificacion = (new AbmProducto())->modificacion($param);
                
                if ($modificacion) {
                    $respuesta = ['success' => true, 'message' => 'Producto modificado exitosamente.'];
                } else {
                    $respuesta = ['success' => false, 'message' => 'Error al modificar el producto.'];
                }
            } else {
                $respuesta = ['success' => false, 'message' => 'Producto no encontrado.'];
            }
        } else {
            $respuesta = ['success' => false, 'message' => 'Datos incompletos.'];
        }

        return $respuesta;
    }
    

    public function buscar($param = null) {
        $where = " true ";
        if ($param != null) {
            if (isset($param['idproducto'])) {
                $where .= " AND idproducto = ".$param['idproducto'];
            }
            if (isset($param['proprecio'])) {
                $where .= " AND proprecio = ".$param['proprecio'];
            }
            if (isset($param['pronombre'])) {
                $where .= " AND pronombre = '".$param['pronombre']."'";
            }
            if (isset($param['prodetalle'])) {
                $where .= " AND prodetalle = '".$param['prodetalle']."'";
            }
            if (isset($param['procantstock'])) {
                $where .= " AND procantstock = ".$param['procantstock'];
            }
            if (isset($param['prodeshabilitado'])) {
                $where .= " AND prodeshabilitado = '".$param['prodeshabilitado']."'";
            }
        }
        $arreglo = (new Producto())->listar($where);
        return $arreglo;
    }
}