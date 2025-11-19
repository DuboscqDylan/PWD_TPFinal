<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD_TPFINAL/configuracion.php";
include STRUCTURE_PATH . '/Header.php';

if ($sesion->validar()) { //Si ya tiene una sesion, redirige a Catalogo
    header('Location: ' . BASE_URL . '/Vista/Paginas/Catalogo/Catalogo.php');
}
?>

<!-- Contenedor principal -->
<div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow p-4 bg-primary text-white" style="max-width: 400px; width: 100%; border-radius: 10px;">
        <h2 class="text-center mb-4 fw-bold">Registrarse</h2>

        <form id="registerForm" class="d-flex flex-column gap-3">
            <input
                type="text"
                name="user"
                id="user"
                placeholder="Usuario"
                class="form-control border-0 border-bottom bg-transparent text-white"
                style="outline: none; box-shadow: none;"
                required>

            <input
                type="email"
                name="email"
                id="email"
                placeholder="Email"
                class="form-control border-0 border-bottom bg-transparent text-white"
                style="outline: none; box-shadow: none;"
                required>

            <input
                type="password"
                name="password"
                id="password"
                placeholder="Contraseña"
                class="form-control border-0 border-bottom bg-transparent text-white"
                style="outline: none; box-shadow: none;"
                required>

            <button type="submit" class="btn btn-light text-primary fw-bold w-100 mt-3">Registrarse</button>
        </form>

        <div class="mt-3 text-center">
            <div id="errorMessage" class="bg-danger text-white fw-semibold rounded p-2 d-none"></div>
            <div id="successMessage" class="bg-success text-white fw-semibold rounded p-2 d-none"></div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#registerForm').on('submit', function(e) {
            e.preventDefault();
            $('#errorMessage').text('').removeClass('d-block').addClass('d-none');
            $('#successMessage').text('').removeClass('d-block').addClass('d-none');
            const formData = {
                usnombre: $('#user').val(),
                usmail: $('#email').val(),
                uspass: md5($('#password').val()),
                idrol: 3 //Crea Cliente por este medio, los demas roles los crea un admin
            };
            $.ajax({
                url: 'Accion/AccionRegistrar.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.trim() === 'success') {
                        $('#successMessage')
                            .text('Registro exitoso. Redirigiendo al login...')
                            .removeClass('d-none')
                            .addClass('d-block');
                        setTimeout(function() {
                            window.location.href = "<?php echo BASE_URL ?>/Vista/Paginas/Login/Login.php";
                        }, 2000);
                    } else {
                        $('#errorMessage')
                            .text(response)
                            .removeClass('d-none')
                            .addClass('d-block');
                    }
                },
                error: function() {
                    $('#errorMessage')
                        .text('Ocurrió un error al procesar la solicitud.')
                        .removeClass('d-none')
                        .addClass('d-block');
                }
            });
        });
    });
</script>

<?php include STRUCTURE_PATH . '/Footer.php'; ?>