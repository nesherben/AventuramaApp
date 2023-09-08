<?php
require_once("Layouts/Header.php");
$user = $this->d['user'];
$reservas = $this->d['reservas'];
$ninos = $this->d['ninos'];
$tutores = $this->d['tutores'];
?>

<link rel="stylesheet" href="Locals/css/perfil.css">

<section class="content" style="zoom: 80%">
  <div class="container-fluid overflex h-100">
    <div class="d-flex gap-2 justify-content-between align-items-center mt-3">
      <h1 class="m-auto">Perfil de <?php echo $user->nombre . " " . $user->apellidos ?></h1>
    </div>
    <hr>
    <div class="d-flex flex-wrap justify-content-between" style="height: -webkit-fill-available">
      <div class="h-100 pb-3" id="usuInfo">
        <div class="card h-50 shadow-sm border rounded">
          <h5 class="card-header">Información Personal</h5>
          <!-- Datos del usuario -->
          <div class="card-body overflex">
            <h6>Email de contacto:</h6>
            <p class="card-text"><?php echo $user->email ?></p>
            <h6>Teléfono de contacto:</h6>
            <p class="card-text"><?php echo $user->telefono ?></p>
            <h6>DNI/NIE:</h6>
            <?php if ($user->tieneDNI) : ?>
              <a href="perfil/DescargarDniUsuario" class="card-text mb-2"><?php echo $user->dni ?></a>
            <?php else : ?>
              <p class="card-text mb-2"><?php echo $user->dni ?></p>
            <?php endif; ?>
            <h6>Dirección completa:</h6>
            <p class="card-text"><?php echo $user->direccion . ", " .  ($user->direccion2 ?? "") ?></p>

            <div class="row">
              <div class="col">
                <h6>Código postal:</h6>
                <p class="card-text"><?php echo $user->cp ?></p>
              </div>
              <div class="col">
                <h6>Localidad:</h6>
                <p class="card-text"><?php echo $user->localidad ?></p>
              </div>
              <div class="col">
                <h6>Provincia:</h6>
                <p class="card-text"><?php echo $user->provincia ?></p>
              </div>

              <?php if (!isset($user->direccion) || !isset($user->cp) || !isset($user->localidad) || !isset($user->provincia)) : ?>
                <p class="text-danger text-bold">Es necesario completar la información faltante.</p>
              <?php endif; ?>
            </div>

          </div>
          <div class="card-footer">
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editarInfo">Editar información</button>
          </div>
        </div>

        <div class="card mt-2 shadow-sm border rounded h-50">
          <h5 class="card-header">Datos de Tutores adicionales</h5>
          <!-- Datos de los niños -->
          <div class="card-body p-0 h-100 overflex">
            <?php if (empty($ninos)) : ?>
              <p class="text-center text-danger">No hay tutores registrados</p>
            <?php else : ?>
              <div class="overflex h-100">
                <table class="table table-sticky table-stripped table-hover">
                  <thead class="bg-light">
                    <tr>
                      <th scope="col"></th>
                      <th scope="col">Nombre y apellidos</th>
                      <th scope="col">Telefono</th>
                      <th scope="col">Email</th>
                      <!-- Otros datos de los niños aquí -->
                    </tr>
                  </thead>
                  <tbody id="TBody">
                    <?php if ($tutores != false) foreach ($tutores as $tutor) : ?>
                      <tr style="cursor:pointer;" data-bs-toggle="collapse" data-bs-target="#M<?php echo $tutor['ID_TUTOR'] ?>">
                        <td style="width: 0px">
                          <div class="d-flex flex-nowrap gap-1">
                            <span style="width: auto;">🔽</span>
                          </div>
                        </td>
                        <td class="text-nowrap"><?php echo $tutor["NOMBRE"] . " " . $tutor["APELLIDOS"] ?></td>
                        <td class="text-nowrap"><?php echo $tutor["NUM_TLFN"] ?></td>
                        <td class="text-nowrap"><?php echo $tutor["EMAIL"] ?></td>
                      </tr>
                      <tr class="collapse-row">
                        <td class="p-0" colspan="12">
                          <div class="collapse" data-bs-parent="#TBody" id="M<?php echo $tutor['ID_TUTOR'] ?>">
                            <div class="card-body d-flex gap-3">

                              <div class="d-flex flex-nowrap gap-1">
                                <button class="btn btn-sm btn-primary btnTutor" data-bs-target="#insertTutor" data-bs-toggle="modal" value="<?php echo $tutor['ID_TUTOR'] ?>" onclick="document.getElementById('EidTutor').value = <?php echo $tutor['ID_TUTOR'] ?>">Editar</button>
                              </div>
                              <div class="d-flex flex-nowrap gap-1">
                                <button class="btn btn-sm btn-primary btnTutor" data-bs-target="#infoTutor" data-bs-toggle="modal" value="<?php echo $tutor['ID_TUTOR'] ?>">Ver datos</button>
                              </div>
                              <form action="perfil/borrarTutor" method="POST" class="d-flex flex-nowrap gap-1">
                                <button class="btn btn-sm btn-danger" data-bs-target="#infoTutor" data-bs-toggle="modal" name="idTutor" value="<?php echo $tutor['ID_TUTOR'] ?>">Borrar</button>
                              </form>
                            </div>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php endif; ?>
          </div>
          <div class="card-footer">
            <button class="btn btn-sm btn-primary" data-bs-target="#insertTutor" onclick="document.getElementById('formTut').reset()" data-bs-toggle="modal">Añadir nuevo tutor</button>
          </div>
        </div>

      </div>

      <div class="d-flex pb-2 h-100 flex-column gap-2 cards-tables">
        <div class="card shadow-sm border rounded h-50">
          <h5 class="card-header">Datos de los Niños</h5>
          <!-- Datos de los niños -->
          <div class="card-body p-0 h-100 overflex">
            <?php if (empty($ninos)) : ?>
              <p class="text-center text-danger">No hay niños registrados</p>
            <?php else : ?>
              <div class="overflex h-100">
                <table class="table table-sticky table-stripped table-hover">
                  <thead class="bg-light">
                    <tr>
                      <th scope="col"></th>
                      <th scope="col">Nombre y apellidos</th>
                      <th scope="col">Edad</th>
                      <th scope="col">Escuela</th>
                      <!-- Otros datos de los niños aquí -->
                    </tr>
                  </thead>
                  <tbody id="NBody">
                    <?php if ($ninos != false) foreach ($ninos as $nino) : ?>
                      <tr data-bs-target="#N<?php echo $nino['ID_NINO'] ?>" data-bs-toggle="collapse" style="cursor:pointer;">
                        <td style="width: 0px">
                          <div class="d-flex flex-nowrap gap-1">
                            <span style="width: auto;">🔽</span>
                          </div>
                        </td>
                        <td class="text-nowrap"><?php echo $nino["NOMBRE"] . " " . $nino["APELLIDOS"] ?></td>
                        <td class="text-nowrap">
                          <?php 
                          $fecha_js = $nino["FH_NACIMIENTO"]; // fecha en formato JavaScript
                          $fecha_nacimiento = date_create_from_format('Y-m-d', $fecha_js); // convertir la fecha a un formato PHP
                          $hoy = new DateTime(); // fecha actual
                          $edad = $hoy->diff($fecha_nacimiento)->y; // calcular la diferencia en años                       
                          echo $edad . " Años"
                          ?>
                        </td>
                        <td class="text-nowrap"><?php echo $nino["CENTRO_ESTUDIOS"] ?></td>
                      </tr>
                      <tr class="collapse-row">
                        <td class="p-0" colspan="12">
                          <div class="collapse" data-bs-parent="#NBody" id="N<?php echo $nino['ID_NINO'] ?>">
                            <div class="card-body d-flex gap-3">
                              <div class="d-flex flex-nowrap gap-1">
                                <button class="btn btn-sm btn-primary btnNino" data-bs-target="#editNino" data-bs-toggle="modal" value="<?php echo $nino["ID_NINO"] ?>">Editar</button>
                                <button class="btn text-nowrap btn-sm btn-primary btnNino" data-bs-target="#infoNino" data-bs-toggle="modal" value="<?php echo $nino["ID_NINO"] ?>">Ver info.</button>
                                <?php if ($nino["0"] == 'true') : ?>
                                  <form action="perfil/DescargarDniNino" method="POST">
                                    <button class="btn btn-sm btn-light" name="idNino" value="<?php echo $nino["ID_NINO"] ?>" type="submit">DNI</button>
                                  </form>
                                <?php endif; ?>
                                <?php if ($nino["1"] == 'true') : ?>
                                  <form action="perfil/DescargarTSNino" method="POST">
                                    <button class="btn btn-sm btn-light text-nowrap" name="idNino" value="<?php echo $nino["ID_NINO"] ?>" type="submit">Tarj. San.</button>
                                  </form>
                                <?php endif; ?>
                              </div>

                            </div>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php endif; ?>
          </div>
          <div class="card-footer">
            <button class="btn btn-sm btn-primary" data-bs-target="#registrarNino" data-bs-toggle="modal">Añadir nuevo niño</button>
          </div>
        </div>
        <div class="card shadow-sm border rounded h-50">
          <h5 class="card-header">Datos de reservas</h5>
          <div class="card-body overflex p-0 h-100">
            <?php if (empty($reservas)) : ?>
              <p class="text-center text-danger">No hay reservas</p>
            <?php else : ?>
              <div class="overflex h-100">
                <table class="table table-sticky table-stripped table-hover">
                  <thead class="bg-light">
                    <tr>
                      <th></th>
                      <th scope="col">Fecha reserva</th>
                      <th scope="col">Actividad</th>
                      <th scope="col">Turno</th>
                      <th scope="col">Niño</th>
                      <th scope="col">Estado</th>
                      <th scope="col">Precio</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($reservas != false) foreach ($reservas as $reserva) : ?>
                      <tr>

                        <td style="width: 0px">
                          <?php if ($reserva["DS_ESTADO"] == 'NO VALIDADO') : ?>
                            <form method="POST" action="perfil/cancelarReserva" class="d-flex flex-nowrap gap-1">
                              <button class="btn btn-sm w-100 btn-danger " name="idReserva" value="<?php echo $reserva["ID_RESERVA"] ?>">Cancelar</button>
                            </form>
                          <?php endif; ?>

                          <?php if ($reserva["DS_ESTADO"] == 'VALIDADO') : ?>                            
                              
                              <button class="btn btn-sm w-100 btn-primary " data-bs-target="#pagoModal" data-bs-toggle="modal" onclick="
                              document.getElementById('precioModalPago').value = <?php echo $reserva['PRECIO'] ?>; 
                              document.getElementById('idReservaPago').value = <?php echo $reserva['ID_RESERVA'] ?>;
                              document.getElementById('idNumReservaModal').innerHTML = '<?php echo $reserva['FH_RESERVA'].$reserva['ID_RESERVA']?>'">Pagar</button>
                            
                          <?php endif; ?>

                          <?php if ($reserva["DS_ESTADO"] == 'PAGADO') : ?>
                            <form method="POST" action="perfil/factura" class="d-flex flex-nowrap gap-1">
                              <button class="btn btn-sm w-100 btn-dark " name="idReserva" value="<?php echo $reserva["ID_RESERVA"] ?>">Factura</button>
                            </form>
                          <?php endif; ?>
                        </td>

                        <td class="text-nowrap"><?php echo $reserva["FH_RESERVA"] ?></td>
                        <td class="text-nowrap"><?php echo $reserva["NM_ACTIVIDAD"] ?></td>
                        <td class="text-nowrap"><?php echo $reserva["DS_TURNO"] ?></td>
                        <td class="text-nowrap"><?php echo $reserva["NOMBRE"] . " " . $reserva["APELLIDOS"] ?></td>
                        <td class="text-nowrap"><?php echo $reserva["DS_ESTADO"] ?></td>
                        <td class="text-nowrap"><?php echo $reserva["PRECIO"] ?> €</td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<div class="modal fade" id="insertTutor">
  <div class="modal-dialog" style="--bs-modal-width: 1000px" role="document">
    <div class="modal-content">
      <form action="perfil/infoTutor" method="POST" id="formTut">
        <div class="modal-header">
          <h5 class="modal-title">Datos de tutor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="d-flex">
          <input hidden name="EidTutor" id="EidTutor" type="text">
          <div class="col-md-6 p-2">
            <div class="form-group">
              <label for="email">Email*</label>
              <input value="" type="email" class="form-control" id="Temail" name="email" required>
            </div>
            <div class="form-group">
              <label for="telefono">Teléfono*</label>
              <input value="" type="text" maxlength="9" class="form-control" id="Ttelefono" name="telefono" required>
            </div>
            <div class="form-group">
              <label for="nombre">Nombre*</label>
              <input value="" type="text" class="form-control" id="Tnombre" name="nombre" required>
            </div>
            <div class="form-group">
              <label for="apellidos">Apellidos*</label>
              <input value="" type="text" class="form-control" id="Tapellidos" name="apellidos" required>
            </div>
            <div class="form-group">
              <label for="dni">DNI / NIE *</label>
              <input value="" type="text" maxlength="9" class="form-control" id="Tdni" name="dni" required>
            </div>
          </div>
          <div class="col-md-6 p-2">
            <div class="form-group">
              <label for="direccion">Dirección*</label>
              <input value="" type="text" class="form-control" id="Tdireccion" name="direccion" required>
            </div>
            <div class="form-group">
              <label for="direccion2">Dirección 2</label>
              <input value="" type="text" class="form-control" id="Tdireccion2" name="direccion2">
            </div>
            <div class="form-group">
              <label for="codigoPostal">Código Postal*</label>
              <input value="" type="text" maxlength="6" class="form-control" id="Tcp" name="cp" required>
            </div>
            <div class="form-group">
              <label for="codigoPostal">Localidad*</label>
              <input value="" type="text" class="form-control" id="Tlocalidad" name="localidad" required>
            </div>
            <div class="form-group">
              <label for="codigoPostal">Provincia*</label>
              <input value="" type="text" class="form-control" id="Tprovincia" name="provincia" required>
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
</div>

