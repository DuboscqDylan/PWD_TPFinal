<?php
include_once 'BaseDatos.php';
include_once 'Compra.php';
include_once 'Producto.php';
// idcompraitem(bigint) idproducto(obj) idcompra(obj) cicantidad(int)

class CompraItem {
    private $idcompraitem;
    private $objProducto;
    private $objCompra;
    private $cicantidad;
    private $mensajeOperacion;

    public function __construct($idcompraitem = null, $objProducto = null, $objCompra = null, $cicantidad = null) {
        $this->idcompraitem  =$idcompraitem;
        $this->objProducto = $objProducto;
        $this->objCompra = $objCompra;
        $this->cicantidad = $cicantidad;
    }

    // Getters
    public function getIdcompraitem() {
        return $this->idcompraitem;
    }

    public function getObjProducto() {
        return $this->objProducto;
    }

    public function getObjCompra() {
        return $this->objCompra;
    }

    public function getCicantidad() {
        return $this->cicantidad;
    }

    public function getMensajeOperacion() {
        return $this->mensajeOperacion;
    }

    // Setters
    public function setIdcompraitem($idcompraitem) {
        $this->idcompraitem = $idcompraitem;
    }

    public function setObjProducto($objProducto) {
        $this->objProducto = $objProducto;
    }

    public function setObjCompra($objCompra) {
        $this->objCompra = $objCompra;
    }

    public function setCicantidad($cicantidad) {
        $this->cicantidad = $cicantidad;
    }

    public function setMensajeOperacion($mensajeOperacion) {
        $this->mensajeOperacion = $mensajeOperacion;
    }

    // Métodos CRUD

    public function cargarDatos($idcompraitem = null, $objProducto = null, $objCompra = null, $cicantidad = null) {
        $this->setIdcompraitem($idcompraitem);
        $this->setObjProducto($objProducto);
        $this->setObjCompra($objCompra);
        $this->setCicantidad($cicantidad);
    }

    /**
     * Buscar datos de una compra por su id
     * @param int $id
     * @return boolean
     */
    public function buscarDatos($id) {
        $bd = new BaseDatos();
        $resultado = false;
        if ($bd->Iniciar()) {
            $consulta = "SELECT * FROM compraitem WHERE idcompraitem = $id";
            if ($bd->Ejecutar($consulta)) {
                if ($row = $bd->Registro()) {
                    $objProducto = new Producto();
                    $objProducto->buscarDatos($row['idproducto']);

                    $objCompra = new Compra();
                    $objCompra->buscarDatos($row['idcompra']);

                    $this->cargarDatos($row['idcompraitem'], $objProducto, $objCompra, $row['cicantidad']);
                    $resultado = true;
                }
            }
        }
        return $resultado;
    }

    /**
     * Retorna una coleccion de items de compra donde se cumpla $condicion
     * @param $condicion // WHERE de sql
     * @return array // items de compra que cumplieron la condicion
     */
    public function listar($condicion = "") {
        $coleccion = [];
        $bd = new BaseDatos();
        if ($bd->Iniciar()) {
            $consulta = "SELECT * FROM compraitem";
            if ($condicion != "") {
                $consulta = $consulta .' WHERE '. $condicion;
            }
            $consulta .= " order by idcompraitem";
            if ($bd->Ejecutar($consulta)) {
                while ($row = $bd->Registro()) {
                    $objProducto = new Producto();
                    $objProducto->buscarDatos($row['idproducto']);

                    $objCompra = new Compra();
                    $objCompra->buscarDatos($row['idcompra']);

                    $compraitem = new CompraItem();
                    $compraitem->cargarDatos($row['idcompraitem'], $objProducto, $objCompra, $row['cicantidad']);
                    array_push($coleccion, $compraitem);
                }
            } else {
                $this->setMensajeOperacion($bd->getError());
            }
        } else {
            $this->setMensajeOperacion($bd->getError());
        }
        return $coleccion;
    }
    
    /**
     * Insertar los datos de un item de compra a la bd
     * @return boolean
     */
    public function insertar() {
        $resultado = false;
        $bd = new BaseDatos();
        if ($bd->Iniciar()) {
            $consulta = "INSERT INTO compraitem(idproducto, idcompra, cicantidad) VALUES 
                (".($this->getObjProducto())->getIdproducto().", ".($this->getObjCompra())->getIdcompra().", ".$this->getCicantidad().")";
            if ($bd->Ejecutar($consulta)) {
                $resultado = true;
            } else {
                $this->setMensajeOperacion($bd->getError());
            }
        } else {
            $this->setMensajeOperacion($bd->getError());
        }
        return $resultado;
    }

    /**
     * Modificar los datos de un item de compra en la bd
     * @return boolean
     */
    public function modificar() {
        $resultado = false;
        $bd = new BaseDatos();
        if ($bd->Iniciar()) {
            $consulta = "UPDATE compraitem 
                            SET idproducto = ".($this->getObjProducto())->getIdproducto().",
                            idcompra = ".($this->getObjCompra())->getIdcompra().", 
                            cicantidad = ".$this->getCicantidad()."
                        WHERE idcompraitem = ".$this->getIdcompraitem();
            if ($bd->Ejecutar($consulta)) {
                $resultado = true;
            } else {
                $this->setMensajeOperacion($bd->getError());
            }
        } else {
            $this->setMensajeOperacion($bd->getError());
        }
        return $resultado;
    }

    /**
     * Eliminar un item de compra de la bd
     * @return boolean
     */
    public function eliminar() {
        $bd = new BaseDatos();
        $resultado = false;
        if ($bd->Iniciar()) {
            $consulta = "DELETE FROM compraitem WHERE idcompraitem = ".$this->getIdcompraitem();
            if ($bd->Ejecutar($consulta)) {
                $resultado = true;
            } else {
                $this->setMensajeOperacion($bd->getError());
            }
        } else {
            $this->setMensajeOperacion($bd->getError());
        }
        return $resultado;
    }

    /**
     * Retorna un string con los datos del item de compra
     * @return String
     */
    public function __toString() {
        return ("Id Compra Item : ".$this->getIdcompraitem()."\n
        Productos: ".$this->getObjProducto(). "\n
        Compra: ".$this->getObjCompra(). "\n
        Cantidad: " .$this->getCicantidad());
    }
}
