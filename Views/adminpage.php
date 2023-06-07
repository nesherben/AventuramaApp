<?php
require_once("Layouts/Header.php");

$categorias = $this->d['categorias'];
$turnos = $this->d['turnos'];
$actividades = $this->d['actividades'];
$reservas = $this->d['reservas'];
$ninos = $this->d['ninos'];

?>
<section class="content">
  <div class="container-fluid overflex py-2 gap-1 h-100">
    <div class="card">
      <div class="card-header border-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        <h5 class="mb-0">
          Gestion de reservas
        </h5>
      </div>
      <form class="p-2">
        <div class="row">
          <div class="col">
            <select class="form-select" name="turnor" aria-label="Default select example">
              <option value="null" selected>Todos los turnos</option>
              <?php foreach ($turnos as $turno) : ?>
                <option value="<?php echo $turno['CD_TURNO'] ?>"><?php echo $turno['DS_TURNO'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col">
            <select class="form-select" name="actividadr" aria-label="Default select example">
              <option value="null" selected>Todas las actividades</option>
              <?php foreach ($actividades as $actividad) : ?>
                <option value="<?php echo $actividad['actividad']['CD_ACTIVIDAD'] ?>"><?php echo $actividad['actividad']['NM_ACTIVIDAD'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col">
            <input type="text" name="ninor" id="nino" class="form-control" placeholder="Nombre del niño">
          </div>
          <div class="col">
            <select class="form-select" name="estador" aria-label="Default select example">
              <option value="null" selected>Todos los estados</option>
              <option value="0">NO VALIDADO</option>
              <option value="1">VALIDADO</option>
              <option value="2">CANCELADO</option>
              <option value="3">PAGADO</option>
              <option value="4">REEMBOLSADO</option>
              <option value="5">FINALIZADO</option>
              <option value="6">EN CURSO</option>
            </select>
          </div>
          <div class="col-1">
            <button class="text-nowrap w-100 btn btn-secondary" type="submit">Filtrar</button>
          </div>
        </div>
      </form>
      <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
        <div class="">
          <div style="height: 60vh; overflow:auto">
            <table class="table table-sticky table-stripped table-hover">
              <thead class="bg-light">
                <tr>
                  <th></th>
                  <th scope="col">Fecha reserva</th>
                  <th scope="col">Email usuario</th>
                  <th scope="col">Actividad</th>
                  <th scope="col">Turno</th>
                  <th scope="col">Niño</th>
                  <th scope="col">Estado</th>
                  <th scope="col">Precio</th>
                </tr>
              </thead>
              <tbody id="RBody">
                <?php if ($reservas != false) foreach ($reservas as $reserva) : ?>
                  <tr style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#R<?php echo $reserva['ID_RESERVA'] ?>">
                    <td style="width: 0px">
                      <div class="d-flex flex-nowrap gap-1">
                        <span style="width: auto;">🔽</span>
                      </div>
                    </td>
                    <td class="text-nowrap"><?php echo $reserva["FH_RESERVA"] ?></td>
                    <td class="text-nowrap"><?php echo $reserva["EMAIL"] ?></td>
                    <td class="text-nowrap"><?php echo $reserva["NM_ACTIVIDAD"] ?></td>
                    <td class="text-nowrap"><?php echo $reserva["DS_TURNO"] ?></td>
                    <td class="text-nowrap"><?php echo $reserva["NOMBRE"] . " " . $reserva["APELLIDOS"] ?></td>
                    <td class="text-nowrap"><?php echo $reserva["DS_ESTADO"] ?></td>
                    <td class="text-nowrap"><?php echo $reserva["PRECIO"] ?> €</td>
                  </tr>
                  <tr class="collapse-row">
                    <td class="p-0" colspan="12">
                      <div class="collapse" data-bs-parent="#RBody" id="R<?php echo $reserva['ID_RESERVA'] ?>">
                        <div class="card-body d-flex gap-3">
                          <div>
                            <button class="border btn btn-sm btn-primary" style="width: 5rem;" data-bs-target="#emailManual" data-bs-toggle="modal" onclick="document.getElementById('eDestinatario').value ='<?php echo $reserva['EMAIL'] ?>'">Email</button>
                          </div>
                          <?php if ($reserva["DS_ESTADO"] == 'NO VALIDADO') : ?>
                            <div>
                              <button class="border btn btn-sm btn-primary" style="width: 5rem;" data-bs-target="#validarReserva" data-bs-toggle="modal" onclick="
                            
                            document.getElementById('vIdReserva').value ='<?php echo $reserva['ID_RESERVA'] ?>'                            
                            
                            ">Validar</button>
                            </div>

                            <form action="<?php echo baseUrl ?>adminpage/cancelarReserva" method="POST">
                              <button style="width: 5.2rem;" class="btn btn-sm btn-danger" type="submit" name="reserva" value="<?php echo $reserva['ID_RESERVA'] ?>">Cancelar</button>
                            </form>
                          <?php endif; ?>

                          <?php if ($reserva["DS_ESTADO"] == 'PAGADO') : ?>
                            <form action="<?php echo baseUrl ?>adminpage/reembolsarReserva" method="POST">
                              <button style="width: 6rem;" class="btn btn-sm btn-danger" type="submit" name="reserva" value="<?php echo $reserva['ID_RESERVA'] ?>">Reembolsar</button>
                            </form>
                          <?php endif; ?>

                        </div>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header border-0 d-flex justify-content-between">
        <h5 class="mb-0 w-100" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          Gestion de niños en campamentos
        </h5>
      </div>
      <form class="p-2">
        <div class="row">
          <div class="col">
            <select class="form-select" name="turnoc">
              <option value="null">Todos los turnos</option>
              <?php foreach ($turnos as $turno) : ?>
                <option value="<?php echo $turno['CD_TURNO'] ?>"><?php echo $turno['DS_TURNO'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col">
            <select class="form-select" name="actividadc">
              <option value="null">Todas las actividades</option>
              <?php foreach ($actividades as $actividad) : ?>
                <option value="<?php echo $actividad['actividad']['CD_ACTIVIDAD'] ?>"><?php echo $actividad['actividad']['NM_ACTIVIDAD'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col">
            <input type="text" name="ninoc" class="form-control" placeholder="Nombre del niño">
          </div>
          <div class="col-1">
            <button class="text-nowrap w-100 btn btn-secondary" type="submit">Filtrar</button>
          </div>
        </div>
      </form>

      <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
        <div class="">
          <div style="height: 60vh; overflow:auto">
            <table class="table table-sticky table-stripped table-hover">
              <thead class="bg-light">
                <tr>
                  <th></th>
                  <th scope="col">Campamento</th>
                  <th scope="col">Padre</th>
                  <th scope="col">Nino</th>
                  <th scope="col">Turno</th>
                </tr>
              </thead>
              <tbody id="NBody">
                <?php if ($ninos != false) foreach ($ninos as $nino) : ?>
                  <tr style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#N<?php echo $nino['ID_NINO'] ?>">
                    <td style="width: 0px">
                      <div class="d-flex flex-nowrap gap-1">
                        <span style="width: auto;">🔽</span>
                      </div>
                    </td>
                    <td class="text-nowrap"><?php echo $nino["NM_ACTIVIDAD"] ?></td>
                    <td class="text-nowrap"><?php echo $nino["NOMBREP"] . " " . $nino["APELLIDOSP"] ?></td>
                    <td class="text-nowrap"><?php echo $nino["NOMBREN"] . " " . $nino["APELLIDOSN"] ?></td>
                    <td class="text-nowrap"><?php echo $nino["DS_TURNO"] ?></td>
                  </tr>
                  <tr class="collapse-row">
                    <td class="p-0" colspan="12">
                      <div class="collapse" data-bs-parent="#NBody" id="N<?php echo $nino['ID_NINO'] ?>">
                        <div class="card-body d-flex gap-3">
                          <div>
                            <button class="border btn btn-sm btn-primary" style="width: 5rem;" data-bs-target="#emailManual" data-bs-toggle="modal" onclick="document.getElementById('eDestinatario').value ='<?php echo $nino['EMAIL'] ?>'">Email</button>
                          </div>
                          <div>
                            <button class="border btn btn-sm btn-primary btnInfos" style="width: 5rem;" data-bs-target="#verInfo" data-bs-toggle="modal" value="<?php echo $nino["ID_NINO"] ?>">Ver info.</button>
                          </div>
                          <?php if ($nino["0"] == 'true') : ?>
                            <form action="<?php echo baseUrl ?>adminpage/DescargarDniNino" method="POST">
                              <button class="btn btn-sm btn-light  text-nowrap" name="idNino" value="<?php echo $nino["ID_NINO"] ?>" type="submit">DNI Niño</button>
                            </form>
                          <?php endif; ?>
                          <?php if ($nino["1"] == 'true') : ?>
                            <form action="<?php echo baseUrl ?>adminpage/DescargarTSNino" method="POST">
                              <button class="btn btn-sm btn-light text-nowrap" name="idNino" value="<?php echo $nino["ID_NINO"] ?>" type="submit">Tarj. San.</button>
                            </form>
                          <?php endif; ?>
                          <?php if ($nino["2"] == 'true') : ?>
                            <form action="<?php echo baseUrl ?>adminpage/DescargarDniPadre" method="POST">
                              <button class="btn btn-sm btn-light text-nowrap" name="idPadre" value="<?php echo $nino["ID_PADRE"] ?>" type="submit">DNI Padre</button>
                            </form>
                          <?php endif; ?>
                        </div>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header border-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
        <h5 class="mb-0">
          Gestion de actividades
        </h5>
      </div>
      <div id="collapseThree" class="collapse show" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
        <div class="">
          <div style="height: 60vh; overflow:auto">
            <table class="table table-stripped table-sticky table-hover">
              <thead class="bg-light">
                <tr>
                  <th></th>
                  <th>Nombre</th>
                  <th>Categoria</th>
                  <th>Turnos</th>
                  <th>Desactivado</th>
                </tr>
              </thead>
              <tbody id="MBody">
                <?php foreach ($actividades as $elem) : ?>
                  <tr style="cursor:pointer; <?php echo $elem['actividad']["DESACTIVADO"] == 0 ? "background-color: white" : "background-color: #F5B4B4" ?>" data-bs-toggle="collapse" data-bs-target="#M<?php echo $elem['actividad']['CD_ACTIVIDAD'] ?>">
                    <td style="width: 0px">
                      <div class="d-flex flex-nowrap gap-1">
                        <span style="width: auto;">🔽</span>
                      </div>
                    </td>
                    <td><?php echo $elem['actividad']['NM_ACTIVIDAD'] ?></td>
                    <td><?php echo $elem['actividad']['DS_CATEGORIA'] ?></td>
                    <td><?php echo $elem['actividad']['TURNOS'] ?></td>
                    <td><?php echo $elem['actividad']['DESACTIVADO'] == 0 ? "No" : "Sí" ?></td>
                  </tr>
                  <tr class="collapse-row">
                    <td class="p-0" colspan="12">
                      <div class="collapse" data-bs-parent="#MBody" id="M<?php echo $elem['actividad']['CD_ACTIVIDAD'] ?>">
                        <div class="card-body d-flex gap-3">
                          <div class="d-flex flex-column gap-3">
                            <form action="<?php echo baseUrl ?>adminpage/ActivarDesactivar" method="POST">
                              <input hidden value="<?php echo $elem['actividad']["DESACTIVADO"] ?>" name="desactivado">
                              <button style="width: 5.2rem;" class="btn btn-sm <?php echo $elem['actividad']["DESACTIVADO"] == 0 ? "btn-danger" : "btn-warning" ?> btnDesactivar" type="submit" name="codigo" value="<?php echo $elem['actividad']['CD_ACTIVIDAD'] ?>"><?php echo $elem['actividad']["DESACTIVADO"] == 0 ? "Desactivar" : "Reactivar " ?></button>
                            </form>
                            <div>
                              <button class="border btn btn-sm btn-primary btnActividad" style="width: 5.2rem;" data-bs-target="#editActividad" data-bs-toggle="modal" value="<?php echo $elem['actividad']['CD_ACTIVIDAD'] ?>">Editar</button>
                            </div>
                            <div>
                              <button class="border btn btn-sm btn-primary btnEstados" style="width: 5.2rem;" data-bs-target="#estadosActividad" data-bs-toggle="modal" value="<?php echo $elem['actividad']['CD_ACTIVIDAD'] ?>">Estados</button>
                            </div>
                            <?php if($elem['actividad']['DESACTIVADO'] == 1):?>
                            <div>
                              <button class="border btn btn-sm btn-danger btnBorrar" style="width: 5.2rem;" data-bs-target="#borrarActividad" data-bs-toggle="modal" onclick="document.getElementById('confirmBtn').value = '<?php echo $elem['actividad']['CD_ACTIVIDAD'] ?>'">Borrar</button>
                            </div>
                            <?php endif;?>
                          </div>
                          <div class="card w-100">
                            <table class="table table-stripped table-hover" style="z-index:0 !important">
                              <thead>
                                <tr>
                                  <th></th>
                                  <th>Turno</th>
                                  <th>Plazas Ocupadas</th>
                                  <th>Plazas Totales</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php foreach ($elem['turnos'] as $turnosA) : ?>
                                  <tr>
                                    <td class="d-flex gap-2 text-nowrap">
                                      <button class="btn btn-sm btn-primary" data-bs-target="#plazasConfig" data-bs-toggle="modal" onclick="

                                      document.getElementById('labelTurno').innerHTML = 'Turno: <?php echo $turnosA['TURNO'] ?>';
                                      document.getElementById('labelActividad').innerHTML = 'Actividad: <?php echo $elem['actividad']['NM_ACTIVIDAD'] ?>';
                                      document.getElementById('campoTurno').value = '<?php echo explode(' - ', $turnosA['TURNO'])[0] ?>';
                                      document.getElementById('campoActividad').value = '<?php echo $elem['actividad']['CD_ACTIVIDAD'] ?>';
                                      document.getElementById('campoPlazas').value = '<?php echo $turnosA['NUM_PLAZAS'] ?>';
                                      
                                      ">Config.</button>
                                      <form action="<?php echo baseUrl ?>adminpage/getExcel" method="POST">


                                        <button name="actTurno" value="<?php echo  $elem['actividad']['CD_ACTIVIDAD'] . "-" . $turnosA['TURNO'] ?>" class="text-nowrap btn btn-sm btn-secondary">Descargar Excel</button>

                                      </form>
                                    </td>
                                    <td><?php echo $turnosA['TURNO'] ?? '-' ?></td>
                                    <td><?php echo $turnosA['PLAZAS_OCUP'] ?? '-' ?></td>
                                    <td><?php echo ($turnosA['TURNO'] == 'TU' ? ($turnosA['NUM_PLAZAS'] == "0" ? "Sin límite" : $turnosA['NUM_PLAZAS'] ?? "-") :  $turnosA['NUM_PLAZAS']  ?? "-") ?></td>
                                  </tr>
                                <?php endforeach; ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <div class="card-footer">
          <button class="btn btn-sm btn-primary" data-bs-target="#registrarActividad" data-bs-toggle="modal">Añadir nuevo</button>
          <button class="btn btn-sm btn-primary" data-bs-target="#registrarCategoria" data-bs-toggle="modal">Registrar categ.</button>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="borrarActividad">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5>¿Está seguro de que quiere borrar esta actividad?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="text-danger">Esta acción no se puede deshacer, se eliminará todo lo relacionado con esta actividad
          incluyendo los turnos y las reservas asociadas a la misma. Es OBLIGATORIO que la actividad no esté activa, 
          NO DEBE HABER TURNOS EN CURSO. Trate esta acción con cuidado. Para su seguridad, hay una comprobación adicional
          en la base de datos.
        </p>
        <form action="<?php echo baseUrl ?>adminpage/borrarActividad" method="POST">
          <input hidden name="codigo" id="confirmBtn" value="">
          <button class="btn btn-danger" type="submit">Borrar</button>
        </form>
      </div>
    </div>
  </div> 
</div>  

<div class="modal fade" id="verInfo">
  <div class="modal-dialog" style="--bs-modal-width:1000px;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Informaciones</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body d-flex flex-column gap-2">
        <div class="d-flex w-100 justify-content-between">
          <div>
            <h4>Informacion usuario responsable</h4>
            <div class="card-body overflex">
              <h6>Nombre y Apellidos</h6>
              <p class="card-text" id="iuNombre"></p>
              <h6>Email de contacto:</h6>
              <p class="card-text" id="iuEmail"></p>
              <h6>Teléfono de contacto:</h6>
              <p class="card-text" id="iuTelefono"></p>
              <h6>DNI/NIE:</h6>
              <p class="card-text mb-2" id="iuDni"></p>
              <h6>Dirección completa:</h6>
              <p class="card-text" id="iuDireccion1"></p>
              <p class="card-text" id="iuDireccion2"></p>
              <h6>Código postal:</h6>
              <p class="card-text" id="iuCP"></p>
              <h6>Localidad:</h6>
              <p class="card-text" id="iuLocalidad"></p>
              <h6>Provincia:</h6>
              <p class="card-text" id="iuProvincia"></p>
            </div>
          </div>
          <div>
            <h4>Informacion de tutor responsable</h4>
            <div class="card-body overflex">
              <h6>Nombre y Apellidos</h6>
              <p class="card-text" id="it1Nombre"></p>
              <h6>Email de contacto:</h6>
              <p class="card-text" id="it1Email"></p>
              <h6>Teléfono de contacto:</h6>
              <p class="card-text" id="it1Telefono"></p>
              <h6>DNI/NIE:</h6>
              <p class="card-text mb-2" id="it1Dni"></p>
              <h6>Dirección completa:</h6>
              <p class="card-text" id="it1Direccion1"></p>
              <p class="card-text" id="it1Direccion2"></p>
              <h6>Código postal:</h6>
              <p class="card-text" id="it1CP"></p>
              <h6>Localidad:</h6>
              <p class="card-text" id="it1Localidad"></p>
              <h6>Provincia:</h6>
              <p class="card-text" id="it1Provincia"></p>
            </div>
          </div>
        </div>
        <hr>
        <div>
          <h4>Informacion niño</h4>
          <div class="card-body overflex">
            <h6>Nombre y Apellidos</h6>
            <p class="card-text" id="inNombre"></p>
            <h6>Centro estudios</h6>
            <p class="card-text" id="inCentro"></p>
            <h6>Fecha Nacimiento (YYYY/MM/DD)</h6>
            <p class="card-text" id="inNacimiento"></p>
            <h6>Alergia medica</h6>
            <p class="card-text" id="inAleMedica"></p>
            <h6>Alergia alimentaria</h6>
            <p class="card-text" id="inAleAlim"></p>
            <h6>¿Tiene alguna lesion?</h6>
            <p class="card-text" id="inLesion"></p>
            <h6>Medicación actual</h6>
            <p class="card-text" id="inMedicina"></p>
            <h6>¿Presenta alguna discapacidad?</h6>
            <p class="card-text" id="inDiscapacidad"></p>
            <h6>Reacciones alergicas</h6>
            <p class="card-text" id="inReacciones"></p>
            <h6>¿Tiene todas las vacunas para su edad?</h6>
            <p class="card-text" id="inVacunas"></p>
            <h6>¿Tiene vacuna antitetanica?</h6>
            <p class="card-text" id="inAntiteta"></p>
            <h6>¿Sabe nadar?</h6>
            <p class="card-text" id="inNadar"></p>
            <h6>Aficiones</h6>
            <p class="card-text" id="inAficiones"></p>
            <h6>Observaciones</h6>
            <p class="card-text" id="inObservaciones"></p>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editActividad">
  <div class="modal-dialog" style="--bs-modal-width: 1000px" role="document">
    <form id="actividadEditForm" action="<?php echo baseUrl ?>adminpage/EditarActividad" class="modal-content" method="POST">
      <div class="modal-header">
        <h5 class="modal-title">Editar Actividad</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <input type="text" hidden class="form-control" maxlength="10" id="codigoActividadAE" name="codigoAntiguo" required>
      </div>
      <div class="modal-body d-flex flex-column gap-2">       

        <div class="d-flex gap-2 w-100 justify-content-between">
          <div class="form-group w-50">
            <label for="nombre">Código</label>
            <input type="text" style="text-transform: uppercase;" class="form-control" maxlength="10" id="codigoActividadE" name="codigo" required>
          </div>
          <div class="form-group w-50">
            <label for="nombre">Categoría</label>
            <select type="text" class="form-select" id="catActividadE" name="categoria" required>
              <?php foreach ($categorias as $categoria) : ?>
                <option value="<?php echo $categoria['CD_CATEGORIA'] ?>"><?php echo $categoria['DS_CATEGORIA'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="nombre">Nombre</label>
          <input type="text" class="form-control" maxlength="50" id="nombreActividadE" name="nombre" required>
        </div>
        <div class="form-group">
          <label for="imagen">Imagen (url)</label>
          <input type="text" class="form-control" id="imagenActividadE" value="" name="imagen">
        </div>
        <div class="d-flex gap-2 w-100 justify-content-between">
          <div class="form-group w-50">
            <label for="nombre">Descripción</label>
            <textarea type="text" class="form-control" id="desActividadE" name="descripcion" rows="5" required></textarea>
          </div>
          
        </div>

      </div>
      <div class="modal-footer d-flex justify-content-between">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="validarReserva">
  <div class="modal-dialog" style="--bs-modal-width: 600px;" role="document">
    <form method="POST" action="<?php echo baseUrl ?>adminpage/validarReserva" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Validar reserva</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body d-flex flex-nowrap gap-2">
        <input type="text" value="" hidden id="vIdReserva" name="idReserva">
        <div class="form-group w-100">
          <label class="form-label">Precio (€):</label>
          <input class="form-control" type="number" step="0.01" min="0" value="0" name="precio">
        </div>
        <div class="form-group w-100">
          <label class="form-label">Días hasta cancelación:</label>
          <input class="form-control" type="number" min="1" value="15" name="limite">
        </div>

      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" type="submit">Enviar</button>
        <button class="btn btn-primary" type="reset" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="plazasConfig">
  <div class="modal-dialog" style="--bs-modal-width: 600px;" role="document">
    <form method="POST" action="<?php echo baseUrl ?>adminpage/SetPlazaTurno" id="plazasForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Configurar plaza </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body d-flex flex-column gap-2">
        <input type="text" value="" hidden id="campoActividad" name="actividad">
        <div class="d-flex justify-content-between"><Label id="labelTurno"></Label><Label id="labelActividad"></Label></div>
        <input type="text" value="" hidden id="campoTurno" name="turno">
        <div class="form-group">
          <label class="form-label">Plazas totales:</label>
          <input class="form-control" type="number" min="0" value="" id="campoPlazas" name="plazas">
        </div>

      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" type="submit">Enviar</button>
        <button class="btn btn-primary" type="reset" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="estadosActividad">
  <div class="modal-dialog" style="--bs-modal-width: 600px;" role="document">
    <div id="actividadForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Estados de Actividad</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <!-- llamar a ajax para los estados de la actividad -->
      <div class="modal-body d-flex flex-column gap-2" id="turnosEstadoActividad">

      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="emailManual">
  <div class="modal-dialog" style="--bs-modal-width:1000px;" role="document">
    <form action="<?php echo baseUrl ?>adminpage/EnviarEmailManual" method="POST" id="emailForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Enviar email</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body d-flex flex-column gap-2">
        <div class="form-group">
          <label class="form-label"></label>
          <input class="form-control" type="text" id="eDestinatario" name="destinatario" placeholder="destinatario" value="">
        </div>
        <div class="form-group">
          <label class="form-label"></label>
          <input class="form-control" type="text" name="asunto" placeholder="Asunto">
        </div>
        <div class="form-group">
          <label class="form-label"></label>
          <textarea class="form-control" name="mensaje" rows="4"></textarea>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-primary" type="submit">Enviar</button>
        <button class="btn btn-primary" type="reset" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="registrarCategoria">
  <div class="modal-dialog" style="--bs-modal-width:400px;" role="document">
    <form action="<?php echo baseUrl ?>adminpage/registrarCategoria" method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Registrar nueva categoría</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body d-flex flex-row gap-2">
        <div class="form-group">
          <label class="form-label">Código</label>
          <input class="form-control" type="text" maxlength="10" name="codigo">
        </div>
        <div class="form-group">
          <label class="form-label">Nombre</label>
          <input class="form-control" type="text" name="nombre">
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-primary" type="submit">Enviar</button>
        <button class="btn btn-primary" type="reset" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="registrarActividad">
  <div class="modal-dialog" style="--bs-modal-width: 1000px" role="document">
    <form id="actividadForm" action="<?php echo baseUrl ?>adminpage/nuevaActividad" class="modal-content" method="POST">
      <div class="modal-header">
        <h5 class="modal-title">Creacion de Actividad</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body d-flex flex-column gap-2">
        <div class="d-flex gap-2 w-100 justify-content-between">
          <div class="form-group w-50">
            <label for="nombre">Código</label>
            <input type="text" style="text-transform: uppercase;" class="form-control" maxlength="10" id="codigoActividad" name="codigo" required>
          </div>
          <div class="form-group w-50">
            <label for="nombre">Categoría</label>
            <select type="text" class="form-select" id="catActividad" name="categoria" required>
              <?php foreach ($categorias as $categoria) : ?>
                <option value="<?php echo $categoria['CD_CATEGORIA'] ?>"><?php echo $categoria['DS_CATEGORIA'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="nombre">Nombre</label>
          <input type="text" class="form-control" maxlength="50" id="nombreActividad" name="nombre" required>
        </div>
        <div class="form-group">
          <label for="imagen">Imagen (url)</label>
          <input type="text" class="form-control" id="imagenActividad" name="imagen">
        </div>

        <div class="d-flex gap-2 w-100 justify-content-between">
          <div class="form-group w-50">
            <label for="nombre">Descripción</label>
            <textarea type="text" class="form-control" id="desActividad" name="descripcion" rows="5" required></textarea>
          </div>
          <div class="form-group w-50">
            <label for="nombre">Turnos</label>
            <select multiple class="form-select" style="height:8.4rem" id="turnosActividad" name="turnos[]" required>
              <?php foreach ($turnos as $turno) : ?>
                <option value="<?php echo $turno['CD_TURNO'] ?>"><?php echo $turno['DS_TURNO'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

      </div>
      <div class="modal-footer d-flex justify-content-between">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>

<script src="<?php echo baseUrl ?>locals/js/adminpage.js"></script>

<?php
require_once("Layouts/Footer.php");
?>