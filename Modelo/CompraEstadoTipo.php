<?php

include_once $_SERVER['DOCUMENT_ROOT']."/PWD_TPFINAL/configuracion.php";
include_once ROOT_PATH.'/Modelo/conector/BaseDatos.php';
include_once ROOT_PATH.'/Modelo/Usuario.php';

class CompraEstadoTipo {
    private $idcompraestadotipo;
    private $cetdescripcion;
    private $cetdetalle;
    private $mensajeoperacion;

    public function __construct($idcompraestadotipo = null, $cetdescripcion = null, $cetdetalle = null) {
        $this->idcompraestadotipo = $idcompraestadotipo;
        $this->cetdescripcion = $cetdescripcion;
        $this->cetdetalle = $cetdetalle;
    }

    // getters 
    public function getIdcompraEstadoTipo() {
        return $this->idcompraestadotipo;
    }

    public function getCetdescripcion() {
        return $this->cetdescripcion;
    }

    public function getCetdetalle() {
        return $this->cetdetalle;
    }

    public function getMensajeOperacion() {
        return $this->mensajeoperacion;
    }

    // setters
    public function setIdCompraEstadoTipo($idcompraestadotipo) {
        $this->idcompraestadotipo = $idcompraestadotipo;
    }

    public function setCetdescripcion($cetdescripcion) {
        $this->cetdescripcion = $cetdescripcion;
    }

    public function setCetdetalle($cetdetalle) {
        $this->cetdetalle = $cetdetalle;
    }

    public function setMensajeOperacion($mensajeoperacion) {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function cargarDatos($idcompraestadotipo = null, $cetdescripcion = null, $cetdetalle = null) {
        $this->setIdCompraEstadoTipo($idcompraestadotipo);
        $this->setCetdescripcion($cetdescripcion);
        $this->setCetdetalle($cetdetalle);
    }

    /**
     * Buscar datos de una compraestadotipo por su id
     * @param int $idcompraestadotipo
     * @return boolean
     */
    public function buscarDatos($idcompraestadotipo) {
        $bd = new BaseDatos();
        $resultado = false;
        if ($bd->Iniciar()) {
            $consulta = "SELECT * FROM compraestadotipo WHERE idcompraestadotipo = $idcompraestadotipo";
            if ($bd->Ejecutar($consulta)) {
                if ($row = $bd->Registro()) {
                    $this->cargarDatos($row['idcompraestadotipo'], $row['cetdescripcion'], $row['cetdetalle']);
                    $resultado = true;
                }
            }
        }
        return $resultado;
    }

    /**
     * Retorna una coleccion de compraestadotipo donde se cumpla $condicion
     * @param $condicion // WHERE de sql
     * @return array // compraestadotipo que cumplieron la condicion
     */
    public function listar($condicion = "") {
        $coleccion = [];
        $bd = new BaseDatos();
        if ($bd->Iniciar()) {
            $consulta = "SELECT * FROM compraestadotipo";
            if ($condicion != "") {
                $consulta = $consulta.' WHERE '.$condicion;
            }
            $consulta .= " ORDER BY idcompraestadotipo ";
            if ($bd->Ejecutar($consulta)) {
                while ($row = $bd->Registro()) {
                    $this->cargarDatos($row['idcompraestadotipo'], $row['cetdescripcion'], $row['cetdetalle']);
                    array_push($coleccion, $this);
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
     * Insertar los datos de una compraestadotipo a la bd
     * @return boolean
     */
    public function insertar() {
        $resultado = false;
        $bd = new BaseDatos();
        if ($bd->Iniciar()) {
            $consulta = "INSERT INTO compraestadotipo(idcompraestadotipo, cetdescripcion, cetdetalle) VALUES
            (".$this->getIdcompraEstadoTipo().",'".$this->getCetdescripcion().", '".$this->getCetdetalle()."')";
            if ($bd->Ejecutar($consulta)) {
                $this->setIdCompraEstadoTipo($bd->lastInsertId());
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
     * Modificar los datos de una compraestadotipo en la bd (No se deberia usar en principio)
     * @return boolean
     */
    public function modificar() {
        $bd = new BaseDatos();
        $resultado = false;
        if ($bd->Iniciar()) {
            $consulta = "UPDATE compraestadotipo SET 
                cetdescripcion = '".$this->getCetdescripcion()."', 
                cetdetalle = ".$this->getCetdetalle()." 
            WHERE idcompraestadotipo = ".$this->getIdcompraEstadoTipo();
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
     * Eliminar una compraestadotipo de la bd
     * @return boolean
     */
    public function eliminar() {
        $bd = new BaseDatos();
        $resultado = false; 
        if ($bd->Iniciar())  {
            $consulta = "DELETE FROM compraestadotipo 
                WHERE idcompraestadotipo = ".$this->getIdCompraEstadoTipo();
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
     * Retorna un string con los datos de la compraestadotipo
     * @return string
     */
     public function __tostring() {
        return ("idcompraestadotipo: " . $this->getIdcompraEstadoTipo() . "\n" .
                "cetdescripcion: " . $this->getCetdescripcion() . "\n" .
                "cetdetalle: " . $this->getCetdetalle() . "\n");
     }
}