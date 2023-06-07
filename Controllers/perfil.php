<?php
require_once("Models/ninoModel.php");
require_once("Models/ninoSanModel.php");
require_once("Models/reservasModel.php");
require_once("Models/tutoresModel.php");
require_once("Models/pdfModel.php");
class perfil extends SessionController
{
    public $user;
    function __construct()
    {
        parent::__construct();
        $this->user = $this->getUserSession();
        error_log("inicio::CONSTRUCT -> Inicio de la clase inicio");
    }

    function render()
    {
        $this->view->render('perfil', ['user' => $this->user, 'reservas' => $this->obtenerReservas(), 'ninos' => $this->getNinos(), 'tutores' => $this->getTutores()]);
    }

    function getNinos()
    {
        $nino = new NinoModel();
        $ninos = $nino->getNinos();
        foreach ($ninos as $key => $n) {
            $nino->setId($n["ID_NINO"]);
            array_push($n, $nino->descargarDni() != false ? true : false);
            array_push($n,  $nino->descargarTarjeta() != false ? true : false);
            $ninos[$key] = $n;
        }
        return $ninos;
    }
    function getTutores()
    {
        $tutores = new TutoresModel();
        return $tutores->getAll($_SESSION['user']);
    }

    function getNino()
    {
        if ($this->existPOST(['id'])) {
            $nino = new NinoModel();
            $info = new NinoSanModel();
            $minino = $nino->getNino($_POST['id']);
            $miinfo = $info->getInfoNino($_POST['id']);
            echo json_encode(array('info' => $miinfo, 'nino' => $minino));
        } else {
            error_log("perfil::getNino -> No se han recibido todos los parametros");
            return false;
        }
    }
    function getTutor()
    {
        if ($this->existPOST(['idTutor'])) {
            $tutor = new TutoresModel();
            $mitutor = $tutor->get($_POST['idTutor'], $_SESSION['user']);
            echo json_encode($mitutor);
        } else {
            error_log("perfil::getNino -> No se han recibido todos los parametros");
            return false;
        }
    }

    function borrarTutor()
    {
        if ($this->existPOST(['idTutor'])) {
            $tutor = new TutoresModel();
            $tutor->delete($_POST['idTutor']);
            $this->redirect('perfil', ['success' => 'Tutor borrado correctamente']);
        } else {
            error_log("perfil::getNino -> No se han recibido todos los parametros");
            $this->redirect('perfil', ['error' => 'No se pudo borrar el tutor']);
        }
    }

    function factura()
    {
        if ($this->existPOST(['idReserva'])) {
            $pdf = new FacturaPDF();
            $reservas = new ReservasModel();
            $reserva = $reservas->getDatosFactura($_POST['idReserva']);
            //necesitamos activar la extension PHP GD
            $pdf->AddFont('DejaVu', '', 'DejaVuSansCondensed.ttf', true);
            $pdf->AddFont('DejaVu', 'B', 'DejaVuSansCondensed-Bold.ttf', true);
            $pdf->AddFont('DejaVu', 'I', 'DejaVuSansCondensed-Oblique.ttf', true);
            $pdf->AddFont('DejaVu', 'BI', 'DejaVuSansCondensed-BoldOblique.ttf', true);
            $pdf->AddPage();
            $pdf->Content($reserva);
            $pdf->Output('FACTURA_' . $reserva['FECHA'] . "" . $reserva['ID'] . '.pdf', 'D', true);
            //$this->redirect('perfil', ['success' => 'Factura generada correctamente']);
        } else {
            error_log("perfil::getNino -> No se han recibido todos los parametros");
            $this->redirect('perfil', ['error' => 'No se pudo generar la factura']);
        }
    }

