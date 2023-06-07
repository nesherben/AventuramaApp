<?php

class NinoModel extends Model
{

  public $nombre;
  public $apellidos;
  public $fechaNacimiento;
  public $dni;
  public $centroEstudios;
  public $observaciones;
  public $id;
  public $infoSanitaria;
  public $tieneDNI;
  public $tieneTS;

  function __construct()
  {
    parent::__construct();

    $this->nombre = "";
    $this->apellidos = "";
    $this->fechaNacimiento = "";
    $this->dni = "";
    $this->centroEstudios = "";
    $this->observaciones = "";
    $this->id = 0;
    $this->infoSanitaria = array();
    $this->tieneDNI = false;
    $this->tieneTS = false;
  }

  public function registrarNino()
  {
    try {
      $query = $this->prepare('INSERT INTO ninos (nombre, apellidos, fh_nacimiento, dni, centro_estudios, id_padre, observaciones) VALUES (:nombre, :apellidos, :fechaNacimiento, :dni, :centroEstudios,:padre, :observaciones)');
      $query->execute([
        'nombre' => $this->nombre,
        'apellidos' => $this->apellidos,
        'fechaNacimiento' => $this->fechaNacimiento,
        'dni' => $this->dni,
        'centroEstudios' => $this->centroEstudios,
        'padre' => $_SESSION['user'],
        'observaciones' => $this->observaciones
      ]);

      $queryo = $this->prepare('SELECT id_nino FROM ninos WHERE dni = :dni');
      $queryo->execute([
        'dni' => $this->dni
      ]);
      $id = $queryo->fetchColumn();
      error_log($id);
      return $id;
    } catch (PDOException $e) {
      error_log('NINO::REGISTRARNINO->PDOException ' . $e);
      return false;
    }
  }