<div class="modal fade" id="editarInfo">
  <div class="modal-dialog" style="--bs-modal-width: 1000px" role="document">
    <div class="modal-content">
      <form action="perfil/infoUsuario" method="POST" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Datos de usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="d-flex">
          <div class="col-md-6 p-2">
            <div class="form-group">
              <label for="email">Email*</label>
              <input value="<?php echo $user->email ?>" type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
              <label for="telefono">Teléfono*</label>
              <input value="<?php echo $user->telefono ?>" type="text" maxlength="9" class="form-control" id="telefono" name="telefono" required>
            </div>
            <div class="form-group">
              <label for="nombre">Nombre*</label>
              <input value="<?php echo $user->nombre ?>" type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
              <label for="apellidos">Apellidos*</label>
              <input value="<?php echo $user->apellidos ?>" type="text" class="form-control" id="apellidos" name="apellidos" required>
            </div>
            <div class="form-group">
              <label for="dni">DNI / NIE *</label>
              <input value="<?php echo $user->dni ?>" type="text" maxlength="9" class="form-control" id="dni" name="dni" required>
            </div>
            
          </div>
          <div class="col-md-6 p-2">
            <div class="form-group">
              <label for="direccion">Dirección*</label>
              <input value="<?php echo $user->direccion ?>" type="text" class="form-control" id="direccion" name="direccion" required>
            </div>
            <div class="form-group">
              <label for="direccion2">Dirección 2</label>
              <input value="<?php echo $user->direccion2 ?>" type="text" class="form-control" id="direccion2" name="direccion2">
            </div>
            <div class="form-group">
              <label for="codigoPostal">Código Postal*</label>
              <input value="<?php echo $user->cp ?>" type="text" maxlength="6" class="form-control" id="cp" name="cp" required>
            </div>
            <div class="form-group">
              <label for="codigoPostal">Localidad*</label>
              <input value="<?php echo $user->localidad ?>" type="text" class="form-control" id="localidad" name="localidad" required>
            </div>
            <div class="form-group">
              <label for="codigoPostal">Provincia*</label>
              <input value="<?php echo $user->provincia ?>" type="text" class="form-control" id="provincia" name="provincia" required>
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
</div>

