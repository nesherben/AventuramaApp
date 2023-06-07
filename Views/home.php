<?php
require_once("Layouts/Header.php");
?>
<link rel="stylesheet" href="Locals/css/home.css">
<section class="content">
  <div class="container-fluid">

    <div class="row" style="min-height: 0">
      <?php foreach ($this->d['lista'] as $key => $value) : ?>
      <h2 class="head-sticky border-bottom shadow-sm"><?php echo $key?></h2>
        <?php foreach($value as $elem) : ?>
        <div class="col-auto mb-3" style="width: 360px; height:350px">
          <a href="inscripcion?elem=<?php echo $elem['codigo']?>" class="hoverable">
            <div class="card shadow-sm">
              <img width="360px" height="200px" src="<?php echo ($elem['imagen'] != "" ? $elem['imagen'] : "logopie.png")?>" class="card-img-top" alt="...">
              <div class="card-body p-0">
                <h5 class="card-title border-bottom m-0 p-2"><?php echo $elem['nombre']?></h5>
                <p class="card-text p-2 mb-2" style="height: 100px; overflow: auto"><?php echo $elem['descripcion']?></p>
              </div>
            </div>
          </a>
        </div>
        <?php endforeach ?>
      <?php endforeach ?>
        
      </div>
      
  </div>
</section>
<?php
require_once("Layouts/Footer.php");
?>