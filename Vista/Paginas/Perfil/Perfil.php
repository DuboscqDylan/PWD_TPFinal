<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD_TPFINAL/configuracion.php";
include STRUCTURE_PATH . '/HeaderSeguro.php';
?>

<div class="container my-5">
    <h1>PERFIL</h1>
    <form id="perfilForm">

        <div class="mb-3">
            <label class="form-label fw-semibold">Nombre</label>
            <input type="text" id="perfilNombre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Email</label>
            <input type="email" id="perfilEmail" class="form-control" required>
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

        <button class="btn btn-primary w-100 mt-2">Actualizar perfil</button>
    </form>

    <div class="mt-3">
        <div id="perfilError" class="alert alert-danger d-none"></div>
        <div id="perfilExito" class="alert alert-success d-none"></div>
    </div>
</div>
<script>
    $('#perfilForm').submit(function(e) {
        e.preventDefault();

        $('#perfilError').addClass('d-none');
        $('#perfilExito').addClass('d-none');

        $.ajax({
            url: '/PWD_TPFINAL/Vista/Paginas/Perfil/Accion/ModificarPerfil.php',
            method: 'POST',
            data: {
                nombre: $('#perfilNombre').val(),
                email: $('#perfilEmail').val(),
                passActual: $('#perfilPassActual').val(),
                passNueva: $('#perfilPassNueva').val(),
                passConfirm: $('#perfilPassConfirm').val()
            },
            success: function(response) {
                if (response.success) {
                    $('#perfilExito').text(response.message).removeClass('d-none');
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