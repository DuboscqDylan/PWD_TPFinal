<?php
include_once ROOT_PATH.'/Control/Session.php';

$session = new Session();
$sesionValida = $session->validar();
$menues =  [];
$compraEstado = null;

if($sesionValida){
    $usuario = $sesion->getUsuario();
    $menues = $sesion->getMenues();
    $compraEstado = $sesion->crearCarrito();

    if (!$sesion->validarPagina($menues)) {
        header("Location: ".BASE_URL."/Vista/Paginas/SesionInvalida/SesionInvalida.php");
        exit();
    }
}else{
    header("Location: ".BASE_URL."/Vista/Paginas/SesionInvalida/SesionInvalida.php");
    exit();
}

$menuHtml = "<div class=''>
            <a class='' href'".BASE_URL."/Vista/Paginas/Catalogo/Catalogo.php>Catálogo</a>";

foreach($menues as $menu){
    if($menu->getPadre() != null){
        $menuHtml .= "<a class='' href='".BASE_URL.$menu->getMeurl()."'> ".$menu->getMenombre()." </a>";
    }
}

$menuHtml .= "</div>";

if($sesionValida) {
    $menuHtml .= "<a class='' href='".BASE_URL."/Vista/Paginas/Perfil/Perfil.php'>".$usuario->getUsnombre()."</a>
    <a class='' href='".BASE_URL."/Vista/Paginas/Logout/Logout.php'>Logout</a>";
}else{
    $menuHtml .= "<a class='' href='".BASE_URL."/Vista/Paginas/Login/Login.php'>Login</a>
    <a class='' href='".BASE_URL."/Vista/Paginas/Registro/Registro.php'>Registro</a>";
}

$menuHtml .= "</div>";

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Programación web dinamica</title>

    <link href="<?php echo BASE_URL; ?> /Vista/Recursos/css/bootstrap/bootstrap.min.css" rel="stylesheet">

    <link href="<?php echo BASE_URL; ?> /Vista/Recursos/js/jquery-easyui-1.11.0/themes/bootstrap/easyui.css" rel="stylesheet">

    <link href="<?php echo BASE_URL; ?> /Vista/Recursos/css/styles.css" rel="stylesheet">

    <script src="<?php echo BASE_URL; ?>/Vista/Recursos/js/bootstrap/bootstrap.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/Vista/Recursos/js/bootstrap/bootstrap.bundle.js"></script>

    <script src="<?php echo BASE_URL; ?>/Vista/Recursos/js/jquery-3.7.1.min.js"></script>

    <script src="<?php echo BASE_URL; ?>/Vista/Recursos/js/jquery-easyui-1.11.0/jquery.easyui.min.js"></script>

    <script src="<?php echo BASE_URL; ?>/Vista/Recursos/js/md5.min.js"></script>

</head>

<body class="">
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
            <div class="">
                <a class="" href="<?php echo BASE_URL; ?>/index.php">
                    <div class="">
                        <img src="<?php echo(BASE_URL);?>/Vista/Media/Sitio/Logo.png" height="50" width="50"></img>
                        <h4 class=""> BIKE SHOP </h4>
                    </div>
                </a>
            </div>

            <?php echo $menuHtml ?>
        </nav>
    </header>    