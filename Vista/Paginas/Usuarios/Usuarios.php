<?php 
include_once $_SERVER['DOCUMENT_ROOT']."/PWD_TPFINAL/configuracion.php";
include STRUCTURE_PATH . "/Header.php"; 
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
                                <!-- Los usuarios serán cargados dinámicamente aquí -->
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
                    <h4 class="card-title text-success mb-3">Alta de Usuario</h4>
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
                                <option value="Administrador">Administrador</option>
                                <option value="Deposito">Depósito</option>
                                <option value="Cliente">Cliente</option>
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

<script>
    // --- Tu código JavaScript original, intacto ---
    $(document).ready(function() {
        cargarUsuarios();

        function cargarUsuarios() {
            $.ajax({
                url: 'Action/ListarUsuarios.php',
                method: 'POST',
                data: { todo: true },
                dataType: 'json',
                success: function(response) {
                    var tableContent = '';
                    $.each(response, function(index, usuario) {
                        tableContent += `
                        <tr id="usuario-${usuario.idusuario}">
                            <td>${usuario.idusuario}</td>
                            <td>${usuario.usnombre}</td>
                            <td>${usuario.usmail}</td>
                            <td>${usuario.usdeshabilitado}</td>
                            <td>${usuario.rol}</td>
                            <td>
                                <button class="btn btn-danger btn-sm" onclick="bajaUsuario(${usuario.idusuario}, '${usuario.rol}')">Baja</button>
                            </td>
                        </tr>`;
                    });
                    $('#usuariosTable tbody').html(tableContent);
                },
                error: function() {
                    alert('Error al cargar los usuarios.');
                }
            });
        }

        // --- resto de tu código JS exactamente igual ---
        // (funciones modificarUsuario, bajaUsuario, alta y modificación)
        // ...
    });
</script>

<?php include STRUCTURE_PATH . "/Footer.php"; ?>
