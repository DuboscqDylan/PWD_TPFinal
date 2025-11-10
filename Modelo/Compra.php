<?php

include_once 'BaseDatos.php';
include_once 'Usuario.php';

class Compra {
    private $idcompra;
    private $cofecha;
    private $objUsuario;
    private $mensajeoperacion;

    public function __construct($idcompra = null, $cofecha = null, $objUsuario = null) {
        $this->idcompra = $idcompra;
        $this->cofecha = $cofecha;
        $this->objUsuario = $objUsuario;
    }

    // getters 
    public function getIdcompra() {
        return $this->idcompra;
    }

    public function getCofecha() {
        return $this->cofecha;
    }

    public function getObjUsuario() {
        return $this->objUsuario;
    }

    public function getMensajeOperacion() {
        return $this->mensajeoperacion;
    }

    // setters
    public function setIdcompra($idcompra) {
        $this->idcompra = $idcompra;
    }

    public function setCofecha($cofecha) {
        $this->cofecha = $cofecha;
    }

    public function setObjUsuario($objUsuario) {
        $this->objUsuario = $objUsuario;
    }

    public function setMensajeOperacion($mensajeoperacion) {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function cargarDatos($idcompra = null, $cofecha = null, $objUsuario = null) {
        $this->setIdcompra($idcompra);
        $this->setCofecha($cofecha);
        $this->setObjUsuario($objUsuario);
    }

    /**
     * Buscar datos de una compra por su id
     * @param int $idcompra
     * @return boolean
     */
    public function buscarDatos($idcompra) {
        $bd = new BaseDatos();
        $resultado = false;
        if ($bd->Iniciar()) {
            $consulta = "SELECT * FROM compra WHERE idcompra = $idcompra";
            if ($bd->Ejecutar($consulta)) {
                if ($row = $bd->Registro()) {
                    $objUsuario = new Usuario();
                    $objUsuario->buscarDatos($row['idusuario']);
                    
                    $this->cargarDatos($row['idcompra'], $row['cofecha'], $objUsuario);
                    $resultado = true;
                }
            }
        }
        return $resultado;
    }

    /**
     * Retorna una coleccion de compras donde se cumpla $condicion
     * @param $condicion // WHERE de sql
     * @return array // compras que cumplieron la condicion
     */
    public function listar($condicion = "") {
        $coleccion = [];
        $bd = new BaseDatos();
        if ($bd->Iniciar()) {
            $consulta = "SELECT * FROM compra";
            if ($condicion != "") {
                $consulta = $consulta.' WHERE '.$condicion;
            }
            $consulta .= " ORDER BY idcompra ";
            if ($bd->Ejecutar($consulta)) {
                while ($row = $bd->Registro()) {
                    $objUsuario = new Usuario();
                    $objUsuario->buscarDatos($row['idusuario']);

                    $obj = new Compra();
                    $obj->cargarDatos($row['idcompra'], $row['cofecha'], $objUsuario);
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
     * Insertar los datos de una compra a la bd
     * @return boolean
     */
    public function insertar() {
        $resultado = false;
        $bd = new BaseDatos();
        if ($bd->Iniciar()) {
            $consulta = "INSERT INTO compra(idusuario, cofecha) VALUES
            (".($this->getObjUsuario())->getIdusuario().", '".$this->getCofecha()."')";
            if ($bd->Ejecutar($consulta)) {
                $this->setIdcompra($bd->lastInsertId());
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
     * Modificar los datos de una compra en la bd (No se deberia usar en principio)
     * @return boolean
     */
    public function modificar() {
        $bd = new BaseDatos();
        $resultado = false;
        if ($bd->Iniciar()) {
            $consulta = "UPDATE compra SET 
                cofecha = '".$this->getCofecha()."', 
                idusuario = ".($this->getObjUsuario())->getIdusuario()." 
            WHERE idcompra = ".$this->getIdcompra();
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
     * Eliminar una compra de la bd
     * @return boolean
     */
    public function eliminar() {
        $bd = new BaseDatos();
        $resultado = false; 
        if ($bd->Iniciar())  {
            $consulta = "DELETE FROM compra 
                WHERE idcompra = ".$this->getIdcompra();
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
     * Retorna un string con los datos de la compra
     * @return string
     */
     public function __tostring() {
        return ("idcompra: " . $this->getIdcompra() . "\n" .
                "cofecha: " . $this->getCoFecha() . "\n" .
                "usuario: " . $this->getObjUsuario() . "\n");
     }
}
