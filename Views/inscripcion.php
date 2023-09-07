<?php require_once("Layouts/Header.php");
$user = $this->d['user'];
$elem = $this->d['elem'];
$ninos = $this->d['ninos'];
$turnos = $this->d['turnos'];
?>
<link rel="stylesheet" href="Locals/css/inscripcion.css">

<section class="content">
	<h1 class="card-header">Inscripción a
		<?php echo $elem->nombre ?>
	</h1>

	<label class="p-3 bg-light shadow-sm border-bottom">Compruebe los datos a continuación y proceda a la
		inscripción.</label>
	<form class="gap-3 m-3" id="formInsc" action="inscripcion/reservarPlaza" method="POST">
		<input hidden value="<?php echo $elem->codigo ?>" name="codigo">

		<div id="userInfo" class="card p-0 overflex">
			<h3 class="card-header">Datos de usuario</h3>
			<div class="card-body rounded overflex">
				<div class="">
					<div class="form-group">
						<label for="email">Email*</label>
						<input value="<?php echo $user->email ?>" type="email" class="form-control" id="email" name="email" required>
					</div>
					<div class="form-group">
						<label for="telefono">Teléfono*</label>
						<input value="<?php echo $user->telefono ?>" type="tel" class="form-control" id="telefono" name="telefono" required>
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
						<input value="<?php echo $user->dni ?>" type="text" class="form-control" maxlength="9" id="dni" name="dni" required>
					</div>
				</div>
				<div class="">
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
						<input value="<?php echo $user->cp ?>" type="text" class="form-control" maxlength="5" id="codigoPostal" name="codigoPostal" required>
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
		</div>

		<div id="ninoList" class="card p-0 overflex">
			<?php if (count($turnos) != 0) : ?>
				<div class="card-header d-flex justify-content-between">
					<h3>Niños a inscribir</h3>

					<button type="button" data-bs-toggle="modal" data-bs-target="#inscripcionModal" class="my-1 btn btn-sm btn-danger">Añadir un niño</button>

				</div>

				<div class="card-body flex-column gap-2 overflex">

					<?php if ($ninos != false)
						foreach ($ninos as $nino) : ?>
						<div class="border rounded p-2 d-flex justify-content-between">
							<div class="d-flex gap-4">
								<button class="btn btn-sm border btnNino" data-bs-target="#editNino" data-bs-toggle="modal" value="<?php echo $nino["ID_NINO"] ?>" type="button">editar</button>

								<div class="form-group my-2" style="
										align-items: center;
										display: inline-flex;
										gap: 1rem;
									">
									<input name="nino[]" value="<?php echo $nino["ID_NINO"] ?>" type="checkbox" style="width: 1rem; height: 1rem;" class="form-check-input checkNino" id="<?php echo "nino" . $nino["ID_NINO"] ?>" />
									<span>
										<?php echo $nino['NOMBRE'] . " " . $nino['APELLIDOS'] ?>
									</span>
								</div>
							</div>

							<div class="form-group" style="
										display: inline-flex;
										justify-content: space-evenly;
										align-items: baseline;
										gap: 1rem;
									">
								<span class="form-label">Turno</span>
								<select name="turno[]" class="form-select form-select-sm w-100">
									<?php foreach ($turnos as $key => $value) : ?>
										<option value="<?php echo $value['CD_TURNO'] ?>"><?php echo $value['DS_TURNO'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>

					<?php endforeach; ?>
				</div>
			<?php else : ?>

				<div class="text-danger p-2">
					No hay turnos disponibles para la actividad
				</div>

			<?php endif; ?>
		</div>


		<div id="lastElement" class="d-flex flex-column gap-3 h-100 w-100">
			<div class="form-group">
				<label>¿Cómo nos has conocido?</label>
				<textarea class="form-control" id="conocido" name="conocido" rows="3"></textarea>
			</div>

			<button type="submit" id="submitbtn" disabled class="ms-auto my-1 btn btn-sm btn-primary">Solicitar inscripción</button>

			<p class="text-danger">Recuerde que también puede añadir responsables auxiliares desde su perfil para poder contactar si surge la necesidad :D</p>
		</div>



	</form>

</section>

<div class="modal fade" id="inscripcionModal" tabindex="-1" role="dialog" aria-labelledby="inscripcionModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="--bs-modal-width: 1000px" role="document">
		<form id="ninoForm" action="inscripcion/registroNino" class="modal-content" method="POST" enctype="multipart/form-data">
			<div class="modal-header">
				<h5 class="modal-title" id="inscripcionModalLabel">Inscripción de Niño</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<input hidden value="<?php echo $elem->codigo ?>" name="codigo">
				<div>
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
							<label for="tarjeta_sanitaria">Foto Tarjeta sanitaria*</label>
							<input type="file" class="form-control" id="tarjeta" name="tarjeta" required>
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
		<form id="ninoEditForm" action="inscripcion/editNino" class="modal-content" method="POST" enctype="multipart/form-data">
			<div class="modal-header">
				<h5 class="modal-title" id="inscripcionModalLabel">Editar Niño</h5>
				<input type="text" id="idNino" name="idNino" value="" hidden>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div>
					<input hidden value="<?php echo $elem->codigo ?>" name="codigo">
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
							<label for="dni">DNI / NIE</label>
							<input type="text" maxlength="9" class="form-control" id="dni" name="dni" >
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
							<input type="file" class="form-control" id="tarjeta_sanitariaNE"  name="tarjeta">
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
							<textarea class="form-control" id="antitetanicaNE" name="antitetanica" rows="2">
							</textarea>
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

<script src="Locals/js/inscripcion.js"></script>

<?php require_once("Layouts/Footer.php"); ?>