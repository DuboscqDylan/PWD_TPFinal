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
                            <tbody>
                                <!-- Los usuarios son cargados dinámicamente acá -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>



        <!-- Formulario de Alta de Usuario -->
        <div class="col-12 col-lg-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body">
                    <h4 class="card-title text-success mb-3">Crear Usuario</h4>
                    <form id="altaUsuarioForm">
                        <div class="mb-3">
                            <label for="user" class="form-label fw-semibold">Nombre</label>
                            <input type="text" class="form-control" id="user" name="user" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="rol" class="form-label fw-semibold">Rol</label>
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

        <!-- Formulario de Modificar Usuario -->
        <div class="col-12 col-lg-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body">
                    <h4 class="card-title text-primary mb-3">Modificar Usuario</h4>
                    <form id="modificarUsuarioForm">
                        <div class="mb-3">
                            <label for="usuarioID" class="form-label fw-semibold">ID</label>
                            <input type="text" class="form-control" id="usuarioID" name="usuarioID" required>
                        </div>
                        <div class="mb-3">
                            <label for="modNombre" class="form-label fw-semibold">Nombre</label>
                            <input type="text" class="form-control" id="modNombre" name="modNombre">
                        </div>
                        <div class="mb-3">
                            <label for="modEmail" class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control" id="modEmail" name="modEmail">
                        </div>
                        <div class="mb-3">
                            <label for="modRol" class="form-label fw-semibold">Rol</label>
                            <select class="form-select" id="modRol" name="modRol">
                                <option value="">Seleccionar...</option>
                                <option value="Administrador">Administrador</option>
                                <option value="Deposito">Depósito</option>
                                <option value="Cliente">Cliente</option>
                            </select>
                        </div>
                        <input type="hidden" id="modUserId">
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
        cargarUsuarios(); // Funcion que es llamada al cargar la página, y contiene la llamada AJAX para cargar los usuarios

        function cargarUsuarios() {
            $.ajax({
                url: '/PWD_TPFINAL/Vista/Paginas/Usuarios/Accion/ListarUsuarios.php',
                method: 'POST',
                data: {
                    todo: true
                },
                dataType: 'json',
                success: function(response) {
                    var tableContent = '';
                    $.each(response, function(index, usuario) {
                        const estado = usuario.usdeshabilitado ? 'Deshabilitado' : 'Disponible';
                        const botonEstado = usuario.usdeshabilitado ?
                            `<button class="my-1 btn btn-success btn-sm" onclick="habilitarUsuario(${usuario.idusuario})">Habilitar</button>` :
                            `<button class="my-1 btn btn-warning btn-sm" onclick="bajaUsuario(${usuario.idusuario}, '${usuario.rol}')">Deshabilitar</button>`;

                        tableContent += `
                        <tr id="usuario-${usuario.idusuario}">
                            <td>${usuario.idusuario}</td>
                            <td>${usuario.usnombre}</td>
                            <td>${usuario.usmail}</td>
                            <td>${usuario.usdeshabilitado} </td>
                            <td>${usuario.rol}</td>
                             <td>
                                    <div class="d-flex flex-column align-items-start">
                                        ${botonEstado}
                                    </div>
                                </td>
                        </tr>
                    `;
                    });
                    $('#usuariosTable tbody').html(tableContent);
                },
                error: function() {
                    alert('Error al cargar los usuarios.');
                }
            });
        }

        function modificarUsuario(id) {
            $.ajax({
                url: '/PWD_TPFINAL/Vista/Paginas/Usuarios/Accion/ListarUsuarios.php',
                method: 'POST',
                data: {
                    idusuario: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.length > 0) {
                        const usuario = response[0];
                        $('#modUserId').val(usuario.idusuario);
                        $('#modNombre').val(usuario.usnombre);
                        $('#modEmail').val(usuario.usmail);
                        $('#modRol').val(usuario.rol);
                        $('#modificarUsuarioForm').show();
                    }
                },
                error: function() {
                    alert('Error al cargar la información del usuario.');
                }
            });
        }

        // Manejo de la baja de un usuario (lo deshabilita)
        window.bajaUsuario = function(id, rol) {
            if (confirm('¿Estás seguro de dar de baja este usuario?')) {
                $.ajax({
                    url: '/PWD_TPFINAL/Vista/Paginas/Usuarios/Accion/BajaUsuario.php',
                    method: 'POST',
                    data: {
                        idusuario: id,
                        rol: rol
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response) {
                            alert(response.message);
                            // Eliminar la fila de la tabla
                            //$('#usuario-' + id).remove();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert('Error al procesar la solicitud de baja.');
                    }
                });
            }
        }

         // habilitado de usuario
        window.habilitarUsuario = function(idusuario) {
            if (confirm('¿Está seguro que desea habilitar el usuario?')) {
                $.ajax({
                    url: '/PWD_TPFINAL/Vista/Paginas/Usuarios/Accion/HabilitarUsuario.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        idusuario: idusuario 
                    },
                    success: function(response) {
                        if (response) {
                            alert(response.message);
                            cargarUsuarios(); // Recargar la lista 
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert('Ocurrió un error al procesar la solicitud.');
                    }
                });
            }
        };

        // Alta de usuario
        $('#altaUsuarioForm').submit(function(e) {
            e.preventDefault();
            $('#errorMessage').text('').removeClass('d-block').addClass('d-none');
            $('#successMessage').text('').removeClass('d-block').addClass('d-none');
            pass = $('#password').val()
            passMD5 = md5(pass)
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
                success: function(response) {
                    if (response.success) {
                        $('#errorMessage')
                            .text(response.message)
                            .removeClass('d-block')
                            .addClass('d-none');
                        $('#successMessage')
                            .text(response.message)
                            .removeClass('d-none')
                            .addClass('d-block');
                        cargarUsuarios(); // Recargar la lista de usuarios
                    } else {
                        $('#successMessage')
                            .text('Usuario creado exitosamente.')
                            .removeClass('d-block')
                            .addClass('d-none');
                        $('#errorMessage')
                            .text(response.message)
                            .removeClass('d-none')
                            .addClass('d-block');
                    }
                },
                error: function() {
                    $('#successMessage')
                            .text('Usuario creado exitosamente.')
                            .removeClass('d-block')
                            .addClass('d-none');
                    $('#errorMessage')
                        .text('Ocurrió un error al procesar la solicitud.')
                        .removeClass('d-none')
                        .addClass('d-block');
                }
            });
                
        });


        // Modificación de usuario
       $('#modificarUsuarioForm').submit(function(e) {
            e.preventDefault();
            $('#errorMessage').text('').removeClass('d-block').addClass('d-none');
            $('#successMessage').text('').removeClass('d-block').addClass('d-none');
            
            var formData = {
                usuarioID: parseInt($('#usuarioID').val(), 10),
            }
            if ($('#modNombre').val().trim() != "") {
                formData['modNombre'] = $('#modNombre').val();
            }
            if ($('#modEmail').val().trim() != "") {
                formData['modEmail'] = $('#modEmail').val();
            }
            if ($('#modRol').val().trim() != "") {
                formData['modRol'] = $('#modRol').val();
            }

            console.log(formData);
            $.ajax({
                url: '/PWD_TPFINAL/Vista/Paginas/Usuarios/Accion/ModificarUsuario.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    var res = JSON.parse(response);
                    console.log(res);
                    if (res.success) {
                        $('#errorMessageMod')
                            .text(res.message)
                            .removeClass('d-block')
                            .addClass('d-none');
                        $('#successMessageMod')
                            .text(res.message)
                            .removeClass('d-none')
                            .addClass('d-block');
                        cargarUsuarios(); // Recargar la lista de usuarios
                    } else {
                        $('#successMessageMod')
                            .text(res.message)
                            .removeClass('d-block')
                            .addClass('d-none');
                        cargarUsuarios();
                        $('#errorMessageMod')
                            .text(res.message)
                            .removeClass('d-none')
                            .addClass('d-block');
                    }
                },
                error: function() {
                    $('#successMessageMod')
                            .text(res.message)
                            .removeClass('d-block')
                            .addClass('d-none');
                    $('#errorMessageMod')
                        .text('Ocurrió un error al procesar la solicitud.')
                        .removeClass('d-none')
                        .addClass('d-block');
                }
            });
        });

    });
</script>

<?php include STRUCTURE_PATH . "/Footer.php"; ?>