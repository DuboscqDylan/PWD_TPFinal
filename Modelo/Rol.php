<?php

include_once 'BaseDatos.php';

class Rol {
    private $idrol;
    private $rodescripcion;
    private $mensajeOperacion;

    public function __construct($idrol = null, $rodescripcion = null) {
        $this->idrol = $idrol;
        $this->rodescripcion = $rodescripcion;
    }

    // getters 
    public function getIdrol() {
        return $this->idrol;
    }
    
    public function getRodescripcion() {
        return $this->rodescripcion;
    }

    public function getMensajeOperacion() {
        return $this->mensajeOperacion;
    }

    // setters
    public function setIdrol($idrol) {
        $this->idrol = $idrol;
    }
    public function setRodescripcion($rodescripcion) {
        $this->rodescripcion = $rodescripcion;
    }

    public function setMensajeOperacion($mensajeOperacion) {
        $this->mensajeOperacion = $mensajeOperacion;
    }
    
    // metodos CRUD
    public function cargarDatos($idrol = null, $rodescripcion = null) {
        $this->setRoDescripcion($rodescripcion);
        $this->setIdrol($idrol);
    }
    
    /**
     * Buscar datos de un rol por su id
     * @param int $idrol
     * @return boolean
     */
    public function buscarDatos($idrol) {
        $bd = new BaseDatos();
        $resultado = false;
        if ($bd->Iniciar()) {
            $consulta = "SELECT * FROM rol WHERE idrol = $idrol";
            if ($bd->Ejecutar($consulta)) {
                if ($row = $bd->Registro()) {
                    $this->cargarDatos($idrol, $row['rodescripcion']);
                    $resultado = true;
                }
            }
        }
        return $resultado;
    }

    /**
     * Retorna una coleccion de roles donde se cumpla $condicion
     * @param $condicion // WHERE de sql
     * @return array // roles que cumplieron la condicion
     */
    public function listar($condicion = "") {
        $coleccion = [];
        $base = new BaseDatos();
        if ($base->Iniciar()) {
            $consulta = "SELECT * FROM rol";
            if ($condicion != "") {
                $consulta = $consulta.' WHERE '.$condicion;
            }
            $consulta .= " ORDER BY idrol ";
            if ($base->Ejecutar($consulta)) {
                while ($row = $base->Registro()) {
                    $obj = new Rol();
                    $obj->cargarDatos($row['idrol'], $row['rodescripcion']);
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

    /**
     * Insertar los datos de un rol a la bd
     * @return boolean
     */
    public function insertar() {
        $resultado = false;
        $bd = new BaseDatos();
        if ($bd->Iniciar()) {
            $consulta = "INSERT INTO rol(rodescripcion) VALUES 
            ('".$this->getRodescripcion()."')";
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
     * Modificar los datos de un rol con los que tiene el objeto actual 
     * @return boolean
     */
    public function modificar() {
        $resultado = false;
        $bd = new BaseDatos();
        if ($bd->Iniciar()) {
            $consulta = "UPDATE rol 
            SET rodescripcion = ".$this->getRodescripcion()."
            WHERE idrol = ".$this->getIdrol();
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
     * Eliminar un rol de la bd
     * @return boolean
     */
    public function eliminar() {
        $resultado = false;
        $bd = new BaseDatos();
        if ($bd->Iniciar()) {
            $consulta = "DELETE FROM rol WHERE idrol = ".$this->getIdrol();
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
     * Retorna un string con los datos del rol
     * @return string
     */
    public function __toString() {
        return ("idrol: ".$this->getIdrol()."\n".
            "rodescripcion: ".$this->getRodescripcion());
    }
}
?>