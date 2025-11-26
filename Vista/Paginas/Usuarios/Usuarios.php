<?php 
include_once $_SERVER['DOCUMENT_ROOT']."/PWD_TPFINAL/configuracion.php";
include STRUCTURE_PATH . "/HeaderSeguro.php"; 
?>

<!-- Contenido principal -->
<div class="container my-5">

    <h1 class="text-center fw-bold text-primary mb-5">Administrar Usuarios</h1>

    <div class="row g-4 justify-content-center">

        <!-- Tabla de Usuarios -->
        <div class="col-12 col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body">
                    <h4 class="card-title text-secondary mb-3">Listado de Usuarios</h4>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="usuariosTable">
                            <thead class="table-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Estado</th>
                                    <th>Rol</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de Alta -->
        <div class="col-12 col-lg-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body">
                    <h4 class="card-title text-success mb-3">Crear Usuario</h4>
                    <form id="altaUsuarioForm">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nombre</label>
                            <input type="text" class="form-control" id="user" name="user" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Rol</label>
                            <select class="form-select" id="rol" name="rol" required>
                                <option value="">Seleccionar...</option>
                                <option value="1">Administrador</option>
                                <option value="2">Depósito</option>
                                <option value="3">Cliente</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success w-100 mt-3">Crear Usuario</button>
                    </form>

                    <div class="mt-3">
                        <div id="errorMessage" class="alert alert-danger d-none"></div>
                        <div id="successMessage" class="alert alert-success d-none"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de Modificar (OCULTO) -->
        <div class="col-12 col-lg-5" id="modificarUsuarioCard" style="display:none;">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body">
                    <h4 class="card-title text-primary mb-3">Modificar Usuario</h4>

                    <form id="modificarUsuarioForm">

                        <!-- ID oculto -->
                        <input type="hidden" id="usuarioID">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nombre</label>
                            <input type="text" class="form-control" id="modNombre">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control" id="modEmail">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Rol</label>
                            <select class="form-select" id="modRol">
                                <option value="">Seleccionar...</option>
                                <option value="Administrador">Administrador</option>
                                <option value="Deposito">Depósito</option>
                                <option value="Cliente">Cliente</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mt-3">Actualizar Usuario</button>
                    </form>

                    <div class="mt-3">
                        <div id="errorMessageMod" class="alert alert-danger d-none"></div>
                        <div id="successMessageMod" class="alert alert-success d-none"></div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<script src="<?php echo BASE_URL; ?>/Vista/Recursos/js/md5.min.js"></script>

