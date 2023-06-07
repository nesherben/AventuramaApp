<?php require_once("Layouts/Header.php"); ?>
<script type="module">

</script>
<link href="<?php echo baseUrl ?>locals/css/login.css" rel="stylesheet">
</script>
<section class="content">
    <div class=" d-flex flex-column card p-0 m-auto" id="LoginFormContainer">

        <div class="card-header">
            <h3>Iniciar sesión</h3>
        </div>
        <form action="<?php echo baseUrl ?>login/authenticate" method="POST">
            <div class="col-md-12 card-body">
                <div class="form-group">
                    <label for="email">Email de usuario:</label>
                    <input type="email" id="username" name="email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" class="form-control">
                </div>
                <a href="?" data-bs-toggle="modal" data-bs-target="#PassModal">Contraseña olvidada</a>
            </div>

            <div class="card-footer d-flex flex-wrap text-nowrap gap-3 justify-content-between">
                <a href="<?php echo baseUrl ?>signup" type="button" class="col btn btn-primary btn-block">Registrarse</a>
                <button type="submit" class="col btn btn-primary btn-block">Iniciar sesión</button>
            </div>
        </form>        
    </div>
</section>

<div class="modal fade" id="PassModal">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Recuperar contraseña</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo baseUrl ?>login/recuperarPassword" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="emailRec" name="email" required>
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

<?php

require_once("Layouts/Footer.php");

?>