<?php
include_once '../../../configuracion.php';
include STRUCTURE_PATH . '/Head.php';
if(!$sesion->validar()) {
    header("Location: ".BASE_URL."/View/Pages/SesionInvalida/SesionInvalida.php");
    exit();
}
?>

<div class="d-flex justify-content-center align-items-start gap-3">
    <h1>PERFIL</h1>
</div>

<?php include STRUCTURE_PATH . '/Foot.php'; ?>
