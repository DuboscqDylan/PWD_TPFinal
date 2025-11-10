<?php 

include_once 'BaseDatos.php';

class Usuario {
    private $idusuario;
    private $usnombre;
    private $uspass;
    private $usmail;
    private $usdeshabilitado;
    private $mensajeOperacion;
    
    public function __construct($idusuario = null, $usnombre = null, $uspass = null, $usmail = null, $usdeshabilitado = null) {
        $this->idusuario = $idusuario;
        $this->usnombre = $usnombre;
        $this->uspass = $uspass;
        $this->usmail = $usmail;
        $this->usdeshabilitado = $usdeshabilitado;
    }

    // getters
    public function getIdusuario() {
        return $this->idusuario;
    }

    public function getUsnombre() {
        return $this->usnombre;
    }

    public function getUsmail() {
        return $this->usmail;
    }

    public function getUspass() {
        return $this->uspass;
    }

    public function getUsdeshabilitado() {
        return $this->usdeshabilitado;
    }

    public function getMensajeOperacion() {
        return $this->mensajeOperacion;
    }

    // setters
    public function setIdusuario($idusuario) {
        $this->idusuario = $idusuario;
    }

    public function setUsnombre($usNombre) {
        $this->usnombre = $usNombre;
    }

    public function setUspass($usPass) {
        $this->uspass = $usPass;
    }

    public function setUsmail($usMail) {
        $this->usmail = $usMail;
    }

    public function setUsdeshabilitado($usdeshabilitado) {
        $this->usdeshabilitado = $usdeshabilitado;
    }

    public function setMensajeOperacion($mensajeOperacion){
        $this->mensajeOperacion = $mensajeOperacion;
    }

    // metodos CRUD
    public function cargarDatos($idusuario, $usnombre = null , $uspass = null , $usmail = null , $usdeshabilitado = null) {
        $this->setIdusuario($idusuario);
        $this->setUsnombre($usnombre);
        $this->setUspass($uspass);
        $this->setUsmail($usmail);
        $this->setUsdeshabilitado($usdeshabilitado);
    }

    /**
     * Buscar datos de un usuario por su id
     * @param int $id
     * @return boolean
     */
    public function buscarDatos($idusuario) {
        $bd = new BaseDatos();
        $resultado = false;
        if ($bd->Iniciar()) {
            $consulta = "SELECT * FROM usuario WHERE idusuario = $idusuario";
            if ($bd->Ejecutar($consulta)) {
                if ($row = $bd->Registro()) {
                    $this->cargarDatos($idusuario, $row['usnombre'], $row['uspass'], $row['usmail'], $row['usdeshabilitado']);
                    $resultado = true;
                }
            }
        }
        return $resultado;
    }
    
    /**
     * Retorna una coleccion de usuarios donde se cumpla $condicion
     * @param $condicion // WHERE de sql
     * @return array // usuarios que cumplieron la condicion
     */
    public function listar($condicion = "") {
        $coleccion = [];
        $bd = new BaseDatos();
        if ($bd->Iniciar()) {
            $consulta = "SELECT * FROM usuario";
            if ($condicion != "") {
                $consulta = $consulta.' WHERE '.$condicion;
            }
            $consulta .= " ORDER BY idusuario ";
            if ($bd->Ejecutar($consulta)) {
                while ($row = $bd->Registro()) {
                    $obj = new Usuario();
                    $obj->cargarDatos($row['idusuario'], $row['usnombre'], $row['uspass'], $row['usmail'], $row['usdeshabilitado']);
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
     * Insertar los datos de un usuario a la bd
     * @return boolean
     */
    public function insertar() {
        $resultado = false;
        $bd = new BaseDatos();
        if ($bd->Iniciar()) {
            $consulta = "INSERT INTO usuario(usnombre, uspass, usmail) VALUES 
            ('".$this->getUsnombre()."', '".$this->getUspass()."', '".$this->getUsmail()."')";
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
     * Modificar los datos de un usuario con los que tiene el objeto actual 
     * @return boolean
     */
    public function modificar() {
        $bd = new BaseDatos();
        $resultado = false;
        if ($bd->Iniciar()) {
            if ($this->getUsdeshabilitado() == null) {
                $desha = 'null';
            } else {
                $desha = "'".$this->getUsdeshabilitado()."'";
            }
            
            $consulta = "UPDATE usuario 
                         SET usnombre = '".$this->getUsnombre()."', 
                             uspass = '".$this->getUspass()."', 
                             usmail = '".$this->getUsmail()."', 
                             usdeshabilitado = ".$desha."
                         WHERE idusuario = ".$this->getIdusuario();
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
     * Eliminar un usuario de la bd
     * @return boolean
     */
    public function eliminar() {
        $bd = new BaseDatos();
        $resultado = false;
        if ($bd->Iniciar()) {
            $consulta = "DELETE FROM usuario WHERE idusuario = ".$this->getIdusuario();
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
     * Retorna un string con los datos del usuario
     * @return string
     */
    public function __toString() {
        return ("idusuario: ".$this->getIdusuario()." \n 
        usnombre: ".$this->getUsnombre()."\n 
        uspass: ".$this->getUspass()."\n 
        usmail: ".$this->getUsmail()." \n 
        usdeshabilitado: ".$this->getUsdeshabilitado());
    }
}
?>