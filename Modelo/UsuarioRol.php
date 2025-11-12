<?php 

include_once $_SERVER['DOCUMENT_ROOT']."/PWD_TPFINAL/configuracion.php";
include_once ROOT_PATH.'/Modelo/conector/BaseDatos.php';
include_once ROOT_PATH.'/Modelo/Usuario.php';
include_once ROOT_PATH.'/Modelo/Rol.php';

class UsuarioRol {
    private $objUsuario;
    private $objRol;
    private $mensajeOperacion;
    
    public function __construct($objUsuario = null, $objRol = null) {
        $this->objUsuario = $objUsuario;
        $this->objRol = $objRol;
    }

    // getters
    public function getObjUsuario() {
        return $this->objUsuario;
    }

    public function getObjRol() {
        return $this->objRol;
    }

    public function getMensajeOperacion() {
        return $this->mensajeOperacion;
    }

    // setters
    public function setObjUsuario($objUsuario) {
        $this->objUsuario = $objUsuario;
    }

    public function setObjRol($objRol) {
        $this->objRol = $objRol;
    }

    public function setMensajeOperacion($mensajeOperacion){
        $this->mensajeOperacion = $mensajeOperacion;
    }

    // metodos CRUD
    public function cargarDatos($objUsuario, $objRol) {
        $this->setObjUsuario($objUsuario);
        $this->setObjRol($objRol);
    }

    /**
     * Buscar datos de un usuariorol por sus ids
     * @param Usuario $objUsuario
     * @param Rol $objRol
     * @return boolean
     */
    public function buscarDatos($objUsuario, $objRol) {
        $bd = new BaseDatos();
        $resultado = false;
        if ($bd->Iniciar()) {
            $consulta = "SELECT * FROM usuariorol 
            WHERE idusuario = ".$objUsuario->getIdusuario()." AND idrol = ".$objRol->getIdrol();
            if ($bd->Ejecutar($consulta)) {
                if ($row = $bd->Registro()) {
                    //Busca datos de cada objeto por separado
                    $objUsuario = new Usuario();
                    $objUsuario->buscarDatos($row['idusuario']);
                    $objRol = new Rol();
                    $objRol->buscarDatos($row['idrol']);
                    $this->cargarDatos($objUsuario, $objRol);
                    $resultado = true;
                }
            }
        }
        return $resultado;
    }
    
    /**
     * Retorna una coleccion de usuariorol donde se cumpla $condicion
     * @param $condicion // WHERE de sql
     * @return array // usuariorol que cumplieron la condicion
     */
    public function listar($condicion = "") {
        $coleccion = [];
        $bd = new BaseDatos();
        if ($bd->Iniciar()) {
            $consulta = "SELECT * FROM usuariorol";
            if ($condicion != "") {
                $consulta = $consulta.' WHERE '.$condicion;
            }
            $consulta .= " ORDER BY idusuario ";
            if ($bd->Ejecutar($consulta)) {
                while ($row = $bd->Registro()) {
                    $objUsuario = new Usuario();
                    $objUsuario->buscarDatos($row['idusuario']);

                    $objRol = new Rol();
                    $objRol->buscarDatos($row['idrol']);

                    $obj = new UsuarioRol();
                    $obj->cargarDatos($objUsuario, $objRol);
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
     * Insertar los datos de un usuariorol a la bd
     * @return boolean
     */
    public function insertar() {
        $resultado = false;
        $bd = new BaseDatos();
        if ($bd->Iniciar()) {
            $consulta = "INSERT INTO usuariorol(idusuario, idrol) VALUES 
            (".($this->getObjUsuario())->getIdusuario().", ".($this->getObjRol())->getIdrol().")";
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
     * Modificar los datos de un usuariorol con los que tiene el objeto actual 
     * (Ambos y unicos atributos son clave, nunca se utilizaria este metodo. En dicho caso,
     * se realizara el alta de otro usuariorol y luego la baja del que no es necesario)
     * @return boolean
     */
    public function modificar() {
        $bd = new BaseDatos();
        $resultado = false;
        if ($bd->Iniciar()) {
            $consulta = "UPDATE usuariorol SET idrol = ".($this->getObjRol())->getIdrol()." 
            WHERE idusuario = ".($this->getObjUsuario())->getIdusuario();
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
     * Eliminar un usuariorol de la bd
     * @return boolean
     */
    public function eliminar() {
        $bd = new BaseDatos();
        $resultado = false;
        if ($bd->Iniciar()) {
            $consulta = "DELETE FROM usuariorol 
            WHERE idusuario = ".($this->getObjUsuario())->getIdusuario()." AND idrol = ".($this->getObjRol())->getIdrol();
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
     * Retorna un string con los datos del usuariorol
     * @return string
     */
    public function __toString() {
        return ("Usuario: ".$this->getObjUsuario()." \n Rol: ".$this->getObjRol());
    }
}
?>