    function registroNino()
    {
        //para el niño
        if ($this->existPOST(['reacciones_alergicas', 'sabe_nadar', 'vacunado', 'nombre', 'apellidos', 'fechaNacimiento', 'centroEstudios'])) {
            $nombre = $_POST['nombre'];
            $apellidos = $_POST['apellidos'];
            $fechaNac = $_POST['fechaNacimiento'];
            $centroEstudios = $_POST['centroEstudios'];
            $observaciones = isset($_POST['observaciones']) ? $_POST['observaciones'] : "";
            $dni = isset($_POST['dni']) ? $_POST['dni'] : "";

            $nino = new NinoModel();
            $nino->setNombre($nombre);
            $nino->setApellidos($apellidos);
            $nino->setFechaNacimiento($fechaNac);
            $nino->setCentroEstudios($centroEstudios);
            $nino->setObservaciones($observaciones);




            $id = $nino->registrarNino();
            if (!isset($id) || $id == false) {
                error_log("Inscripcion::registroNino -> No se ha podido registrar el niño");
                $this->redirect('perfil', ['error' => 'No se ha podido registrar el niño']);
                return false;
            }
            //para la info sanitaria
            $info = new NinoSanModel();
            $info->setAlergiasMedicas(isset($_POST['alergias_medicas']) ? $_POST['alergias_medicas'] : "");
            $info->setAlergiasAlimentarias(isset($_POST['alergias_alimentarias']) ? $_POST['alergias_alimentarias'] : "");
            $info->setLesion(isset($_POST['lesion']) ? $_POST['lesion'] : "");
            $info->setMedicacion(isset($_POST['medicacion']) ? $_POST['medicacion'] : "");
            $info->setMotivosMedicacion(isset($_POST['motivo_medicacion']) ? $_POST['motivo_medicacion'] : "");
            $info->setDiscapacidad(isset($_POST['discapacidad']) ? $_POST['discapacidad'] : "");
            $info->setReaccionesAlergicas(isset($_POST['reacciones_alergicas']) ? $_POST['reacciones_alergicas'] : "");
            $info->setVacunado($_POST['vacunado']);
            $info->setTetanos(isset($_POST['antitetanica']) ? $_POST['antitetanica'] : "");
            $info->setNadar($_POST['sabe_nadar']);
            $info->setAficiones(isset($_POST['aficiones']) ? $_POST['aficiones'] : "");
            $info->setObservacionesMed(isset($_POST['observaciones_med']) ? $_POST['observaciones_med'] : "");


            if (!$info->registrarInfoNino($id)) {
                error_log("Inscripcion::registroNino -> No se ha podido registrar la info sanitaria del niño");
                $this->redirect('perfil', ['error' => 'No se ha podido registrar la info sanitaria del niño']);
                return false;
            };
            //para los archivos
            if ($_FILES["dniImg"]["name"] != "") {
                $nino->guardarDNI($id, $_FILES["dniImg"]);
            }
            if ($_FILES["tarjeta"]["name"] != "") {
                $info->guardarTarjeta($id, $_FILES["tarjeta"]);
            }
        } else {
            error_log("Inscripcion::registroNino -> No se han recibido todos los parametros");
            return false;
        }
        $this->redirect('perfil', ['success' => 'Niño registrado correctamente']);
        //redireccion cuando se registre el niño
        return true;
    }

    function editNino()
    {
        if ($this->existPOST(['idNino', 'reacciones_alergicas', 'sabe_nadar', 'vacunado', 'nombre', 'apellidos', 'fechaNacimiento', 'centroEstudios'])) {
            $id = $_POST['idNino'];
            $nombre = $_POST['nombre'];
            $apellidos = $_POST['apellidos'];
            $fechaNac = $_POST['fechaNacimiento'];
            $centroEstudios = $_POST['centroEstudios'];
            $observaciones = isset($_POST['observaciones']) ? $_POST['observaciones'] : "";
            $dni = isset($_POST['dni']) ? $_POST['dni'] : "";

            $nino = new NinoModel();
            $nino->setNombre($nombre);
            $nino->setDni($dni);
            $nino->setApellidos($apellidos);
            $nino->setFechaNacimiento($fechaNac);
            $nino->setCentroEstudios($centroEstudios);
            $nino->setObservaciones($observaciones);

            if (!$nino->editarNino($id)) {
                error_log("Inscripcion::registroNino -> No se ha podido registrar el niño");
                $this->redirect('perfil', ['error' => 'No se ha podido modificar el niño']);
                return false;
            }

            $info = new NinoSanModel();
            $info->setAlergiasMedicas(isset($_POST['alergias_medicas']) ? $_POST['alergias_medicas'] : "");
            $info->setAlergiasAlimentarias(isset($_POST['alergias_alimentarias']) ? $_POST['alergias_alimentarias'] : "");
            $info->setLesion(isset($_POST['lesion']) ? $_POST['lesion'] : "");
            $info->setMedicacion(isset($_POST['medicacion']) ? $_POST['medicacion'] : "");
            $info->setMotivosMedicacion(isset($_POST['motivo_medicacion']) ? $_POST['motivo_medicacion'] : "");
            $info->setDiscapacidad(isset($_POST['discapacidad']) ? $_POST['discapacidad'] : "");
            $info->setReaccionesAlergicas(isset($_POST['reacciones_alergicas']) ? $_POST['reacciones_alergicas'] : "");
            $info->setVacunado($_POST['vacunado']);
            $info->setTetanos(isset($_POST['antitetanica']) ? $_POST['antitetanica'] : "");
            $info->setNadar($_POST['sabe_nadar']);
            $info->setAficiones(isset($_POST['aficiones']) ? $_POST['aficiones'] : "");
            $info->setObservacionesMed(isset($_POST['observaciones_med']) ? $_POST['observaciones_med'] : "");

            if (!$info->editarInfoNino($id)) {
                error_log("Inscripcion::registroNino -> No se ha podido registrar la info sanitaria del niño");
                $this->redirect('perfil', ['error' => 'No se ha podido modificar la info sanitaria del niño']);
                return false;
            }
            //para los archivos
            if ($_FILES["dniImg"]["name"] != "") {
                $nino->guardarDNI($id, $_FILES["dniImg"]);
            }
            if ($_FILES["tarjeta"]["name"] != "") {
                $info->guardarTarjeta($id, $_FILES["tarjeta"]);
            }
        } else {
            error_log("Inscripcion::registroNino -> No se han recibido todos los parametros");
            $this->redirect('perfil', ['error' => 'Faltan datos para modificar el niño']);

            return false;
        }
        $this->redirect('perfil', ['success' => 'Niño modificado correctamente']);
        //redireccion cuando se registre el niño
        return true;
    }
    function obtenerReservas()
    {
        //esto para ver las reservas realizadas en total desde el perfil
        $reserva = new ReservasModel();
        $reserva->setUsuario($_SESSION['user']);
        return $reserva->getAllByUser();
    }