<script>
$(document).ready(function() {

    cargarUsuarios();

    // -----------------------------------
    // LISTAR USUARIOS
    // -----------------------------------
    function cargarUsuarios() {
        $.ajax({
            url: '/PWD_TPFINAL/Vista/Paginas/Usuarios/Accion/ListarUsuarios.php',
            method: 'POST',
            data: { todo: true },
            dataType: 'json',
            success: function(response) {

                let tableContent = '';

                $.each(response, function(index, usuario) {

                    const botonEstado = usuario.usdeshabilitado 
                        ? `<button class="my-1 btn btn-success btn-sm" onclick="habilitarUsuario(${usuario.idusuario})">Habilitar</button>`
                        : `<button class="my-1 btn btn-warning btn-sm" onclick="bajaUsuario(${usuario.idusuario}, '${usuario.rol}')">Deshabilitar</button>`;

                    tableContent += `
                        <tr id="usuario-${usuario.idusuario}">
                            <td>${usuario.idusuario}</td>
                            <td>${usuario.usnombre}</td>
                            <td>${usuario.usmail}</td>
                            <td>${usuario.usdeshabilitado}</td>
                            <td>${usuario.rol}</td>
                            <td>
                                <div class="d-flex flex-column align-items-start">
                                    <button class="my-1 btn btn-info btn-sm" onclick="modificarUsuario(${usuario.idusuario})">Modificar</button>
                                    ${botonEstado}
                                </div>
                            </td>
                        </tr>
                    `;
                });

                $('#usuariosTable tbody').html(tableContent);
            }
        });
    }

    // -----------------------------------
    // CLIC EN MODIFICAR
    // -----------------------------------
    window.modificarUsuario = function(id) {

        $.ajax({
            url: '/PWD_TPFINAL/Vista/Paginas/Usuarios/Accion/ListarUsuarios.php',
            method: 'POST',
            data: { idusuario: id },
            dataType: 'json',
            success: function(response) {

                if (response.length > 0) {
                    const u = response[0];

                    $('#usuarioID').val(u.idusuario);
                    $('#modNombre').val(u.usnombre);
                    $('#modEmail').val(u.usmail);
                    $('#modRol').val(u.rol);

                    $('#modificarUsuarioCard').slideDown();
                }
            }
        });
    }

    // -----------------------------------
    // SUBMIT MODIFICAR USUARIO
    // -----------------------------------
    $('#modificarUsuarioForm').submit(function(e) {
        e.preventDefault();

        let formData = {
            usuarioID: $('#usuarioID').val(),
            modNombre: $('#modNombre').val(),
            modEmail: $('#modEmail').val(),
            modRol: $('#modRol').val()
        };

        $.ajax({
            url: '/PWD_TPFINAL/Vista/Paginas/Usuarios/Accion/ModificarUsuario.php',
            type: 'POST',
            data: formData,
            success: function(res) {

                res = JSON.parse(res);

                if (res.success) {

                    $('#successMessageMod')
                        .text(res.message)
                        .removeClass('d-none')
                        .addClass('d-block');

                    cargarUsuarios();

                    // Ocultar formulario
                    $('#modificarUsuarioCard').slideUp();
                    $('#modificarUsuarioForm')[0].reset();

                } else {
                    $('#errorMessageMod')
                        .text(res.message)
                        .removeClass('d-none')
                        .addClass('d-block');
                }
            }
        });

    });

    // -----------------------------------
    // BAJA USUARIO
    // -----------------------------------
    window.bajaUsuario = function(id, rol) {
        if (confirm('¿Seguro desea deshabilitar al usuario?')) {
            $.ajax({
                url: '/PWD_TPFINAL/Vista/Paginas/Usuarios/Accion/BajaUsuario.php',
                method: 'POST',
                data: { idusuario: id, rol: rol },
                dataType: 'json',
                success: function(resp) {
                    alert(resp.message);
                    cargarUsuarios();
                }
            });
        }
    }

    // -----------------------------------
    // HABILITAR USUARIO
    // -----------------------------------
    window.habilitarUsuario = function(id) {
        if (confirm('¿Habilitar usuario?')) {
            $.ajax({
                url: '/PWD_TPFINAL/Vista/Paginas/Usuarios/Accion/HabilitarUsuario.php',
                type: 'POST',
                data: { idusuario: id },
                dataType: 'json',
                success: function(resp) {
                    alert(resp.message);
                    cargarUsuarios();
                }
            });
        }
    }

    // -----------------------------------
    // ALTA USUARIO
    // -----------------------------------
    $('#altaUsuarioForm').submit(function(e) {
        e.preventDefault();

        const passMD5 = md5($('#password').val());

        const formData = {
            user: $('#user').val(),
            email: $('#email').val(),
            password: passMD5,
            rol: $('#rol').val()
        };

        $.ajax({
            url: '/PWD_TPFINAL/Vista/Paginas/Usuarios/Accion/AltaUsuario.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(resp) {

                if (resp.success) {
                    $('#successMessage')
                        .text(resp.message)
                        .removeClass('d-none')
                        .addClass('d-block');

                    cargarUsuarios();

                } else {
                    $('#errorMessage')
                        .text(resp.message)
                        .removeClass('d-none')
                        .addClass('d-block');
                }
            }
        });

    });

});
</script>

<?php include STRUCTURE_PATH . "/Footer.php"; ?>
