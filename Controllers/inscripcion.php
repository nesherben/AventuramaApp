<?php

require_once("Models/ninoModel.php");
require_once("Models/ninoSanModel.php");
require_once("Models/reservasModel.php");

class Inscripcion extends SessionController{
    private $user;
    public $model;
    public $actividad;
    function __construct(){
        parent::__construct();
        $this->user = $this->getUserSession();
        
        error_log("Inscripcion::CONSTRUCT -> Inicio de la clase Inscripcion");
    }
    
    function render(){
        if(!isset($_GET['elem']) || $_GET['elem'] == "" || $this->model->exist($_GET['elem']) == false){
            
            error_log("Inscripcion::RENDER -> No se ha recibido el parametro elem");
            $this->view->render('error');
            return;
        
        }
        $this->actividad = $this->model->get($_GET['elem']);
        error_log("Inscripcion::RENDER -> Inicio de la view Inscripcion ".$_GET['elem']."");
        $this->view->render('inscripcion', ['user' => $this->user,'turnos' => $this->getTurnosDisponibles(), 'ninos' => $this->getNinos(), 'elem' => $this->actividad]);
    }

    function registroNino(){
        //para el niño
        if($this->existPOST(['codigo','reacciones_alergicas','sabe_nadar','vacunado','nombre', 'apellidos', 'fechaNacimiento', 'centroEstudios'])){
            $nombre = $_POST['nombre'];
            $apellidos = $_POST['apellidos'];
            $fechaNac = $_POST['fechaNacimiento'];
            $centroEstudios = $_POST['centroEstudios'];
            $observaciones = isset($_POST['observaciones']) ? $_POST['observaciones'] : "";
            $dni = isset($_POST['dni']) ? $_POST['dni'] : "";
            $nino = new NinoModel();
            $nino->setDni($dni);
            $nino->setNombre($nombre);
            $nino->setApellidos($apellidos);
            $nino->setFechaNacimiento($fechaNac);
            $nino->setCentroEstudios($centroEstudios);
            $nino->setObservaciones($observaciones);


            $id = $nino->registrarNino();
            if(!isset($id) || $id == false){            
                error_log("Inscripcion::registroNino -> No se ha podido registrar el niño");
                $this->redirect('inscripcion', ['elem' => $_POST['codigo'],'error' => 'No se ha podido registrar el niño']);
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

            if(!$info->registrarInfoNino($id)){
                error_log("Inscripcion::registroNino -> No se ha podido registrar la info sanitaria del niño");
                $this->redirect('inscripcion', ['elem' => $_POST['codigo'],'error' => 'No se ha podido registrar la info sanitaria del niño']);
                return false;
            };
            //para los archivos
            // if($_FILES["dniImg"]["name"]!=""){
            //     $nino->guardarDNI($id,$_FILES["dniImg"]);  
            // }
            if($_FILES["tarjeta"]["name"]!=""){  
                $info->guardarTarjeta($id,$_FILES["tarjeta"]);
            }
        }else{
            error_log("Inscripcion::registroNino -> No se han recibido todos los parametros");
            $this->redirect('inscripcion', ['elem' => $_POST['codigo'],'error' => 'Faltan datos para modificar el niño']);

            return false;
        }       
        $this->redirect('inscripcion', ['elem' => $_POST['codigo'],'success' => 'Niño registrado correctamente']);
        //redireccion cuando se registre el niño
        return true;
        
    }

    function getNino(){
        if($this->existPOST(['id'])){
            
            $nino = new NinoModel();
            $info = new NinoSanModel();
            $minino = $nino->getNino($_POST['id']);
            $miinfo = $info->getInfoNino($_POST['id']);
            echo json_encode(array('info' => $miinfo, 'nino' => $minino));
        }else{
            error_log("Inscripcion::getNino -> No se han recibido todos los parametros");
            return false;
        }
    }

    function editNino(){
        if($this->existPOST(['codigo','idNino','reacciones_alergicas','sabe_nadar','vacunado','nombre', 'apellidos', 'fechaNacimiento', 'centroEstudios'])){
            $id = $_POST['idNino'];
            $nombre = $_POST['nombre'];
            $apellidos = $_POST['apellidos'];
            $fechaNac = $_POST['fechaNacimiento'];
            $dni = isset($_POST['dni']) ? $_POST['dni'] : "";
            $centroEstudios = $_POST['centroEstudios'];
            $observaciones = isset($_POST['observaciones']) ? $_POST['observaciones'] : "";

            $nino = new NinoModel();
            $nino->setDni($dni);
            $nino->setNombre($nombre);
            $nino->setApellidos($apellidos);
            $nino->setFechaNacimiento($fechaNac);
            $nino->setCentroEstudios($centroEstudios);
            $nino->setObservaciones($observaciones);           

            if(!$nino->editarNino($id)){            
                error_log("Inscripcion::registroNino -> No se ha podido registrar el niño");
                $this->redirect('inscripcion', ['elem' => $_POST['codigo'],'error' => 'No se ha podido modificar el niño']);
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

            if(!$info->editarInfoNino($id)){
                error_log("Inscripcion::registroNino -> No se ha podido registrar la info sanitaria del niño");
                $this->redirect('inscripcion', ['elem' => $_POST['codigo'], 'error' => 'No se ha podido modificar la info sanitaria del niño']);
                return false;
            }
            //para los archivos
            // if($_FILES["dniImg"]["name"]!=""){
            //     $nino->guardarDNI($id,$_FILES["dniImg"]);  
            // }
            if($_FILES["tarjeta"]["name"]!=""){  
                $info->guardarTarjeta($id,$_FILES["tarjeta"]);
            }
        }else{
            error_log("Inscripcion::registroNino -> No se han recibido todos los parametros");
            $this->redirect('inscripcion', ['elem' => $_POST['codigo'],'error' => 'Faltan datos para modificar el niño']);

            return false;
        }       
        $this->redirect('inscripcion', ['elem' => $_POST['codigo'],'success' => 'Niño modificado correctamente']);
        //redireccion cuando se registre el niño
        return true;
    }

    function getNinos(){
        $nino = new NinoModel();
        $ninos = $nino->getNinos();
        return $ninos;
    }
    function getTurnosDisponibles(){
        $turnos = $this->model->getAllTurnos($_GET['elem']);
        
        $turnosDisponibles = $turnos; 
        return $turnosDisponibles;
    }

    function reservarPlaza(){
        if($this->existPOST(['codigo','email','telefono','nombre','apellidos','dni','direccion',
        'codigoPostal','localidad', 'provincia', 'nino', 'turno'])){
            $usumodel = new UserModel();
            $actividad = $_POST['codigo'];
            $usumodel->setId($_SESSION['user']);
            $usumodel->setEmail($_POST['email']);
            $usumodel->setTelefono($_POST['telefono']);
            $usumodel->setNombre($_POST['nombre']);
            $usumodel->setApellidos($_POST['apellidos']);
            $usumodel->setDni($_POST['dni']);
            $usumodel->setDireccion($_POST['direccion']);
            $usumodel->setDireccion2($_POST['direccion2']);
            $usumodel->setCp($_POST['codigoPostal']);
            $usumodel->setLocalidad($_POST['localidad']);
            $usumodel->setProvincia($_POST['provincia']);
            if(!$usumodel->update()){
                error_log("Inscripcion::reservarPlaza -> No se ha podido actualizar el usuario");
                $this->redirect('inscripcion', ['elem' => $actividad,'error' => 'No se ha podido reservar la plaza, error de datos de usuario']);
            }
            
            $ninos = $_POST['nino'];
            $turnos = $_POST['turno'];

            foreach($ninos as $keypos=>$nino){
                $reserva = new ReservasModel();
                $reserva->setActividad($actividad);
                $reserva->setNino($nino);
                $reserva->setTurno($turnos[$keypos]);
                $reserva->setUsuario($_SESSION['user']);
                $reserva->setConocido($_POST['conocido']);
                if(!$reserva->inscripcion()){
                    error_log("Inscripcion::reservarPlaza -> No se ha podido reservar la plaza");
                    $this->redirect('inscripcion', ['elem' => $actividad,'error' => 'No se ha podido reservar la plaza']);
                }
            }
            
                $this->redirect('perfil', ['success' => 'Reserva realizada correctamente, en breve se pondrán en contacto para proceder al pago']);
         
        }
        else{
            error_log("Inscripcion::reservarPlaza -> No se han recibido todos los parametros");
            $this->redirect('inscripcion', ['elem' => $_POST['codigo'],'error' => 'No se ha podido reservar la plaza']);
        }
    }
    
}

?>