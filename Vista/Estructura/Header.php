<?php

$session = new Session();
$sesionValida = $session->validar();
$menues =  [];
$compraEstado = null;

if($sesionValida){
    $usuario = $sesion->getUsuario();
    $menues = $sesion->getMenues();
    $compraEstado = $sesion->crearCarrito();
}

$menuHtml = "<div class=''>
            <a class='' href'".BASE_URL."/Vista/Paginas/Catalogo/Catalogo.php>Catálogo</a>";

foreach($menues as $menu){
    if($menu->getPadre() != null){
        $menuHtml .= "<a class='' href='".BASE_URL.$menu->getMeurl()."'> ".$menu->getMenombre()." </a>";
    }
}

$menuHtml .= "</div>"

if($sesionValida) {
    $menuHtml .= "<a class='' href='".BASE_URL."/Vista/Paginas/Perfil/Perfil.php'>".$usuario->getUsnombre()."</a>";
}

?>