    function cancelarReserva()
    {
        //funcion para cancelar una reserva
        if ($this->existPOST(['idReserva'])) {
            $id = $_POST['idReserva'];
            $reserva = new ReservasModel();
            $reserva->setId($id);
            $reserva->setEstado(3);
            if ($reserva->cambiarEstado()) {
                $this->redirect('perfil', ['success' => 'Reserva cancelada correctamente']);
                return true;
            } else {
                error_log("Inscripcion::cancelarReserva -> No se ha podido cancelar la reserva");
                $this->redirect('perfil', ['error' => 'No se ha podido cancelar la reserva']);
                return false;
            }
        } else {
            error_log("Inscripcion::cancelarReserva -> No se han recibido todos los parametros");
            $this->redirect('perfil', ['error' => 'No se ha podido cancelar la reserva']);
            return false;
        }
    }

    function pagarReserva($idReserva = ['idReserva' => null])
    {
        //esto está provisional para que se pueda pagar la reserva
        //WIP hay que montar la plataforma de pago para que se pueda pagar
        //ESTO SE IMPLEMENTARÁ EN LA VERSIÓN FINAL DEL PROYECTO
        //DE MOMENTO ESTO ES UNA PRUEBA PARA MOSTRAR EL ESTADO PAGADO
        if ($this->existPOST(['idReserva']) || ($idReserva["idReserva"] != null && isset($_SESSION['user']))) {
            $id = $_POST['idReserva'] ?? $idReserva["idReserva"];
            $reserva = new ReservasModel();
            $reserva->setId($id);
            $data = $reserva->get($id);

            $precio = $data['PRECIO'];
            $reserva->setPrecio($precio);
            //PROVISIONAL !!!!!!!!!!! IMPORTANTE CAMBIAR PARA LLAMAR A LA PASARELA DE PAGO
            $reserva->setEstado(2);
            $reserva->ocuparPlaza($data['CD_ACTIVIDAD'], $data['CD_TURNO']);

            if ($reserva->cambiarEstado()) {
                $this->redirect('perfil', ['success' => "Reserva pagada de $precio € correctamente"]);
                return true;
            } else {
                error_log("Inscripcion::cancelarReserva -> No se ha podido pagar la reserva");
                $this->redirect('perfil', ['error' => 'No se ha podido cancelar la reserva']);
                return false;
            }
        } else {
            error_log("Inscripcion::cancelarReserva -> No se han recibido todos los parametros");
            $this->redirect('perfil', ['error' => 'No se ha podido pagar la reserva']);
            return false;
        }
    }


