<?php
$this->loadCSSJSMessages();
$this->showMessages();
header('Content-Type: text/html; charset=UTF-8');

?>

<head>
    <meta charset="utf-8">
    <title>Aventurama</title>
    <input hidden class="baseUrl" value="<?php echo baseUrl ?>">
    <link href="<?php echo baseUrl ?>locals/css/site.css" rel="stylesheet">
    <script type="module" src="<?php echo baseUrl ?>locals/js/site.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>

    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo baseUrl ?>locals/assets/ico/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo baseUrl ?>locals/assets/ico/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo baseUrl ?>locals/assets/ico/favicon-16x16.png">
    <link rel="manifest" href="<?php echo baseUrl ?>locals/assets/ico/site.webmanifest">
    <link rel="mask-icon" href="<?php echo baseUrl ?>locals/assets/ico/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#00aba9">
    <meta name="theme-color" content="#ffffff">

    <div class="bg-dark d-flex shadow" id="header">
        <img src="https://aventurama.es/wp-content/uploads/logotop1.jpg" style="cursor: pointer; position: relative; width: auto; height: 100%;" onclick="window.location.href = ('<?= baseUrl ?>')">
        <?php if (isset($_SESSION['user']) && isset($this->d['user'])) : ?>
            <ul class="nav justify-content-center overflow-auto">
                <li class="nav-item"><a class="btn h-100 btn-dark" href="<?php echo baseUrl ?>home">Inicio</a></li>
                <li class="nav-item"><a class="btn h-100 btn-dark" href="<?php echo baseUrl ?>perfil">Perfil</a></li>
                <?php if ($this->d['user']->getRole() == "admin") : ?>
                    <li class="nav-item"><a class="btn h-100 btn-danger" href="<?php echo baseUrl ?>adminpage">Admin</a></li>
                <?php endif; ?>
            </ul>
            <div class="ms-auto px-1 my-1  justify-content-between overflow-auto">
                <?php echo explode('@', $this->d['user']->getEmail())[0] ?>
                <a class="btn btn-sm btn-light" href="<?php echo baseUrl ?>logout" style="width:8rem;height:100%">
                    Cerrar Sesion
                </a>
            </div>
        <?php endif; ?>
    </div>


</head>