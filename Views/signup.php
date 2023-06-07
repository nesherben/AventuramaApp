<?php

require_once 'Layouts/Header.php';
?>
<section class="content">

    <div class="d-flex flex-column card p-0 m-auto" style="min-width: 400px" id="LoginFormContainer">

        <div class="card-header w-100 d-flex justify-content-between align-center">
            <h3>Registro</h3>
            <a href="#" data-bs-target="#AccModal" data-bs-toggle="modal">¿necesitas activar tu cuenta?</a>
        </div>

        <form action="signup/newUser" id="formSignup" method="POST">
            <div class="card-body">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group row">
                    <div class="col">
                        <label for="contrasena">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="col">
                        <label for="contrasena">Confirmar Contraseña</label>
                        <input type="password" class="form-control" id="password2" name="password2" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="apellidos">Apellidos</label>
                    <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" class="form-control" id="telefono" name="telefono" required>
                </div>
                <div class="d-flex h-auto gap-2 pt-2">
                    <input type="checkbox" id="terminos" name="terminos" required/>
                    <p class="m-0">He leido y acepto los</p><a class="text-dark" data-bs-toggle="modal" href="#terminosModal" type="button">terminos y condiciones</a>
                </div>
            </div>
            <div class="d-flex flex-wrap text-nowrap card-footer gap-3 justify-content-between">
                <a href="/" type="button" class="col btn btn-primary btn-block">Cancelar</a>
                <button type="submit" id="registrobtn" disabled class="col btn btn-primary btn-block">Registrarse</button>
            </div>
        </form>
    </div>
</section>

<div class="modal fade" id="AccModal">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Reenviar activación</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="signup/reactivate" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="emailRec" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Contraseña</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Confimar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="Locals/js/signup.js"></script>

<?php
require_once 'Layouts/Footer.php';
?>