<div class="modal fade" id="registrarNino">
  <div class="modal-dialog" style="--bs-modal-width: 1000px" role="document">
    <form id="ninoForm" action="perfil/registroNino" class="modal-content" method="POST" enctype="multipart/form-data">
      <div class="modal-header">
        <h5 class="modal-title" id="inscripcionModalLabel">Inscripción de Niño</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body card">
        <div>
          <input hidden value="" id="cod_nino" name="codigo">
          <div class="form-group">
            <label for="nombre">Nombre*</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
          </div>
          <div class="form-group">
            <label for="apellidos">Apellidos*</label>
            <input type="text" class="form-control" id="apellidos" name="apellidos" required>
          </div>
          <div class="form-group">
            <label for="fechaNacimiento">Fecha de Nacimiento*</label>
            <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento" required>
          </div>
          <div class="row">
            <div class="col form-group">
                <label for="dni">DNI / NIE</label>
                <input type="text" maxlength="9" class="form-control" id="dni" name="dni" >
              </div>
              <!-- <div class="col form-group">
                <label for="dniImg">imagen DNI / NIE </label>
                <input type="file" class="form-control" id="dniImg" name="dniImg">
            </div> -->
          </div>
          <div class="form-group">
            <label for="centroEstudios">Centro de Estudios*</label>
            <input type="text" class="form-control" id="centroEstudios" name="centroEstudios" required>
          </div>
          <div class="form-group">
            <label for="observaciones">Observaciones</label>
            <textarea class="form-control" id="observaciones" name="observaciones"></textarea>
          </div>
        </div>
        <hr />
        <div class="row">
          <div class="col-md-6">
            <h4>Información médica del niño</h4>
            <div class="form-group">
              <label for="tarjeta_sanitaria">Foto Tarjeta sanitaria *</label>
              <input type="file" class="form-control" id="tarjeta_sanitaria" required name="tarjeta">
            </div>
            <div class="form-group">
              <label for="alergias_medicas">Alergias médicas</label>
              <textarea class="form-control" id="alergias_medicas" name="alergias_medicas"></textarea>
            </div>
            <div class="form-group">
              <label for="alergias_alimentarias">Alergias alimentarias</label>
              <textarea class="form-control" id="alergias_alimentarias" name="alergias_alimentarias"></textarea>
            </div>
            <div class="form-group">
              <label for="lesion">Lesión y fecha de la lesión</label>
              <textarea class="form-control" id="lesion" name="lesion"></textarea>
            </div>
            <div class="form-group">
              <label for="medicacion">Medicación que toma</label>
              <textarea class="form-control" id="medicacion" name="medicacion"></textarea>
            </div>
            <div class="form-group">
              <label for="motivo_medicacion">Motivo de la medicación</label>
              <textarea class="form-control" id="motivo_medicacion" name="motivo_medicacion"></textarea>
            </div>
          </div>
          <div class="col-md-6">
            <h4>Otras Informaciones</h4>

            <div class="form-group">
              <label for="discapacidad">¿Presenta discapacidad?</label>
              <textarea rows="1" class="form-control" id="discapacidad" name="discapacidad"></textarea>
            </div>
            <div class="form-group">
              <label for="reacciones_alergicas">Reacciones alérgicas*</label>
              <textarea type="text" class="form-control" id="reacciones_alergicas" required name="reacciones_alergicas"></textarea>
            </div>
            <div class="form-group">
              <label for="vacunado">¿Tiene todas las vacunas para su edad? *</label>
              <select class="form-control" id="vacunado" required name="vacunado">
                <option value="">--Seleccione--</option>
                <option value="si">Sí</option>
                <option value="no">No</option>
              </select>
            </div>
            <div class="form-group">
              <label for="antitetanica">¿Ha recibido la vacuna antitetánica? ¿Cuándo?</label>
              <textarea class="form-control" id="antitetanica" name="antitetanica" rows="2">
              </textarea>
            </div>
            <div class="form-group">
              <label for="sabe_nadar">¿Sabe nadar? *</label>
              <select class="form-control" id="sabe_nadar" required name="sabe_nadar">
                <option value="">--Seleccione--</option>
                <option value="si">Sí</option>
                <option value="no">No</option>
              </select>
            </div>
            <div class="form-group">
              <label for="aficiones">Aficiones</label>
              <textarea class="form-control" id="Aficiones" name="aficiones" rows="2"></textarea>
            </div>
            <div class="form-group">
              <label for="observaciones">Observaciones</label>
              <textarea class="form-control" id="observaciones_med" name="observaciones_med" rows="2"></textarea>
            </div>
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

