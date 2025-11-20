<?php

include_once $_SERVER['DOCUMENT_ROOT']."/PWD_TPFINAL/configuracion.php";
include_once ROOT_PATH.'/Modelo/conector/BaseDatos.php';
include_once ROOT_PATH.'/Modelo/Menu.php';
include_once ROOT_PATH.'/Modelo/Rol.php';

class MenuRol {
    private $objMenu;
    private $objRol;
    private $mensajeOperacion;

    public function __construct($objMenu = null, $objRol = null) {
        $this->objMenu = $objMenu;
        $this->objRol = $objRol;
    }

    public function getObjMenu() {
        return $this->objMenu;
    }

    public function getObjRol() {
        return $this->objRol;
    }

    public function getMensajeOperacion() {
        return $this->mensajeOperacion;
    }

    public function setObjMenu($objMenu) {
        $this->objMenu = $objMenu;
    }

    public function setObjRol($objRol) {
        $this->objRol = $objRol;
    }

    public function setMensajeOperacion($mensajeOperacion) {
        $this->mensajeOperacion = $mensajeOperacion;
    }

    // Metodos
    public function cargarDatos($objMenu = null, $objRol = null) {
        $this->setObjMenu($objMenu);
        $this->setObjRol($objRol);
    }

    /**
     * Buscar datos de un menurol por sus ids
     * @param Menu $objMenu
     * @param Rol $objRol
     * @return boolean
     */
    public function buscarDatos($objMenu, $objRol) {
        $bd = new BaseDatos();
        $resultado = false;
        if ($bd->Iniciar()) {
            $consulta = "SELECT * FROM menurol 
            WHERE idmenu = ".$objMenu->getIdmenu()." AND idrol = ".$objRol->getIdrol();
            if ($bd->Ejecutar($consulta)) {
                if ($row = $bd->Registro()) {
                    $objMenu = new Menu();
                    $objMenu->buscarDatos($row['idmenu']);
                    $objRol = new Rol();
                    $objRol->buscarDatos($row['idrol']);
                    $this->cargarDatos($objMenu, $objRol);
                    $resultado = true;
                }
            }
        }
        return $resultado;
    }

    /**
     * Retorna una coleccion de menurol donde se cumpla $condicion
     * @param $condicion // WHERE de sql
     * @return array // menurol que cumplieron la condicion
     */
    public function listar($condicion = "") {
        $coleccion = [];
        $bd = new BaseDatos();
        if ($bd->Iniciar()) {
            $consulta = "SELECT * FROM menurol";
            if ($consulta != "") {
                $consulta = $consulta." WHERE ".$condicion;
            }
            $consulta .= " ORDER BY idmenu";
            if ($bd->Ejecutar($consulta)) {
                while ($row = $bd->Registro()) {
                    $objMenu = new Menu();
                    $objMenu->buscarDatos($row['idmenu']);

                    $objRol = new Rol();
                    $objRol->buscarDatos($row['idrol']);

                    $obj = new MenuRol();
                    $obj->cargarDatos($objMenu, $objRol);
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
     * Insertar los datos de un menurol a la bd
     * @return boolean
     */
    public function insertar() {
        $resultado = false;
        $bd = new BaseDatos();
        if ($bd->Iniciar()) {
            $consulta = "INSERT INTO menurol(idmenu, idrol) VALUES 
            ('".$this->getObjMenu()->getIdmenu()."', '".$this->getObjRol()->getIdrol()."')";
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
     * Modificar los datos de un menurol con los que tiene el objeto actual 
     * (Ambos y unicos atributos son clave, nunca se utilizaria este metodo. En dicho caso,
     * se realizara el alta de otro menurol y luego la baja del que no es necesario)
     * @return boolean
     */
    public function modificar() {
        $bd = new BaseDatos();
        $resultado = false;
        if ($bd->Iniciar()) {
            $consulta = "UPDATE menurol 
            SET idmenu = ".($this->getObjMenu())->getIdmenu().", idrol = ".($this->getObjRol())->getIdrol()."
            WHERE idmenu = ".($this->getObjMenu())->getIdmenu()." AND idrol = ".($this->getObjRol())->getIdrol();
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
     * Eliminar un menurol de la bd
     * @return boolean
     */
    public function eliminar() {
        $base = new BaseDatos();
        $resp = false;
        if ($base->Iniciar()) {
            $consulta = "DELETE FROM menurol 
            WHERE idmenu = ".($this->getObjMenu())->getIdmenu()." AND idrol = ".($this->getObjRol())->getIdrol();
            if ($base->Ejecutar($consulta)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $resp;
    }

    /**
     * Retorna un string con los datos del menurol
     * @return string
     */
    public function __toString() {
        return ("Menu: ".$this->getObjMenu()." \n Rol: ".$this->getObjRol());
    }
}
?>