<?php

require_once 'Layouts/Header.php';
?>
<section class="content">

    <div class="d-flex flex-column card p-0 m-auto" style="min-width: 400px" id="LoginFormContainer">

        <div class="card-header w-100 d-flex justify-content-between align-center">
            <h3>Recuperar contraseña</h3>
        </div>

        <form action="recoverPassword/recover" method="POST">
            <div class="card-body">
                <div class="form-group row">
                    <input hidden type="text" class="form-control" id="email" name="email" value="<?php echo $this->d['email'] ?? "" ?>" required>
                    <input hidden type="text" class="form-control" id="token" name="token" value="<?php echo $this->d['token'] ?? "" ?>" required>
                    <div class="col">
                        <label for="contrasena">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="col">
                        <label for="contrasena">Confirmar Contraseña</label>
                        <input type="password" class="form-control" id="password2" name="password2" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Confimar</button>
            </div>
        </form>
    </div>
</section>


<?php
require_once 'Layouts/Footer.php';
?>