<div class="modal fade" id="editNino" tabindex="-1" role="dialog" aria-labelledby="inscripcionModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="--bs-modal-width: 1000px" role="document">
    <form id="ninoEditForm" action="perfil/editNino" class="modal-content" method="POST" enctype="multipart/form-data">
      <div class="modal-header">
        <h5 class="modal-title" id="inscripcionModalLabel">Editar Niño</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

        <input type="text" id="idNino" name="idNino" value="" hidden>
      </div>
      <div class="modal-body">
        <div>
          <div class="form-group">
            <label for="nombre">Nombre*</label>
            <input type="text" class="form-control" id="nombreNE" name="nombre" required>
          </div>
          <div class="form-group">
            <label for="apellidos">Apellidos*</label>
            <input type="text" class="form-control" id="apellidosNE" name="apellidos" required>
          </div>
          <div class="form-group">
            <label for="fechaNacimiento">Fecha de Nacimiento*</label>
            <input type="date" class="form-control" id="fechaNacimientoNE" name="fechaNacimiento" required>
          </div>
          <div class="row">
            <div class="col form-group">
                <label for="dni">DNI / NIE </label>
                <input type="text" maxlength="9" class="form-control" id="dni" name="dni">
              </div>
              <!-- <div class="col form-group">
                <label for="dniImg">Modificar Imagen DNI / NIE </label>
                <input type="file" class="form-control" id="dniImg" name="dniImg">
            </div> -->
          </div>
          <div class="form-group">
            <label for="centroEstudios">Centro de Estudios*</label>
            <input type="text" class="form-control" id="centroEstudiosNE" name="centroEstudios" required>
          </div>
          <div class="form-group">
            <label for="observaciones">Observaciones</label>
            <textarea class="form-control" id="observacionesNE" name="observaciones"></textarea>
          </div>
        </div>
        <hr />
        <div class="row">
          <div class="col-md-6">
            <h4>Información médica del niño</h4>
            <div class="form-group">
              <label for="tarjeta_sanitaria">Modificar Foto Tarjeta sanitaria</label>
              <input type="file" class="form-control" id="tarjeta_sanitariaNE" name="tarjeta">
            </div>
            <div class="form-group">
              <label for="alergias_medicas">Alergias médicas</label>
              <textarea class="form-control" id="alergias_medicasNE" name="alergias_medicas"></textarea>
            </div>
            <div class="form-group">
              <label for="alergias_alimentarias">Alergias alimentarias</label>
              <textarea class="form-control" id="alergias_alimentariasNE" name="alergias_alimentarias"></textarea>
            </div>
            <div class="form-group">
              <label for="lesion">Lesión y fecha de la lesión</label>
              <textarea class="form-control" id="lesionNE" name="lesion"></textarea>
            </div>
            <div class="form-group">
              <label for="medicacion">Medicación que toma</label>
              <textarea class="form-control" id="medicacionNE" name="medicacion"></textarea>
            </div>
            <div class="form-group">
              <label for="motivo_medicacion">Motivo de la medicación</label>
              <textarea class="form-control" id="motivo_medicacionNE" name="motivo_medicacion"></textarea>
            </div>
          </div>
          <div class="col-md-6">
            <h4>Otras Informaciones</h4>

            <div class="form-group">
              <label for="discapacidad">¿Presenta discapacidad?</label>
              <textarea rows="1" class="form-control" id="discapacidadNE" name="discapacidad"></textarea>
            </div>
            <div class="form-group">
              <label for="reacciones_alergicas">Reacciones alérgicas*</label>
              <textarea type="text" class="form-control" id="reacciones_alergicasNE" required name="reacciones_alergicas"></textarea>
            </div>
            <div class="form-group">
              <label for="vacunado">¿Tiene todas las vacunas para su edad? *</label>
              <select class="form-control" id="vacunadoNE" required name="vacunado">
                <option value="">--Seleccione--</option>
                <option value="si">Sí</option>
                <option value="no">No</option>
              </select>
            </div>
            <div class="form-group">
              <label for="antitetanica">¿Ha recibido la vacuna antitetánica? ¿Cuándo?</label>
              <textarea class="form-control" id="antitetanicaNE" name="antitetanica" rows="2"></textarea>
            </div>
            <div class="form-group">
              <label for="sabe_nadar">¿Sabe nadar? *</label>
              <select class="form-control" id="sabe_nadarNE" required name="sabe_nadar">
                <option value="">--Seleccione--</option>
                <option value="si">Sí</option>
                <option value="no">No</option>
              </select>
            </div>
            <div class="form-group">
              <label for="aficiones">Aficiones</label>
              <textarea class="form-control" id="AficionesNE" name="aficiones" rows="2"></textarea>
            </div>
            <div class="form-group">
              <label for="observaciones">Observaciones</label>
              <textarea class="form-control" id="observaciones_medNE" name="observaciones_med" rows="2"></textarea>
            </div>
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