    function DescargarDniUsuario()
    {
        //funcion para descargar el dni del usuario
        $user = new UserModel();
        $data = $user->descargarDNI($_SESSION['user']);
        if (!$data) {
            error_log("Inscripcion::DescargarDniUsuario -> No se ha podido descargar el dni");
            $this->redirect('perfil', ['error' => 'No se ha podido descargar el dni']);
            return false;
        }
        header("Content-type: " . $data["TIPO_ARCHIVO"]);
        header("Content-Disposition: attachment; filename=" . $data["NM_ARCHIVO"]);
        header("Content-Length: " . $data["SIZE_ARCHIVO"]);

        // Enviar los datos binarios de la imagen al navegador
        return $data["DNI"];
    }
    function DescargarDniNino()
    {
        //funcion para descargar el dni del niño
        $nino = new NinoModel();
        $nino->setId($_POST["idNino"]);
        $data = $nino->descargarDNI();
        if (!$data) {
            error_log("Inscripcion::DescargarDniUsuario -> No se ha podido descargar el dni");
            $this->redirect('perfil', ['error' => 'No se ha podido descargar el dni']);
            return false;
        }
         header("Content-type: " . $data["TIPO_ARCHIVO"]);
         header("Content-Disposition: attachment; filename=" . $data["NM_ARCHIVO"]);
         header("Content-Length: " . $data["SIZE_ARCHIVO"]);

        // // Enviar los datos binarios de la imagen al navegador
         return $data["DNI"];
        }
    function DescargarTSNino()
    {
        //funcion para descargar la tarjeta sanitaria
        $nino = new NinoModel();
        $nino->setId($_POST['idNino']);
        $data = $nino->descargarTarjeta();
        if (!$data) {
            error_log("Inscripcion::DescargarDniUsuario -> No se ha podido descargar");
            $this->redirect('perfil', ['error' => 'No se ha podido descargar']);
            return false;
        }
        header("Content-type: " . $data["TIPO_ARCHIVO"]);
        header("Content-Disposition: attachment; filename=" . $data["NM_ARCHIVO"]);
        header("Content-Length: " . $data["SIZE_ARCHIVO"]);

        // Enviar los datos binarios de la imagen al navegador
        return $data["TARJETA"];
    }
    function infoTutor()
    {
        //funcion para cambiar la info de usuario con un update en el model
        if ($this->existPOST(['nombre', 'apellidos', 'email', 'telefono', 'direccion', 'direccion2', 'dni', 'cp', 'localidad', 'provincia'])) {
            $nombre = $_POST['nombre'];
            $apellidos = $_POST['apellidos'];
            $email = $_POST['email'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];
            $direccion2 = $_POST['direccion2'];
            $dni = $_POST['dni'];
            $cp = $_POST['cp'];
            $localidad = $_POST['localidad'];
            $provincia = $_POST['provincia'];
            $idPrincipal = $_SESSION['user'];
            $tutor = new TutoresModel();
            $tutor->setNombre($nombre);
            $tutor->setApellidos($apellidos);
            $tutor->setEmail($email);
            $tutor->setTelefono($telefono);
            $tutor->setDireccion($direccion);
            $tutor->setDireccion2($direccion2);
            $tutor->setDni($dni);
            $tutor->setCp($cp);
            $tutor->setLocalidad($localidad);
            $tutor->setProvincia($provincia);
            $tutor->setIdPrincipal($idPrincipal);
            if ($_POST['EidTutor'] != "") {
                $tutor->setId($_POST['EidTutor']);
                if (!$tutor->update()) {
                    $this->redirect('perfil', ['error' => 'No se ha podido actualizar la información del tutor']);
                }
            } else {
                if (!$tutor->save()) {
                    $this->redirect('perfil', ['error' => 'No se ha podido actualizar la información del tutor']);
                }
            }

            $this->redirect('perfil', ['success' => 'Se ha actualizado la información del tutor']);
        } else {

            $this->redirect('perfil', ['error' => 'No se ha podido actualizar la información del tutor']);
        }
    }
    function infoUsuario()
    {
        //funcion para cambiar la info de usuario con un update en el model
        if ($this->existPOST(['nombre', 'apellidos', 'email', 'telefono', 'direccion', 'direccion2', 'dni', 'cp', 'localidad', 'provincia'])) {
            $nombre = $_POST['nombre'];
            $apellidos = $_POST['apellidos'];
            $email = $_POST['email'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];
            $direccion2 = $_POST['direccion2'];
            $dni = $_POST['dni'];
            $cp = $_POST['cp'];
            $localidad = $_POST['localidad'];
            $provincia = $_POST['provincia'];
            $id = $_SESSION['user'];
            $user = new UserModel();
            $user->setNombre($nombre);
            $user->setApellidos($apellidos);
            $user->setEmail($email);
            $user->setTelefono($telefono);
            $user->setDireccion($direccion);
            $user->setDireccion2($direccion2);
            $user->setDni($dni);
            $user->setCp($cp);
            $user->setLocalidad($localidad);
            $user->setProvincia($provincia);
            $user->setId($id);

            if (!$user->update()) {
                $this->redirect('perfil', ['error' => 'No se ha podido actualizar la información del usuario']);
            }

            if ($_FILES["dniImg"]["name"] != "") {
                $user->guardarDNI($id, $_FILES["dniImg"]);
            }

            $this->redirect('perfil', ['success' => 'Se ha actualizado la información del usuario']);
        }
    }
}
