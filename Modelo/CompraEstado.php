<?php 
include_once $_SERVER['DOCUMENT_ROOT']."/PWD_TPFINAL/configuracion.php";
include_once ROOT_PATH.'/Modelo/conector/BaseDatos.php';
include_once ROOT_PATH.'/Modelo/CompraEstadoTipo.php';
include_once ROOT_PATH.'/Modelo/Compra.php';

class CompraEstado {
    private $idcompraestado;
    private $objCompra;
    private $objCompraEstadoTipo;
    private $cefechaini;
    private $cefechafin;
    private $mensajeOperacion;
    
    public function __construct($idcompraestado = null, $objCompra = null, $objCompraEstadoTipo = null, $cefechaini = null, $cefechafin = null) {
        $this->idcompraestado = $idcompraestado;
        $this->objCompra = $objCompra;
        $this->objCompraEstadoTipo = $objCompraEstadoTipo;
        $this->cefechaini = $cefechaini;
        $this->cefechafin = $cefechafin;
    }

    // getters
    public function getIdcompraestado() {
        return $this->idcompraestado;
    }

    public function getObjCompra() {
        return $this->objCompra;
    }

    public function getObjCompraEstadoTipo() {
        return $this->objCompraEstadoTipo;
    }

    public function getCefechaini() {
        return $this->cefechaini;
    }

    public function getCefechafin() {
        return $this->cefechafin;
    }

    public function getMensajeOperacion() {
        return $this->mensajeOperacion;
    }

    // setters
    public function setIdcompraestado($idcompraestado) {
        $this->idcompraestado = $idcompraestado;
    }

    public function setObjCompra($objCompra) {
        $this->objCompra = $objCompra;
    }

    public function setObjCompraEstadoTipo($objCompraEstadoTipo) {
        $this->objCompraEstadoTipo = $objCompraEstadoTipo;
    }

    public function setCefechaini($cefechaini) {
        $this->cefechaini = $cefechaini;
    }

    public function setCefechafin($cefechafin) {
        $this->cefechafin = $cefechafin;
    }

    public function setMensajeOperacion($mensajeOperacion){
        $this->mensajeOperacion = $mensajeOperacion;
    }
    
    // metodos CRUD
    public function cargarDatos($idcompraestado = null, $objCompra = null, $objCompraEstadoTipo = null, $cefechaini = null, $cefechafin = null) {
        $this->setIdcompraestado($idcompraestado);
        $this->setObjCompra($objCompra);
        $this->setObjCompraEstadoTipo($objCompraEstadoTipo);
        $this->setCefechaini($cefechaini);
        $this->setCefechafin($cefechafin);
    }

    /**
     * Buscar datos de un compraestado por su id
     * @param int $id
     * @return boolean
     */
    public function buscarDatos($idcompraestado) {
        $bd = new BaseDatos();
        $resultado = false;
        if ($bd->Iniciar()) {
            $consulta = "SELECT * FROM compraestado WHERE idcompraestado = $idcompraestado";
            if ($bd->Ejecutar($consulta)) {
                if ($row = $bd->Registro()) {
                    $objCompra = new Compra();
                    $objCompra->buscarDatos($row['idcompra']);

                    $objCompraEstadoTipo = new CompraEstadoTipo();
                    $objCompraEstadoTipo->buscarDatos($row['idcompraestadotipo']);

                    $this->cargarDatos($idcompraestado, $objCompra, $objCompraEstadoTipo, $row['cefechaini'], $row['cefechafin']);
                    $resultado = true;
                }
            }
        }
        return $resultado;
    }
    
    /**
     * Retorna una coleccion de compraestados que cumplan $condicion
     * @param $condicion // WHERE de sql
     * @return array // compraestados que cumplieron la condicion
     */
    public function listar($condicion = "") {
        $coleccion = [];
        $bd = new BaseDatos();
        if ($bd->Iniciar()) {
            $consulta = "SELECT * FROM compraestado";
            if ($condicion != "") {
                $consulta = $consulta.' WHERE '.$condicion;
            }
            $consulta .= " ORDER BY idcompraestado ";
            if ($bd->Ejecutar($consulta)) {
                while ($row = $bd->Registro()) {
                    $objCompra = new Compra();
                    $objCompra->buscarDatos($row['idcompra']);

                    $objCompraEstadoTipo = new CompraEstadoTipo();
                    $objCompraEstadoTipo->buscarDatos($row['idcompraestadotipo']);

                    $obj = new CompraEstado();
                    $obj->cargarDatos($row['idcompraestado'], $objCompra, $objCompraEstadoTipo, $row['cefechaini'], $row['cefechafin']);
                    array_push($coleccion, $obj);
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
     * Insertar los datos de un compraestado a la bd
     * @return boolean
     */
    public function insertar() {
        $resultado = false;
        $bd = new BaseDatos();
        if ($bd->Iniciar()) {
            $consulta = "INSERT INTO compraestado(idcompra, idcompraestadotipo) VALUES 
            (".($this->getObjCompra())->getIdcompra().", ".($this->getObjCompraEstadoTipo())->getIdcompraestadotipo().")";
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
     * Modificar los datos de un compraestado con los que tiene el objeto actual 
     * @return boolean
     */
    public function modificar() {
        $bd = new BaseDatos();
        $resultado = false;
        if ($bd->Iniciar()) {
            $consulta = "UPDATE compraestado 
                            SET idcompra = ".($this->getObjCompra()->getIdcompra()).", 
                                idcompraestadotipo = ".($this->getObjCompraEstadoTipo())->getIdcompraestadotipo().", 
                                cefechaini = '".$this->getCefechaini()."', 
                                cefechafin = '".$this->getCefechafin()."' 
                            WHERE idcompraestado = ".$this->getIdcompraestado();
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
     * Eliminar un compraestado de la bd
     * @return boolean
     */
    public function eliminar() {
        $bd = new BaseDatos();
        $resultado = false;
        if ($bd->Iniciar()) {
            $consulta = "DELETE FROM compraestado WHERE idcompraestadotipo = ".$this->getIdcompraestado();
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
     * Retorna un string con los datos del compraestado
     * @return string
     */
    public function __toString() {
        return ("idcompraestado: ".$this->getIdcompraestado()." \n 
        compra: ".$this->getObjCompra()."\n 
        compraestadotipo: ".$this->getObjCompraEstadoTipo()."\n
        cefechaini: ".$this->getCefechaini()."\n 
        cefechafin: ".$this->getCefechafin());
    }
}
?>