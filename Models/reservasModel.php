<?php
class reservasModel extends Model
{
  public $id;
  public $usuario;
  public $nino;
  public $actividad;
  public $turno;
  public $conocido;
  public $fecha;
  public $estado;
  public $precio;

  function __construct()
  {
    parent::__construct();
    $this->id = "";
    $this->usuario = "";
    $this->nino = "";
    $this->actividad = "";
    $this->turno = "";
    $this->conocido = "";
    $this->fecha = "";
    $this->estado = "";
    $this->precio = 0;
  }

  public function get($id)
  {
    try {
      $query = $this->prepare('SELECT * FROM reservas re join estados_reserva er on re.ESTADO = er.CD_ESTADO
                                      join tipos_act ta on re.CD_ACTIVIDAD = ta.CD_ACTIVIDAD
                                      left join turnos_act tu on re.TURNO = tu.CD_TURNO
                                      join usuarios us on re.ID_USUARIO = us.ID_USUARIO
                                      join ninos ni on re.ID_NINO = ni.ID_NINO
                                      where re.ID_RESERVA = :id');
      $query->execute([
        'id' => $id
      ]);
      $reserva = $query->fetch(PDO::FETCH_ASSOC);
      return $reserva;
    } catch (PDOException $e) {
      error_log('RESERVAS::GET->PDOException ' . $e);
      return false;
    }
  }
  public function exist($id)
  {
  }
  public function getDatosFactura(string $id)
  {
    try {
      // aqui seleccionaremos todos los datos de usuario: nombre completo, direccion, telefono, email
      // seleccionar los datos de la reserva como precio, fecha, actividad, turno...
      // seleccionar niño cliente
      // la referencia es el Id de la reseva - fecha sin separadores

      $query = $this->prepare('SELECT re.ID_RESERVA as ID, re.FH_RESERVA as FECHA, re.PRECIO as PRECIO,
                                      ca.DS_CATEGORIA as CATEGORIA, ta.NM_ACTIVIDAD as ACTIVIDAD, tu.DS_TURNO as TURNO,
                                      us.NOMBRE as UNOMBRE, us.APELLIDOS as UAPELLIDOS, us.DIRECCION as UDIRECCION,
                                      us.DIRECCION2 as UDIRECCION2, us.NUM_TLFN as TELEFONO, us.EMAIL as EMAIL,
                                      us.CP as CP, us.LOCALIDAD as LOCALIDAD, us.PROVINCIA as PROVINCIA, us.DNI as DNI,
                                      ni.NOMBRE as NNOMBRE, ni.APELLIDOS as NAPELLIDOS
                                      FROM reservas re 
                                      join tipos_act ta on re.CD_ACTIVIDAD = ta.CD_ACTIVIDAD
                                      join categorias_act ca on ta.CT_ACTIVIDAD= ca.CD_CATEGORIA
                                      left join turnos_act tu on re.TURNO = tu.CD_TURNO
                                      join usuarios us on re.ID_USUARIO = us.ID_USUARIO
                                      join ninos ni on re.ID_NINO = ni.ID_NINO
                                      where re.ID_RESERVA = :id');
      $query->execute([
        'id' => $id
      ]);
      $reserva = $query->fetch(PDO::FETCH_ASSOC);
      return $reserva;
    } catch (PDOException $e) {
      error_log('RESERVAS::GET->PDOException ' . $e);
      return false;
    }
  }
  public function  getDatosExcel($actividad, $turno)
  {
    try {
      $query = $this->prepare('SELECT re.ID_RESERVA as "ID reserva", re.FH_RESERVA as "Fecha de reserva", re.PRECIO as "Precio",
                                      ca.DS_CATEGORIA as "Categoria de act.", ta.NM_ACTIVIDAD as "Actividad", tu.DS_TURNO as "Turno",
                                      us.NOMBRE as "Nombre de usuario", us.APELLIDOS as "Apellidos de usuario", 
                                      CONCAT(us.DIRECCION , " ", us.DIRECCION2) as "Direccion de usuario",
                                      us.NUM_TLFN as "Telefono de usuario", us.EMAIL as "Email de usuario" ,
                                      us.CP as "Cod. Postal usuario", us.LOCALIDAD as "Localidad usuario", us.PROVINCIA as "Provincia usuario", us.DNI as "DNI usuario",
                                      ni.NOMBRE as "Nombre de niño", ni.APELLIDOS as "Apellidos de niño", ni.CENTRO_ESTUDIOS as "Centro de estudios niño",
                                      ni.OBSERVACIONES as "Observaciones niño", ni.FH_NACIMIENTO as "Fecha de nacimiento niño", ni.DNI as "DNI niño",
                                      isa.ALERGIA_ALI as "Alergia alimentaria niño",  isa.ALERGIA_MED as "Alergias Medicas niño", isa.MED_ACTUAL as "Medicacion niño",
                                      isa.LESION as "Lesiones niño", isa.DISCAPACIDAD as "Discapacidad niño", isa.REAC_ALERGICA as "Reaccion alergica niño",
                                      isa.VACUNADO as "Tiene todas las vacunas de su edad", isa.ANTITETANICA as "Tiene antitetanica", isa.NATACION as "Sabe nadar",
                                      isa.AFICIONES as "Aficiones niño", isa.OBSERVACIONES as "Observaciones medicas niño", 
                                      ts.NOMBRE as "Nombre de tutor", ts.APELLIDOS as "Apellidos de tutor", ts.DNI as "DNI tutor", ts.NUM_TLFN as "Telefono de tutor",
                                      ts.EMAIL as "Email de tutor", CONCAT(ts.DIRECCION, " ", ts.DIRECCION2) as "Direccion de tutor", ts.CP as "Cod. Postal tutor", ts.LOCALIDAD as "Localidad tutor",
                                      ts.PROVINCIA as "Provincia tutor"
                                      FROM reservas re 
                                      join tipos_act ta on re.CD_ACTIVIDAD = ta.CD_ACTIVIDAD
                                      join categorias_act ca on ta.CT_ACTIVIDAD= ca.CD_CATEGORIA
                                      left join turnos_act tu on re.TURNO = tu.CD_TURNO
                                      join usuarios us on re.ID_USUARIO = us.ID_USUARIO
                                      join tutores ts on ts.ID_PRINCIPAL = us.ID_USUARIO
                                      join ninos ni on re.ID_NINO = ni.ID_NINO
                                      join info_sanitaria isa on re.ID_NINO = isa.ID_NINO
                                      where re.CD_ACTIVIDAD = :actividad and re.TURNO = :turno
                                      and (re.ESTADO = 6 or re.estado = 2 or re.estado = 5)');
      $query->execute([
        'actividad' => trim($actividad),
        'turno' => trim($turno)
      ]);
      $reservas = $query->fetchAll(PDO::FETCH_ASSOC);
      return $reservas;
    } catch (PDOException $e) {
      error_log('RESERVAS::GETDATOSEXCEL->PDOException ' . $e);
      return false;
    }
  }
  public function getAll($filtro = null)
  {
    try {
      $query = $this->prepare('SELECT * FROM reservas re 
                                  join estados_reserva er on re.ESTADO = er.CD_ESTADO
                                  join tipos_act ta on re.CD_ACTIVIDAD = ta.CD_ACTIVIDAD
                                  left join turnos_act tu on re.TURNO = tu.CD_TURNO
                                  join usuarios us on re.ID_USUARIO = us.ID_USUARIO
                                  join ninos ni on re.ID_NINO = ni.ID_NINO
                                  where re.ESTADO != 7
                                  and (re.estado = :estado or :estado2 = "")
                                   and ( (ni.NOMBRE like :nino or ni.APELLIDOS like :nino2 ) or :nino3 = "")
                                   and ( re.CD_ACTIVIDAD = :actividad or :actividad2 = "")
                                   and ( tu.CD_TURNO = :turno or :turno2 = "")
                                  ORDER BY re.FH_RESERVA DESC');

      $query->execute([

        'nino' => '%' . $filtro['nino'] . '%',
        'nino2' => '%' . $filtro['nino'] . '%',
        'nino3' => '%' . $filtro['nino'] . '%',
        'actividad' => $filtro['actividad'],
        'actividad2' => $filtro['actividad'],
        'turno' => $filtro['turno'],
        'turno2' => $filtro['turno'],
        'estado' => $filtro['estado'],
        'estado2' => $filtro['estado']

      ]);

      $reservas = $query->fetchAll(PDO::FETCH_ASSOC);
      return $reservas;
    } catch (PDOException $e) {
      error_log('RESERVAS::GETALL->PDOException ' . $e);
      return false;
    }
  }

  public function getAllByUser()
  {
    try {
      $query = $this->prepare('SELECT * FROM reservas re join estados_reserva er on re.ESTADO = er.CD_ESTADO
                                    join tipos_act ta on re.CD_ACTIVIDAD = ta.CD_ACTIVIDAD
                                    left join turnos_act tu on re.TURNO = tu.CD_TURNO
                                    join ninos ni on re.ID_NINO = ni.ID_NINO
                                    where re.ID_USUARIO = :usuario
                                    ORDER BY re.FH_RESERVA DESC');
      $query->execute([
        'usuario' => $this->usuario
      ]);
      $reservas = $query->fetchAll(PDO::FETCH_ASSOC);
      return $reservas;
    } catch (PDOException $e) {
      error_log('RESERVAS::GETALL->PDOException ' . $e);
      return false;
    }
  }

  public function inscripcion()
  {
    try {
      $query = $this->prepare('INSERT INTO reservas (ID_USUARIO, ID_NINO, CD_ACTIVIDAD, TURNO, CONOCIDO) 
                                        VALUES (:usuario, :nino, :actividad, :turno, :conocido)');
      $query->execute([
        'actividad' => $this->actividad,
        'turno' => $this->turno,
        'usuario' => $this->usuario,
        'nino' => $this->nino,
        'conocido' => $this->conocido
      ]);

      $usermodel = new UserModel();
      $user = $usermodel->get($this->getUsuario());
      $email = $user->getEmail();

      $this->mandarCorreoUsuario('0', $email);
      return true;
    } catch (PDOException $e) {
      error_log('ACTIVIDADES::INSCRIPCION->PDOException ' . $e);
      return false;
    }
  }

  public function CambiarEstado()
  {
    try {
      $query = $this->prepare('UPDATE reservas SET ESTADO = :estado, PRECIO = :precio WHERE ID_RESERVA = :id');
      $query->execute([
        'estado' => $this->estado,
        'id' => $this->id,
        'precio' => ($this->precio = ''? null : $this->precio) ?? 0
      ]);

      $query2 = $this->prepare('SELECT EMAIL FROM usuarios U JOIN reservas R ON R.ID_USUARIO = U.ID_USUARIO WHERE ID_RESERVA = :id');
      $query2->execute([
        'id' => $this->id,
      ]);

      $email = $query2->fetch(PDO::FETCH_ASSOC)['EMAIL'];

      $this->mandarCorreoUsuario($this->estado, $email, $this->id);
      return true;
    } catch (PDOException $e) {
      error_log('ACTIVIDADES::VALIDAR->PDOException ' . $e);
      return false;
    }
  }

  public function ocuparPlaza($actividad, $turno)
  {
    try {
      $query = $this->prepare('UPDATE plazas_act 
      SET PLAZAS_OCUP = (SELECT PLAZAS_OCUP FROM plazas_act where CD_ACTIVIDAD = :actividad and CD_TURNO = :turno) + 1 
      where CD_ACTIVIDAD = :actividad2 and CD_TURNO = :turno2');
      $query->execute([
        'actividad' => $actividad,
        'turno' => $turno,
        'actividad2' => $actividad,
        'turno2' => $turno
      ]);
      return true;
    } catch (PDOException $e) {
      error_log('RESERVAS::OCUPARPLAZA->PDOException ' . $e);
      return false;
    }
  }
  public function desocuparPlaza($actividad, $turno)
  {
    try {
      $query = $this->prepare('UPDATE plazas_act 
      SET PLAZAS_OCUP = (SELECT PLAZAS_OCUP FROM plazas_act where CD_ACTIVIDAD = :actividad and CD_TURNO = :turno) - 1 
      where CD_ACTIVIDAD = :actividad2 and CD_TURNO = :turno2');
      $query->execute([
        'actividad' => $actividad,
        'turno' => $turno,
        'actividad2' => $actividad,
        'turno2' => $turno
      ]);
      return true;
    } catch (PDOException $e) {
      error_log('RESERVAS::OCUPARPLAZA->PDOException ' . $e);
      return false;
    }
  }

  public function mandarCorreoUsuario(string $estado, string $email, string $reserva = null)
  {
    $asunto = "";
    $message = "";
    $header = "From: " . emailHost . "\r\n";
    $header .= "Reply-To: " . emailHost . "\r\n";
    $header .= "X-Mailer: PHP/" . phpversion();
    $header .= "Mime-Version: 1.0\r\n";
    $header .= "Content-Type: text/html; charset=utf-8\r\n";
    $baseUrl = substr(baseUrl, 0 ,-1);
    switch ($estado) {
      case '0': //no validado
        $asunto = "Reserva solicitada";
        $message = <<<MESSAGE
                      ¡Muchas gracias por haber reservado una actividad con Aventurama!<br>
                      Ahora tenemos que revisarla y una vez comprobemos que está todo correcto, te avisaremos por correo para que puedas proceder al pago de la misma.  
                      MESSAGE;
        // send the email
        break;
      case '1': //validado
        $asunto = "Reserva validada";
        $message = <<<MESSAGE
                      Tu reserva ha sido validada y para que quede confirmada debes proceder al pago de la misma en el plazo establecido.<br>
                      Para ello, puedes pulsar en el siguiente enlace que te llevará a la pasarela de pago: <br><br><br>
                      $baseUrl/perfil/pagarReserva/idReserva=$reserva
                      <br><br><br>
                      Si el enlace no está disponible, por favor, dirijase al portal web, acceda a su perfil y pulse en el botón "Pagar reserva".<br><br>
                      MESSAGE;
        break;
      case '2': //pagado
        $asunto = "Reserva pagada";
        //aqui se enviaria un enlace para descargar la factura
        $message = <<<MESSAGE
                      Hemos recibido el pago de la actividad y por tanto está confirmada.<br>
                      ¡Solo queda esperar a que empiece! ¡Muchas gracias!
                      MESSAGE;
        break;
      case '3': //cancelado
        $asunto = "Reserva cancelada";
        $message = <<<MESSAGE
                      Sentimos comunicarte que tu reserva se ha cancelado.<br>
                      Esperemos poder volver a contar contigo en alguna otra actividad de Aventurama.
                      MESSAGE;
        break;
      case '4': //reembolsado 
        $asunto = "Reserva reembolsada";
        $message = <<<MESSAGE
                      Hemos procedido a reembolsar el pago de tu actividad.<br>
                      Esperamos poder volver a contar contigo en alguna otra actividad de Aventurama
                      MESSAGE;
        break;
      case '5': //finalizado 
        $asunto = "Actividad finalizada";
        $message = <<<MESSAGE
                        Y tenemos que decir adiós.... pero mejor es un hasta luego, porque esperamos volver a veros el año que viene.<br>
                      ¡Ha sido un placer! ¡Muchas gracias por contar con nosotros!
                      MESSAGE;
        break;
      case '6': //en curso 
        $asunto = "Actividad en curso";
        $message = <<<MESSAGE
                    ¡Comenzamos! Esperamos que tengas todo preparado para lo que se viene en los próximas días así que te esperamos en breve para disfrutar a tope.
                    MESSAGE;
        break;
      default:
        $asunto = "Informacion de reserva";
        $message = <<<MESSAGE
            Este email no deberia verlo, si tiene alguna reserva pendiente, por favor, contacte con nosotros. <br>
            Ha ocurrido un error con el servicio de correos, disculpe las molestias.
          MESSAGE;
        break;
    }
    return mail($email, $asunto, $message, $header);
  }

  function setLimiteCancelacion(int $dias)
  {
    $fechalimite = date('Y-m-d', strtotime('+' . $dias . ' days'));
    try {
      $query = $this->prepare('UPDATE reservas SET FH_LIMITE = :fecha WHERE ID_RESERVA = :id');
      $query->execute([
        'id' => $this->id,
        'fecha' => $fechalimite
      ]);
      return true;
    } catch (PDOException $e) {
      error_log('ACTIVIDADES::VALIDAR->PDOException ' . $e);
      return false;
    }
  }

  //getters y setters
  public function getId()
  {
    return $this->id;
  }
  public function getUsuario()
  {
    return $this->usuario;
  }
  public function getNino()
  {
    return $this->nino;
  }
  public function getActividad()
  {
    return $this->actividad;
  }
  public function getTurno()
  {
    return $this->turno;
  }
  public function getConocido()
  {
    return $this->conocido;
  }
  public function getFecha()
  {
    return $this->fecha;
  }
  public function getEstado()
  {
    return $this->estado;
  }
  public function getPrecio()
  {
    return $this->precio;
  }

  public function setId($id)
  {
    $this->id = $id;
  }
  public function setUsuario($usuario)
  {
    $this->usuario = $usuario;
  }
  public function setNino($nino)
  {
    $this->nino = $nino;
  }
  public function setActividad($actividad)
  {
    $this->actividad = $actividad;
  }
  public function setTurno($turno)
  {
    $this->turno = $turno;
  }
  public function setConocido($conocido)
  {
    $this->conocido = $conocido;
  }
  public function setFecha($fecha)
  {
    $this->fecha = $fecha;
  }
  public function setEstado($estado)
  {
    $this->estado = $estado;
  }
  public function setPrecio($precio)
  {
    $this->precio = $precio;
  }
}
