<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD_TPFINAL/configuracion.php";
include STRUCTURE_PATH . '/HeaderSeguro.php';
?>

<div class="container my-5">
    <h1>PERFIL</h1>

    <!-- ALERTAS -->
    <div class="mt-3">
        <div id="perfilError" class="alert alert-danger d-none"></div>
        <div id="perfilExito" class="alert alert-success d-none"></div>
    </div>

    <!-- FORMULARIO PERFIL -->
    <form id="perfilForm">

        <!-- NOMBRE (solo texto) -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Nombre</label>
            <p id="perfilNombre" class="form-control-plaintext"></p>
        </div>

        <!-- EMAIL (solo texto) -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Email</label>
            <p id="perfilEmail" class="form-control-plaintext"></p>
        </div>

        <hr>

        <h5 class="mt-3 fw-bold">Cambiar contraseña</h5>

        <div class="mb-3">
            <label class="form-label">Contraseña actual</label>
            <input type="password" id="perfilPassActual" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Nueva contraseña</label>
            <input type="password" id="perfilPassNueva" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Confirmar nueva contraseña</label>
            <input type="password" id="perfilPassConfirm" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary w-100 mt-2">Actualizar perfil</button>
    </form>
</div>

<script>
/* ============================================================
   1) Obtener perfil al cargar la página
   ============================================================ */
$(document).ready(function () {

    $.ajax({
        url: '/PWD_TPFINAL/Vista/Paginas/Perfil/Accion/ObtenerPerfil.php',
        method: 'GET',
        dataType: 'json',

        success: function(response) {
            if (response.success) {
                // Colocar datos en los textos (no inputs)
                $('#perfilNombre').text(response.data.usnombre);
                $('#perfilEmail').text(response.data.usmail);
            } else {
                $('#perfilError').text(response.message).removeClass('d-none');
            }
        },

        error: function() {
            $('#perfilError').text('Fallo al obtener datos del perfil.').removeClass('d-none');
        }
    });

});


/* ============================================================
   2) Envío del formulario (cambio de contraseña)
   ============================================================ */
$('#perfilForm').submit(function(e) {
    e.preventDefault();

    $('#perfilError').addClass('d-none');
    $('#perfilExito').addClass('d-none');

    $.ajax({
        url: '/PWD_TPFINAL/Vista/Paginas/Perfil/Accion/ModificarPerfil.php',
        method: 'POST',
        dataType: 'json',
        data: {
            nombre: $('#perfilNombre').text(),
            email: $('#perfilEmail').text(),
            passActual: $('#perfilPassActual').val(),
            passNueva: $('#perfilPassNueva').val(),
            passConfirm: $('#perfilPassConfirm').val()
        },
        success: function(response) {
            if (response.success) {
                $('#perfilExito').text(response.message).removeClass('d-none');

                // Limpiar campos de contraseña
                $('#perfilPassActual').val('');
                $('#perfilPassNueva').val('');
                $('#perfilPassConfirm').val('');
            } else {
                $('#perfilError').text(response.message).removeClass('d-none');
            }
        },
        error: function() {
            $('#perfilError').text('Error al comunicarse con el servidor.').removeClass('d-none');
        }
    });
});
</script>

<?php include STRUCTURE_PATH . '/Footer.php'; ?>
