<?php

include_once $_SERVER['DOCUMENT_ROOT']."/PWD_TPFINAL/configuracion.php";
include_once ROOT_PATH.'/Modelo/conector/BaseDatos.php';

class Producto {
    private $idproducto;
    private $pronombre;
    private $prodetalle;
    private $procantstock;
    private $proprecio;
    private $prodeshabilitado;
    private $mensajeOperacion;

    public function __construct($idproducto = null, $pronombre = null, $prodetalle = null, $procantstock = null, $proprecio = null, $prodeshabilitado = null) {
        $this->idproducto = $idproducto;
        $this->pronombre = $pronombre;
        $this->prodetalle = $prodetalle;
        $this->procantstock = $procantstock;
        $this->proprecio = $proprecio;
        $this->prodeshabilitado = $prodeshabilitado;
    }

    // Getters
    public function getIdproducto() {
        return $this->idproducto;
    }

    public function getPronombre() {
        return $this->pronombre;
    }

    public function getProdetalle() {
        return $this->prodetalle;
    }

    public function getProcantstock() {
        return $this->procantstock;
    }

    public function getProprecio() {
        return $this->proprecio;
    }

    public function getProdeshabilitado() {
        return $this->prodeshabilitado;
    } 

    public function getMensajeOperacion() {
        return $this->mensajeOperacion;
    }

    // Setters
    public function setIdproducto($idproducto) {
        $this->idproducto = $idproducto;
    }

    public function setPronombre($pronombre) {
        $this->pronombre = $pronombre;
    }

    public function setProdetalle($prodetalle) {
        $this->prodetalle = $prodetalle;
    }
    public function setProcantstock($procantstock) {
        $this->procantstock = $procantstock;
    }

    public function setProprecio($proprecio) {
        $this->proprecio = $proprecio;
    }

    public function setProdeshabilitado($prodeshabilitado) {
        $this->prodeshabilitado = $prodeshabilitado;
    }

    public function setMensajeOperacion($mensajeOperacion) {
        $this->mensajeOperacion = $mensajeOperacion;
    }

    // metodos CRUD
    public function cargarDatos($idproducto, $pronombre = null, $prodetalle = null, $procantstock = null, $proprecio = null, $prodeshabilitado = null) {
        $this->setIdproducto($idproducto);
        $this->setPronombre($pronombre);
        $this->setProdetalle($prodetalle);
        $this->setProcantstock($procantstock);
        $this->setProprecio($proprecio);
        $this->setProdeshabilitado($prodeshabilitado);
    }

    /**
     * Buscar datos de un producto por su id
     * @param int $idproducto
     * @return boolean
     */
    public function buscarDatos($idproducto) {
        $bd = new BaseDatos();
        $resultado = false;
        if ($bd->Iniciar()) {
            $consulta = "SELECT * FROM producto WHERE idproducto = $idproducto";
            if ($bd->Ejecutar($consulta)) {
                if ($row = $bd->Registro()) {
                    $this->cargarDatos($idproducto, $row['pronombre'], $row['prodetalle'], $row['procantstock'], $row['proprecio'], $row['prodeshabilitado']);
                    $resultado = true;
                }
            }
        }
        return $resultado;
    }

    /**
     * Retorna una coleccion de productos donde se cumpla $condicion
     * @param $condicion // WHERE de sql
     * @return array // productos que cumplieron la condicion
     */
    public function listar($condicion = "") {
        $coleccion = [];
        $base = new BaseDatos();
        if ($base->Iniciar()) {
            $consulta = "SELECT * FROM producto";
            if ($condicion != "") {
                $consulta = $consulta.' WHERE '.$condicion;
            }
            $consulta .= " ORDER BY idproducto ";
            if ($base->Ejecutar($consulta)) {
                while ($row = $base->Registro()) {
                    $obj = new Producto();
                    $obj->cargarDatos($row['idproducto'], $row['pronombre'], $row['prodetalle'], $row['procantstock'], $row['proprecio'], $row['prodeshabilitado']);
                    array_push($coleccion, $obj);
                }
            } else {
                $this->setmensajeOperacion($base->getError());
            }
        } else {
            $this->setmensajeOperacion($base->getError());
        }
        return $coleccion;
    }

    public function insertar() {
        $resultado = false;
        $bd = new BaseDatos();
    
        if ($bd->Iniciar()) {
            $consulta = "INSERT INTO producto (pronombre, prodetalle, procantstock, proprecio) VALUES 
            ('".$this->getPronombre()."','".$this->getProdetalle()."','".$this->getProcantstock()."','".$this->getProprecio()."')";
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
     * Modificar los datos de un producto con los que tiene el objeto actual 
     * @return boolean
     */
    public function modificar() {
        $resultado = false;
        $bd = new BaseDatos();
        if ($bd->Iniciar()) {

            if ($this->getProdeshabilitado() == null) {
                $desha = 'null';
            } else {
                $desha = "'".$this->getProdeshabilitado()."'";
            }
            
            $consulta = "UPDATE producto 
            SET pronombre = '".addslashes($this->getPronombre())."', 
                prodetalle = '".addslashes($this->getProdetalle())."', 
                procantstock = ".$this->getProcantstock().", 
                proprecio = ".$this->getProprecio().", 
                prodeshabilitado = ".$desha." 
            WHERE idproducto = ".$this->getIdproducto();
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
     * Eliminar un producto de la bd
     * @return boolean
     */
    public function eliminar() {
        $resultado = false;
        $bd = new BaseDatos();
        if ($bd->Iniciar()) {
            $consulta = "DELETE FROM producto WHERE idproducto = ".$this->getIdproducto();
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
     * Retorna un string con los datos del producto
     * @return string
     */
    public function __toString() {
        return ("idproducto: ".$this->getIdproducto()."\n" .
            "pronombre: ".$this->getPronombre()."\n".
            "prodetalle: ".$this->getProdetalle()."\n".
            "procantstock: ".$this->getProcantstock()."\n".
            "proprecio: ".$this->getProprecio()."\n".
            "prodeshabilitado: ".$this->getProdeshabilitado())."\n";
    }
}
?>