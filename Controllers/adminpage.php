<?php
require_once("Models/actividadesModel.php");
require_once("Models/reservasModel.php");
require_once("Models/ninoModel.php");
require_once("Models/ninoSanModel.php");
require_once("Models/userModel.php");
require_once("Models/tutoresModel.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Adminpage extends SessionController
{
    private $user;
    function __construct()
    {
        parent::__construct();
        $this->user = $this->getUserSession();

        error_log("inicio::CONSTRUCT -> Inicio de la clase adminpage");
    }

    function render()
    {
        error_log("inicio::RENDER -> " . $this->user->getEmail());
        $this->view->render('adminpage', ['user' => $this->user, 'ninos' => $this->getNinos(), 'reservas' => $this->getReservas(), 'actividades' => $this->getActividades(), 'turnos' => $this->getTurnos(), 'categorias' => $this->getCategorias()]);
    }
    function getTurnos()
    {
        $turno = new ActividadesModel();
        $turnos = $turno->getAllTurnos();
        return $turnos;
    }
    function getCategorias()
    {
        $categoria = new ActividadesModel();
        $categorias = $categoria->getAllCategorias();
        return $categorias;
    }
    function getActividades()
    {
        $actividad = new ActividadesModel();
        $actividades = $actividad->getActividades();
        if (!$actividades) {
            return array();
        }
        for ($i = 0; $i < count($actividades); $i++) {
            $actividadesArray[$i] = array(
                'actividad' => $actividades[$i],
                'turnos' => $actividad->getTurnosPlazas($actividades[$i]['CD_ACTIVIDAD']),
            );
        }

        return $actividadesArray;
    }

    function setPlazaTurno()
    {
        if (isset($_POST['actividad']) && isset($_POST['turno']) && isset($_POST['plazas'])) {
            $codigo = $_POST['actividad'];
            $turno = $_POST['turno'];
            $plazas = $_POST['plazas'];
            $actividad = new ActividadesModel();
            if ($actividad->setPlazaTurno($codigo, $turno, $plazas))
                $this->redirect('adminpage', ['success' => 'Plazas actualizadas con exito']);
            else
                $this->redirect('adminpage', ['error' => 'Error al actualizar las plazas']);
        } else {
            $this->redirect('adminpage', ['error' => 'Error al actualizar las plazas']);

            return false;
        }
    }
    function getAllInfo()
    {
        if (isset($_POST['id_nino'])) {
            $id_nino = $_POST['id_nino'];
            $nino = new NinoModel();
            $info = new NinoSanModel();
            $padre = new UserModel();
            $tutores = new TutoresModel();
            $id_padre =  $nino->getNino($id_nino)['ID_PADRE'];
            $datos = array("padre" => $padre->get($id_padre), "tutor" => $tutores->getAll($id_padre), "nino" => $nino->getNino($id_nino), "ninoSan" => $info->getInfoNino($id_nino));
            echo json_encode($datos);
        } else {
            return false;
        }
    }

    function getExcel()
    {
        $info = explode('-', $_POST['actTurno']);
        $actividad = $info[0];
        $turno = $info[1];
        $reserva = new ReservasModel();
        $excel = $reserva->getDatosExcel($actividad, $turno);
        if ($excel != false && count($excel) == 0) {
            $this->redirect('adminpage', ['error' => 'Excel no disponible, no hay datos']);
            return false;
        }
        if(!$excel){
            $this->redirect('adminpage', ['error' => 'Error al generar excel']);
            return false;
        }
        $documento = new Spreadsheet();
        $hoja = $documento->getActiveSheet();
        //cabeceras
        // Establecer las cabeceras en la primera fila
        $columna = 'A';
        foreach ($excel[0] as $cabecera => $valor) {
            $hoja->setCellValue($columna . '1', $cabecera);
            $columna++;
        }
        $hoja->setAutoFilter('A1:AP1');

        //datos
        $fila = 2;
        foreach ($excel as $key => $value) {
            $columna = 'A';
            foreach ($value as $key2 => $value2) {
                $hoja->setCellValue($columna . $fila, $value2);
                $columna++;
            }
            $fila++;
        }


        $writer = new Xlsx($documento);
        $archivoExcel = 'temp.xlsx';
        $writer->save($archivoExcel);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Excel_' . $_POST['actTurno'] . '.xlsx"');
        readfile($archivoExcel);
        $this->redirect('adminpage', ['success' => 'Excel generado con exito']);
    }

    function getReservas()
    {
        $reserva = new ReservasModel();
        $reservas = $reserva->getAll(
            array(
                'nino' => isset($_GET['ninor']) ? ($_GET['ninor'] == "null" ? "" : $_GET['ninor'] ?? "") : "",
                'turno' => isset($_GET['turnor']) ? ($_GET['turnor'] == "null" ? "" : $_GET['turnor'] ?? "") : "",
                'actividad' => isset($_GET['actividadr']) ? ($_GET['actividadr'] == "null" ? "" : $_GET['actividadr'] ?? "") : "",
                'estado' => isset($_GET['estador']) ? ($_GET['estador'] == "null" ? "" : $_GET['estador'] ?? "") : "",
            )
        );
        return $reservas;
    }
    function getNinos()
    {
        $nino = new NinoModel();
        $padre = new UserModel();
        $ninos = $nino->getAll(
            array(
                'nino' => isset($_GET['ninoc']) ? ($_GET['ninoc'] == "null" ? "" : $_GET['ninoc']) : "",
                'turno' => isset($_GET['turnoc']) ? ($_GET['turnoc'] == "null" ? "" : $_GET['turnoc']) : "",
                'actividad' => isset($_GET['actividadc']) ? ($_GET['actividadc'] == "null" ? "" : $_GET['actividadc']) : "",
            )
        );
        if (!$ninos) {
            return array();
        }
        foreach ($ninos as $key => $n) {
            $miPadre = $padre->get($n["ID_PADRE"]);
            $nino->setId($n["ID_NINO"]);
            array_push($n, $nino->descargarDni() == false ? false : true);
            array_push($n,  $nino->descargarTarjeta() == false ? false : true);
            array_push($n,  $miPadre->descargarDNI($miPadre->getId()) == false ? false : true);
            $ninos[$key] = $n;
        }
        return $ninos;
    }
    function getEstadosActividad()
    {
        if (isset($_POST['codigo'])) {
            $codigo = $_POST['codigo'];
            $actividad = new ActividadesModel();
            $datos = $actividad->getEstadosPorActividad($codigo);
            echo json_encode($datos);
        } else {

            return false;
        }
    }
    function setEstadosActividad()
    {
        if (isset($_POST['codigo'])) {
            $codigo = $_POST['codigo'];
            $turno = $_POST['turno'];
            $actividad = new ActividadesModel();
            if ($actividad->setEstadosPorCampamento($codigo, $turno))
                $this->redirect('adminpage', ['success' => 'estado de actividad actualizado con exito']);
            else
                $this->redirect('adminpage', ['error' => 'Error al actualizar el estado de la actividad']);
        } else {
            $this->redirect('adminpage', ['error' => 'Error al actualizar la actividad']);

            return false;
        }
    }
    function borrarActividad(){
        if(isset($_POST['codigo'])){
            
            $codigo = $_POST['codigo'];
            $actividad = new ActividadesModel();
            if($actividad->borrarActividad($codigo)){
                $this->redirect('adminpage', ['success' => 'Actividad borrada con exito']);
            }else{
                $this->redirect('adminpage', ['error' => 'Error al borrar la actividad']);
            }
        }else{
            $this->redirect('adminpage', ['error' => 'Error al borrar la actividad']);

            return false;
        }
    }
    function nuevaActividad()
    {
        if ($this->existPOST(['codigo', 'nombre', 'descripcion', 'turnos', 'categoria'])) {
            $codigo = $_POST['codigo'];
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $turnos = $_POST['turnos'][0] == "0" ? array("0") : $_POST['turnos'];
            $categoria = $_POST['categoria'];
            $imagen = $_POST['imagen'];
            $actividad = new ActividadesModel();
            $actividad->setCodigo($codigo);
            $actividad->setNombre($nombre);
            $actividad->setDescripcion($descripcion);
            $actividad->setTurnos($turnos);
            $actividad->setCategoria($categoria);
            $actividad->setImagen($imagen);

            if ($actividad->insertarActividad()) {
                $this->redirect('adminpage', ['success' => 'Actividad creada con exito']);
            } else {
                $this->redirect('adminpage', ['error' => 'Error al crear la actividad']);
            }
        } else {
            $this->redirect('adminpage', ['error' => 'Error al crear la actividad']);
        }
    }

    function getActividad()
    {
        if (isset($_POST['codigo'])) {
            $codigo = $_POST['codigo'];
            $actividad = new ActividadesModel();

            echo json_encode($actividad->getActividad($codigo));
        } else {
            return false;
        }
    }

    function DescargarDniPadre()
    {
        //funcion para descargar el dni del usuario
        $user = new UserModel();
        $data = $user->descargarDNI($_POST['idPadre']);
        if (!$data) {
            error_log("Inscripcion::DescargarDniUsuario -> No se ha podido descargar el dni");
            $this->redirect('perfil', ['error' => 'No se ha podido descargar el dni']);
            return false;
        }
        header("Content-type: " . $data["TIPO_ARCHIVO"]);
        header("Content-Disposition: attachment; filename=\"" . $data["NM_ARCHIVO"] . "\"");
        header("Content-Length: " . $data["SIZE_ARCHIVO"]);
        ob_clean();
        $stream = fopen('data://application/octet-stream;base64,' . base64_encode($data["DNI"]), 'r');
        fpassthru($stream);
        fclose($stream);
        // Enviar los datos binarios de la imagen al navegador
        exit;
    }
    function DescargarDniNino()
    {
        //funcion para descargar el dni del usuario
        $nino = new NinoModel();
        $nino->setId($_POST['idNino']);
        $data = $nino->descargarDNI();
        if (!$data) {
            error_log("Inscripcion::DescargarDniUsuario -> No se ha podido descargar el dni");
            $this->redirect('perfil', ['error' => 'No se ha podido descargar el dni']);
            return false;
        }
        header("Content-type: " . $data["TIPO_ARCHIVO"]);
        header("Content-Disposition: attachment; filename=\"" . $data["NM_ARCHIVO"] . "\"");
        header("Content-Length: " . $data["SIZE_ARCHIVO"]);
        ob_clean();
        $stream = fopen('data://application/octet-stream;base64,' . base64_encode($data["DNI"]), 'r');
        fpassthru($stream);
        fclose($stream);
        // Enviar los datos binarios de la imagen al navegador
        exit;
    }
    function DescargarTSNino()
    {
        //funcion para descargar el dni del usuario
        $nino = new NinoModel();
        $nino->setId($_POST['idNino']);
        $data = $nino->descargarTarjeta();

        if (!$data) {
            error_log("Inscripcion::DescargarDniUsuario -> No se ha podido descargar el dni");
            $this->redirect('perfil', ['error' => 'No se ha podido descargar el dni']);
            return false;
        }

        error_log($data["TIPO_ARCHIVO"] . " filename=" . $data["NM_ARCHIVO"] . " tamaño= " . $data["SIZE_ARCHIVO"] );

        header("Content-type: " . $data["TIPO_ARCHIVO"]);
        header("Content-Disposition: attachment; filename=\"" . $data["NM_ARCHIVO"] . "\"");
        header("Content-Length: " . $data["SIZE_ARCHIVO"]);
        ob_clean();
        $stream = fopen('data://application/octet-stream;base64,' . base64_encode($data["TARJETA"]), 'r');
        fpassthru($stream);
        fclose($stream);
        // Enviar los datos binarios de la imagen al navegador
        exit;
    }
    function EditarActividad()
    {
        if ($this->existPOST(['codigoAntiguo', 'codigo', 'nombre', 'descripcion', 'categoria'])) {
            $codigo = $_POST['codigo'];
            $codigoAntiguo = $_POST['codigoAntiguo'];
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $categoria = $_POST['categoria'];
            $imagen = $_POST['imagen'];
            $actividad = new ActividadesModel();
            $actividad->setCodigo($codigo);
            $actividad->setNombre($nombre);
            $actividad->setDescripcion($descripcion);
            $actividad->setCategoria($categoria);
            $actividad->setImagen($imagen);

            if ($actividad->editarActividad($codigoAntiguo)) {
                $this->redirect('adminpage', ['success' => 'Actividad modificada con exito']);
            } else {
                $this->redirect('adminpage', ['error' => 'Error al editar la actividad']);
            }
        } else {
            $this->redirect('adminpage', ['error' => 'Error al editar la actividad']);
        }
    }
    function registrarCategoria(){
        $actividad = new ActividadesModel();
        if($actividad->registrarCategoria($_POST['codigo'], $_POST['nombre'])){
            $this->redirect('adminpage', ['success' => 'Categoría registrada con exito']);
        }
        else{
            $this->redirect('adminpage', ['error' => 'Error al registrar la categoría']);
        }
    }

    function ActivarDesactivar()
    {
        if ($this->existPOST(['codigo', 'desactivado'])) {
            $codigo = $_POST['codigo'];
            $desactivado = $_POST['desactivado'];
            $actividad = new ActividadesModel();
            if ($actividad->activarDesactivar($codigo, $desactivado)) {
                $this->redirect('adminpage', ['success' => 'Actividad modificada con exito']);
            } else {
                $this->redirect('adminpage', ['error' => 'Error al editar la actividad']);
            }
        } else {
            $this->redirect('adminpage', ['error' => 'Error al editar la actividad']);
        }
    }

    function cancelarReserva()
    {
        //funcion para cancelar una reserva
        if ($this->existPOST(['reserva'])) {
            $id = $_POST['reserva'];
            $reserva = new ReservasModel();
            $reserva->setId($id);
            $reserva->setEstado(3);
            if ($reserva->cambiarEstado()) {
                $this->redirect('adminpage', ['success' => 'Reserva cancelada correctamente']);
                return true;
            } else {
                error_log("Inscripcion::cancelarReserva -> No se ha podido cancelar la reserva");
                $this->redirect('adminpage', ['error' => 'No se ha podido cancelar la reserva']);
                return false;
            }
        } else {
            error_log("Inscripcion::cancelarReserva -> No se han recibido todos los parametros");
            $this->redirect('adminpage', ['error' => 'No se ha podido cancelar la reserva']);
            return false;
        }
    }

    //cuando la api de pagos este operativa, esto llamará a reembolsar
    function reembolsarReserva()
    {
        //ESTO TAMBIEN VA POR PLATAFORMA DE PAGOS
        //funcion para reembolsar una reserva
        if ($this->existPOST(['reserva'])) {
            $id = $_POST['reserva'];
            $reserva = new ReservasModel();
            $reserva->setId($id);

            $data = $reserva->get($id);

            $precio = $data['PRECIO'];
            $reserva->setPrecio($precio);
            $reserva->setEstado(4);
            $reserva->desocuparPlaza($data['CD_ACTIVIDAD'], $data['CD_TURNO']);

            if ($reserva->cambiarEstado()) {
                $this->redirect('adminpage', ['success' => 'Reserva reembolsada correctamente']);
                return true;
            } else {
                error_log("Inscripcion::cancelarReserva -> No se ha podido reembolsar la reserva");
                $this->redirect('adminpage', ['error' => 'No se ha podido reembolsar la reserva']);
                return false;
            }
        } else {
            error_log("Inscripcion::cancelarReserva -> No se han recibido todos los parametros");
            $this->redirect('adminpage', ['error' => 'No se ha podido reembolsar la reserva']);
            return false;
        }
    }


    function validarReserva()
    {
        //funcion para validar una reserva
        if ($this->existPOST(['idReserva', 'precio', 'limite'])) {
            $id = $_POST['idReserva'];
            $precio = $_POST['precio'];
            $reserva = new ReservasModel();
            $reserva->setId($id);
            $reserva->setPrecio($precio);
            $reserva->setEstado(1);
            if ($reserva->cambiarEstado() && $reserva->setLimiteCancelacion($_POST['limite'])) {
                $this->redirect('adminpage', ['success' => 'Reserva validada correctamente']);
                return true;
            } else {
                error_log("Inscripcion::cancelarReserva -> No se ha podido validar la reserva");
                $this->redirect('adminpage', ['error' => 'Error al validar la reserva en la base de datos']);
                return false;
            }
        } else {
            error_log("Inscripcion::cancelarReserva -> No se han recibido todos los parametros");
            $this->redirect('adminpage', ['error' => 'No se ha podido validar la reserva']);
            return false;
        }
    }

    function EnviarEmailManual()
    {
        if ($this->existPOST(['destinatario', 'asunto', 'mensaje'])) {
            $email = $_POST['destinatario'];
            $asunto = $_POST['asunto'];
            $mensaje = $_POST['mensaje'];
            // set email subject & body

            $message = <<<MESSAGE
                            $mensaje
                            MESSAGE;

            // email header
            $header = "From: " . emailHost;
            $header .= "\r\nContent-type: text/html";

            // send the email
            if (mail($email, $asunto, $message, $header)) {
                $this->redirect('adminpage', ['success' => 'Email enviado con exito']);
            } else {
                $this->redirect('adminpage', ['error' => 'Error al enviar el email']);
            }
        } else {
            $this->redirect('adminpage', ['error' => 'No se encuentran los parametros']);
        }
    }
}