  public function editarNino(string $id)
  {
    try {
      $query = $this->prepare('UPDATE ninos SET nombre = :nombre, apellidos = :apellidos, fh_nacimiento = :fechaNacimiento, dni = :dni, centro_estudios = :centroEstudios, observaciones = :observaciones WHERE ID_NINO = :id');
      $query->execute([
        'nombre' => $this->nombre,
        'apellidos' => $this->apellidos,
        'fechaNacimiento' => $this->fechaNacimiento,
        'dni' => $this->dni,
        'centroEstudios' => $this->centroEstudios,
        'observaciones' => $this->observaciones,
        'id' => $id
      ]);
      return true;
    } catch (PDOException $e) {
      error_log('NINO::EDITARNINO->PDOException ' . $e);
      return false;
    }
  }
  public function getAll($filtro = null)
  {
    try {
      $query = $this->prepare('SELECT ni.id_nino as ID_NINO, ni.nombre as NOMBREN, ni.apellidos as APELLIDOSN, 
                                ni.fh_nacimiento as FH_NACIMIENTO, ni.dni as DNI, ni.centro_estudios as CENTRO_ESTUDIOS, 
                                ni.observaciones as OBSERVACIONES, pa.nombre as NOMBREP, pa.apellidos as APELLIDOSP,
                                pa.email as EMAIL, pa.id_usuario as ID_PADRE, NM_ACTIVIDAD, DS_TURNO
                                FROM ninos ni 
                                left join reservas re on ni.id_nino = re.id_nino  
                                left join usuarios pa on ni.id_padre = pa.id_usuario 
                                left join tipos_act ac on re.cd_actividad = ac.cd_actividad
                                left join turnos_act tu on re.turno = tu.cd_turno                                                    
                                where re.estado = 6
                                and ( (ni.NOMBRE like :nino or ni.APELLIDOS like :nino2 ) or :nino3 = "")
                                   and ( re.CD_ACTIVIDAD = :actividad or :actividad2 = "")
                                   and ( tu.CD_TURNO = :turno or :turno2 = "")
                              ');
      $query->execute([
        'nino' => '%' . $filtro['nino'] . '%',
        'nino2' => '%' . $filtro['nino'] . '%',
        'nino3' => '%' . $filtro['nino'] . '%',
        'actividad' => $filtro['actividad'],
        'actividad2' => $filtro['actividad'],
        'turno' => $filtro['turno'],
        'turno2' => $filtro['turno']

      ]);
      $ninos = $query->fetchAll(PDO::FETCH_ASSOC);
      return $ninos;
    } catch (PDOException $e) {
      error_log('NINO::getNinos->PDOException ' . $e);
      return false;
    }
  }

  public function getNinos()
  {
    try {
      $query = $this->prepare('SELECT * FROM ninos WHERE ID_PADRE = :id');
      $query->execute([
        'id' => $_SESSION['user']
      ]);
      $ninos = $query->fetchAll(PDO::FETCH_ASSOC);
      return $ninos;
    } catch (PDOException $e) {
      error_log('NINO::getNinos->PDOException ' . $e);
      return false;
    }
  }

  public function getNino(string $id)
  {
    try {
      $query = $this->prepare('SELECT * FROM ninos WHERE ID_NINO = :id');
      $query->execute([
        'id' => $id
      ]);
      $nino = $query->fetch(PDO::FETCH_ASSOC);

      return $nino;
    } catch (PDOException $e) {
      error_log('NINO::getNinos->PDOException ' . $e);
      return false;
    }
  }

  function guardarDNI(string $id, $dni)
  {
    try {
      $query = $this->prepare('DELETE FROM dnis_ninos WHERE ID_NINO = :id');
      $query->execute([
        'id' => $id
      ]);

      $query = $this->prepare('INSERT into dnis_ninos (ID_NINO, DNI, NM_ARCHIVO, TIPO_ARCHIVO, SIZE_ARCHIVO) VALUES (:id, :dni, :nombre, :tipo, :size)');
      $query->execute([
        'id' => $id,
        'dni' => file_get_contents($dni['tmp_name']),
        'nombre' => $dni['name'],
        'tipo' => $dni['type'],
        'size' => $dni['size']
      ]);
      return true;
    } catch (PDOException $e) {
      error_log('NINO::guardarDNI->PDOException ' . $e);
      return false;
    }
  }

  function descargarDNI()
  {
    try {
      $query = $this->prepare('SELECT * FROM dnis_ninos WHERE ID_NINO = :id');
      $query->execute([
        'id' => $this->id
      ]);
      $dniData = $query->fetch(PDO::FETCH_ASSOC);
      return $dniData;
    } catch (PDOException $e) {
      error_log('NINO::descargarDNI->PDOException ' . $e);
      return false;
    }
  }

  function descargarTarjeta()
  {
    try {
      $query = $this->prepare('SELECT * FROM tarjetas_sanitarias WHERE ID_NINO = :id');
      $query->execute([
        'id' => $this->id
      ]);
      $tarjetaData = $query->fetch(PDO::FETCH_ASSOC);
      return $tarjetaData;
    } catch (PDOException $e) {
      error_log('NINO::descargarTS->PDOException ' . $e);
      return false;
    }
  }
  //getters y setters
  public function getNombre()
  {
    return $this->nombre;
  }
  public function getApellidos()
  {
    return $this->apellidos;
  }
  public function getFechaNacimiento()
  {
    return $this->fechaNacimiento;
  }
  public function getDni()
  {
    return $this->dni;
  }
  public function getCentroEstudios()
  {
    return $this->centroEstudios;
  }
  public function getObservaciones()
  {
    return $this->observaciones;
  }
  public function getInfoSanitaria()
  {
    return $this->infoSanitaria;
  }
  public function getId()
  {
    return $this->id;
  }

  public function setNombre($nombre)
  {
    $this->nombre = $nombre;
  }
  public function setApellidos($apellidos)
  {
    $this->apellidos = $apellidos;
  }
  public function setFechaNacimiento($fechaNacimiento)
  {
    $this->fechaNacimiento = $fechaNacimiento;
  }
  public function setDni($dni)
  {
    $this->dni = $dni;
  }
  public function setCentroEstudios($centroEstudios)
  {
    $this->centroEstudios = $centroEstudios;
  }
  public function setObservaciones($observaciones)
  {
    $this->observaciones = $observaciones;
  }
  public function setInfoSanitaria($infoSanitaria)
  {
    $this->infoSanitaria = $infoSanitaria;
  }
  public function setId($id){
    if($id != null){
      $_SESSION['nino'] = $id;
      $this->id = $id;
    }
    else
      $this->id = $_SESSION['nino'];
  }
}
