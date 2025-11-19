<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD_TPFINAL/configuracion.php";
include_once ROOT_PATH . '/Modelo/conector/BaseDatos.php';

class Menu
{
    private $idmenu;
    private $menombre;
    private $medescripcion;
    private $meurl;
    private $ObjMenu;
    private $ObjPadre;
    private $medeshabilitado;
    private $mensajeoperacion;

    public function __construct()
    {
        $this->idmenu = "";
        $this->menombre = "";
        $this->medescripcion = "";
        $this->meurl = "";
        $this->ObjMenu = null;
        $this->medeshabilitado = null;
        $this->mensajeoperacion = "";
    }

    /**
     * @return mixed
     */
    public function getIdmenu()
    {
        return $this->idmenu;
    }

    public function getPadre()
    {
        return $this->ObjPadre;
    }

    /**
     * @param mixed $idmenu
     */
    public function setIdmenu($idmenu)
    {
        $this->idmenu = $idmenu;
    }

    /**
     * @return mixed
     */
    public function getMenombre()
    {
        return $this->menombre;
    }

    /**
     * @param mixed $menombre
     */
    public function setMenombre($menombre)
    {
        $this->menombre = $menombre;
    }

    /**
     * @return mixed
     */
    public function getMedescripcion()
    {
        return $this->medescripcion;
    }

    /**
     * @return mixed
     */
    public function getMeurl()
    {
        return $this->meurl;
    }

    /**
     * @param mixed $medescripcion
     */
    public function setMedescripcion($medescripcion)
    {
        $this->medescripcion = $medescripcion;
    }

    public function setMeurl($meurl)
    {
        $this->meurl = $meurl;
    }

    /**
     * @return mixed
     */
    public function getObjMenu()
    {
        return $this->ObjMenu;
    }

    /**
     * @param mixed $ObjMenu
     */
    public function setObjMenu($ObjMenu)
    {
        $this->ObjMenu = $ObjMenu;
    }

    /**
     * @return mixed
     */
    public function getMedeshabilitado()
    {
        return $this->medeshabilitado;
    }

    /**
     * @param mixed $medeshabilitado
     */
    public function setMedeshabilitado($medeshabilitado)
    {
        $this->medeshabilitado = $medeshabilitado;
    }

    /**
     * @return string
     */
    public function getMensajeoperacion()
    {
        return $this->mensajeoperacion;
    }

    /**
     * @param string $mensajeoperacion
     */
    public function setMensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function setObjPadre($ObjPadre)
    {
        $this->ObjPadre = $ObjPadre;
    }

    public function cargarDatos($idmenu, $menombre, $medescripcion, $meurl, $ObjMenu, $medeshabilitado)
    {
        $this->setIdmenu($idmenu);
        $this->setMenombre($menombre);
        $this->setMedescripcion($medescripcion);
        $this->setMeurl($meurl);
        $this->setObjMenu($ObjMenu);
        $this->setMedeshabilitado($medeshabilitado);
        $this->setObjPadre($ObjMenu);
    }


    public function cargar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM menu WHERE idmenu = " . $this->getIdmenu();
        //  echo $sql;
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $base->Registro();
                    $objMenuPadre = null;
                    if ($row['idpadre'] != null or $row['idpadre'] != '') {
                        $objMenuPadre = new Menu();
                        $objMenuPadre->setIdmenu($row['idpadre']);
                        $objMenuPadre->cargar();
                    }
                    $this->cargarDatos($row['idmenu'], $row['menombre'], $row['medescripcion'], $row['meurl'], $objMenuPadre, $row['medeshabilitado']);
                }
            }
        } else {
            $this->setmensajeoperacion("Menu->cargar: " . $base->getError()[2]);
        }
        return $resp;
    }

    public function insertar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO menu( menombre ,  medescripcion , meurl ,  idpadre ,  medeshabilitado)  ";
        $sql .= "VALUES('" . $this->getMenombre() . "','" . $this->getMedescripcion() . "','" . $this->getMeurl() . "','";
        if ($this->getObjMenu() != null)
            $sql .= $this->getObjMenu()->getIdmenu() . ",";
        else
            $sql .= "null,";
        if ($this->getMedeshabilitado() != null)
            $sql .= "'" . $this->getMedeshabilitado() . "'";
        else
            $sql .= "null";
        $sql .= ");";
        // echo $sql;
        if ($base->Iniciar()) {
            if ($elid = $base->Ejecutar($sql)) {
                $this->setIdmenu($elid);
                $resp = true;
            } else {
                $this->setmensajeoperacion("Menu->insertar: " . $base->getError()[2]);
            }
        } else {
            $this->setmensajeoperacion("Menu->insertar: " . $base->getError()[2]);
        }
        return $resp;
    }
    /**
     * Buscar datos de un menu por su id
     * @param int $idmenu
     * @return boolean
     */
    public function buscarDatos($idmenu)
    {
        $bd = new BaseDatos();
        $resultado = false;
        if ($bd->Iniciar()) {
            $consulta = "SELECT * FROM menu WHERE idmenu = $idmenu";
            if ($bd->Ejecutar($consulta)) {
                if ($row = $bd->Registro()) {
                    $objPadre = null;
                    if ($row['idpadre'] != null) {
                        $objPadre = new Menu();
                        $objPadre->buscarDatos($row['idpadre']);
                    }
                    $this->cargarDatos($idmenu, $row['menombre'], $row['medescripcion'], $row['meurl'], $objPadre, $row['medeshabilitado']);
                    $resultado = true;
                }
            }
        }
        return $resultado;
    }

    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "UPDATE menu SET menombre='" . $this->getMenombre() . "',medescripcion='" . $this->getMedescripcion() . "','" . $this->getMeurl() . "'";
        if ($this->getObjMenu() != null)
            $sql .= ",idpadre= " . $this->getObjMenu()->getIdmenu();
        else
            $sql .= ",idpadre= null";
        if ($this->getMedeshabilitado() != null)
            $sql .= ",medeshabilitado='" . $this->getMedeshabilitado() . "'";
        else
            $sql .= " ,medeshabilitado=null";
        $sql .= " WHERE idmenu = " . $this->getIdmenu();
        // echo $sql;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("Menu->modificar 1: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("Menu->modificar 2: " . $base->getError());
        }
        return $resp;
    }

    public function eliminar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM menu WHERE idmenu =" . $this->getIdmenu();
        // echo $sql;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("Menu->eliminar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("Menu->eliminar: " . $base->getError());
        }
        return $resp;
    }

    public static  function listar($parametro = "")
    {
        $arreglo = array();
        $base = new BaseDatos();
        $sql = "SELECT * FROM menu ";
        //   echo $sql;
        if ($parametro != "") {
            $sql .= 'WHERE ' . $parametro;
        }
        $res = $base->Ejecutar($sql);
        if ($res > 0) {

            while ($row = $base->Registro()) {
                $obj = new Menu();
                $objMenuPadre = null;
                if ($row['idpadre'] != null) {
                    $objMenuPadre = new Menu();
                    $objMenuPadre->setIdmenu($row['idpadre']);
                }
                $obj->cargarDatos($row['idmenu'], $row['menombre'], $row['medescripcion'], $row['meurl'], $objMenuPadre, $row['medeshabilitado']);
                array_push($arreglo, $obj);
            }
        }


        return $arreglo;
    }
}