<div class="modal fade" id="infoNino">
  <div class="modal-dialog" style="--bs-modal-width:1000px;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Informacion Niño</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body d-flex flex-column gap-2">

        <div>
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

<div class="modal fade" id="infoTutor">
  <div class="modal-dialog" style="--bs-modal-width:1000px;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Informacion Tutor</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body d-flex flex-column gap-2">

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

      <div class="modal-footer">
        <button class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="pagoModal">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Aviso de pago</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
                <div class="modal-body">
                <article class="cuerpo intro tipcuerpo textleft">
                  <p>Aventurama agradece la participación en la actividad. Por favor, para proceder al pago, es
                  necesario efectuar una transferencia a la cuenta bancaria correspondiente. A continuación, se
                  podrá proceder a la confirmación del pago. Esta confirmación no es vinculante y en caso de
                  no haberse realizado correctamente, la reserva no será valida.</p><br>
                  <p>los datos de ingreso son los siguientes:</p><br>
                  <p>número de cuenta: <b>IBAN ES07 0049 1804 10 2110412864</b>, del Banco Santander, 
                    a nombre de AVENTURAMA, indicando tambien el nombre del participante y el siguiente número:</p>
                    <p>Numero de reserva: <b id="idNumReservaModal"></b></p>
                    <p>Para cualquier duda, puedes contactar con nosotros en los siguientes medios:</p>
                    <span>Teléfonos: <b>686 131 266</b> / <b>669 521 332</b> / <b>686 131 158</b>.</span>
                    <br><span>Email:<b> informacion@aventurama.es</b></span>
                </article>
                <br>
                <article class="cuerpo intro tipcuerpo textleft">
                  <p><input type="checkbox" id="confirmarPago"><span class="text-danger">
                  Al confirmar el pago, soy consciente de que he pagado la cuantía correspondiente a la reserva
                  por los medios disponibles. En caso contrario, Aventurama se reserva el derecho de cancelar la
                  reserva y efectuar las medidas correspondientes.</span></p>
                </article>
                </div>
                  
                <div class="modal-footer d-flex justify-content-between">
                  <form method="POST" action="perfil/pagarReserva" class="d-flex flex-nowrap gap-1">
                    <input hidden type="number" id="precioModalPago" step="0.01" name="precio">
                    <button class="btn btn-sm w-100 btn-primary " data-bs-dismiss="modal" id="idReservaPago" disabled name="idReserva">Confirmar Pago</button>
                  </form>
                </div>
            
        </div>
    </div>
</div>

<script src="Locals/js/perfil.js"></script>


<?php
require_once("Layouts/Footer.php");
?>