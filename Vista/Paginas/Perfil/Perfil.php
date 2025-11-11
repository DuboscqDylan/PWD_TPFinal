<?php
include_once '../../../configuracion.php';
include STRUCTURE_PATH . '/Head.php';
if(!$sesion->validar()) {
    header("Location: ".BASE_URL."/View/Pages/SesionInvalida/SesionInvalida.php");
    exit();
}
?>

<div class="">
    <h1>PERFIL</h1>
</div>

<?php include STRUCTURE_PATH . '/Foot.php'; ?>
