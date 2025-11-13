<?php
include_once $_SERVER['DOCUMENT_ROOT']."/PWD_TPFINAL/configuracion.php";
include STRUCTURE_PATH . '/Header.php';
if(!$sesion->validar()) {
    header("Location: ".BASE_URL."/Vista/Paginas/SesionInvalida/SesionInvalida.php");
    exit();
}
?>

<div class="container my-5">
    <h1>PERFIL</h1>
</div>

<?php include STRUCTURE_PATH . '/Footer.php'; ?>
