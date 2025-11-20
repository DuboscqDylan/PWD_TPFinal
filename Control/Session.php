<?php

class Session
{

    /*
    * Constructor que inicia la sesión.
    */
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Actualiza las variables de sesión con los valores ingresados.
     */
    public function iniciar($username, $pass)
    {
        $resp = false;
        //var_dump("DEBUG iniciar():", $username, $pass);
        $obj = new AbmUsuario();
        $param['usnombre'] = $username;
        $param['uspass'] = $pass;
        $param['usdeshabilitado'] = null;

        $resultado = $obj->buscar($param);
        if (count($resultado) > 0) {        // Si el usuario existe en la BD, cargamos su id en la sesión
            $usuario = $resultado[0];
            $_SESSION['idusuario'] = $usuario->getIdusuario();
            $resp = true;
        } else {
            //$this->cerrar();
        }
        return $resp;
    }

    /**
     * Valida si la sesión actual tiene username y pass válidos. Devuelve true o false.
     */
    public function validar()
    {
        $resp = $this->activa() && isset($_SESSION['idusuario']);
        return $resp;
    }

    /**
     * Devuelve true o false si la sesión está activa o no.
     */
    public function activa()
    {
        $resp = session_status() === PHP_SESSION_ACTIVE ? true : false;
        return $resp;
    }

    /**
     * Devuelve el usuario logeado.
     */
    public function getUsuario()
    {
        $usuario = null;
        if ($this->validar()) {
            $obj = new AbmUsuario();
            $param['idusuario'] = $_SESSION['idusuario'];
            $resultado = $obj->buscar($param);
            if (count($resultado) > 0) {
                $usuario = $resultado[0];
            }
        }
        return $usuario;
    }

    /**
     * Devuelve lista de roles del usuario logeado.
     */
    public function getRoles()
    {
        $arreglo = [];
        if ($this->validar()) {
            $usuarios = (new AbmUsuario())->buscar(['idusuario' => $_SESSION['idusuario']]);
            if (count($usuarios) > 0) {
                $resultado = (new AbmUsuarioRol())->buscar(['usuario' => $usuarios[0]]);
                if (count($resultado) > 0) {
                    foreach ($resultado as $unUsuarioRol) {
                        array_push($arreglo, $unUsuarioRol->getObjRol());
                    }
                }
            }
        }
        return $arreglo;
    }

    /**
     * Devuelve verdadero si un usuario es cliente (VERIFICAR QUE ID TIENE EL ROL CLIENTE EN LA BD)
     */
    public function esCliente()
    {
        $roles = $this->getRoles();
        $i = 0;
        $encontrado = false;;
        while (!$encontrado && $i < count($roles)) {
            $encontrado = $roles[$i]->getIdrol() == 3; //En este caso 3 es el id del rol cliente
            $i++;
        }
        return $encontrado;
    }

    /**
     * Crea carrito (compra con estado tipo 1) si fuese el caso
     * retorna $compraEstado del carrito
     */
    public function crearCarrito()
    {
        $compraEstado = null;

        if (!$this->validar()) {
            return null;
        }
        if (!$this->esCliente()) {
            return null;
        }

        $usuario = $this->getUsuario();
        $compras = (new AbmCompra())->buscar(['usuario' => $usuario]);

        $compraEstadoTipos = (new AbmCompraEstadoTipo())->buscar(['idcompraestadotipo' => 1]);
        if (empty($compraEstadoTipos)) {
            return null;
        }

        $tipoCarrito = $compraEstadoTipos[0];

        foreach ($compras as $compra) {
            $compraEstados = (new AbmCompraEstado())->buscar(['objCompra' => $compra]);
            if (empty($compraEstados)) {
                continue;
            }
            $ultimoEstado = null;
            foreach ($compraEstados as $estado) {
                if (
                    $ultimoEstado === null ||
                    new DateTime($estado->getCefechaini()) > new DateTime($ultimoEstado->getCefechaini())
                ) {
                    $ultimoEstado = $estado;
                }
            }
            if ($ultimoEstado === null) {
                continue;
            }
            if (
                $ultimoEstado->getObjCompraEstadoTipo()->getIdcompraestadotipo() == 1 &&
                $ultimoEstado->getCefechafin() === null
            ) {
                return $ultimoEstado;
            }
        }


        $fecha = (new DateTime('now', new DateTimeZone('-03:00')))
            ->format('Y-m-d H:i:s');

        $param = [
            'cofecha' => $fecha,
            'usuario' => $usuario
        ];
        if (!(new AbmCompra())->alta($param)) {
            return null;
        }
        $nuevaCompra = (new AbmCompra())->buscar([
            'usuario' => $usuario,
            'cofecha' => $fecha
        ]);

        if (empty($nuevaCompra)) {
            return null;
        }

        $nuevaCompra = $nuevaCompra[0];

        $estados = (new AbmCompraEstado())->buscar([
            'objCompra' => $nuevaCompra,
            'objCompraEstadoTipo' => $tipoCarrito,
            'cefechafin' => null
        ]);

        if (empty($estados)) {
            return null;
        }

        return $estados[0];
    }

    /**
     * Obtiene todos los menues para el usuario actual segun su rol
     * Retorna un array
     */
    public function getMenues()
    {
        $menues = [];
        if ($this->validar()) {
            $objRol = $this->getRoles()[0];
            $menuRoles = (new AbmMenuRol())->buscar(['rol' => $objRol]);

            // Obtiene menues para dicho rol
            foreach ($menuRoles as $menuRol) {
                array_push($menues, ($menuRol->getObjMenu()));
            }
            foreach ($menues as $menu) { //Busca menues hijos y los agrega al array de menues
                $hijos = (new AbmMenu())->buscar(['idpadre' => $menu->getIdmenu()]);
                $menues = array_merge($menues, $hijos);
            }
        }
        return $menues;
    }

    /**
     * Verifica si la pagina actual puede ser mostrada
     * Retorna un booleano
     */
    public function validarPagina($menues = [])
    {
        if (empty($menues)) return false; // sin menús = sin permisos

        // Normalizamos la URL actual (sin parámetros ni slashes extras)
        $urlActual = strtok(CURRENT_URL, '?'); // elimina query string
        $urlActual = rtrim($urlActual, '/');   // elimina barra final si existe

        foreach ($menues as $menu) {
            $menuUrl = BASE_URL . '/' . ltrim($menu->getMeurl(), '/');
            $menuUrl = rtrim($menuUrl, '/');
            if ($menuUrl === $urlActual) {
                return true;
            }
        }

        return false;
    }

    /**
     * Inicia sesion si es posible con los datos ingresados en $param
     * @param array $param ['usnombre', 'uspass'] uspass(md5)
     */
    public function iniciarSesion($param)
    {
        $exito = isset($param['usnombre']) && isset($param['uspass']);
        if ($exito) { //Verifica que parametros esten definidos
            $exito = $this->iniciar($param['usnombre'], $param['uspass']);
        }
        return $exito;
    }

    /**
     * Cierra la sesión actual.
     */
    public function cerrar()
    {
        $resp = session_destroy();
        return $resp;
    }
}
