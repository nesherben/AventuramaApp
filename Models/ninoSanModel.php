<?php

class NinoSanModel extends Model{

  public $alergiasMedicas;
  public $alergiasAlimentarias;
  public $lesion;
  public $medicacion;
  public $motivosMedicacion;
  public $discapacidad;
  public $reaccionesAlergicas;
  public $vacunado;
  public $tetanos;
  public $nadar;
  public $aficiones;
  public $observacionesMed;

  function __construct(){
    parent::__construct();
    $this->alergiasMedicas = "";
    $this->alergiasAlimentarias = "";
    $this->lesion = "";
    $this->medicacion = "";
    $this->motivosMedicacion = "";
    $this->discapacidad = "";
    $this->reaccionesAlergicas = "";
    $this->vacunado = "";
    $this->tetanos = "";
    $this->nadar = "";
    $this->aficiones = "";
    $this->observacionesMed = "";
  }

  public function registrarInfoNino(string $id){
    try{
      $query = $this->prepare('INSERT INTO info_sanitaria (id_nino, alergia_Med, alergia_Ali, lesion, med_actual, motivo_Med, discapacidad, reac_Alergica, vacunado, antitetanica, natacion, aficiones, observaciones) VALUES (:id, :alergiasMedicas, :alergiasAlimentarias, :lesion, :medicacion, :motivosMedicacion, :discapacidad, :reaccionesAlergicas, :vacunado, :tetanos, :nadar, :aficiones, :observacionesMed)');
    $query->execute([
      'id' => $id,
      'alergiasMedicas' => $this->alergiasMedicas,
      'alergiasAlimentarias' => $this->alergiasAlimentarias,
      'lesion' => $this->lesion,
      'medicacion' => $this->medicacion,
      'motivosMedicacion' => $this->motivosMedicacion,
      'discapacidad' => $this->discapacidad,
      'reaccionesAlergicas' => $this->reaccionesAlergicas,
      'vacunado' => $this->vacunado,
      'tetanos' => $this->tetanos,
      'nadar' => $this->nadar,
      'aficiones' => $this->aficiones,
      'observacionesMed' => $this->observacionesMed
    ]);
    return true;
  }  
  catch(PDOException $e){
    error_log('NINO::REGISTRARINFONINO->PDOException ' . $e);
    return false;
  }
}

public function editarInfoNino(string $id){
  try{
    $query = $this->prepare('UPDATE info_sanitaria SET alergia_Med = :alergiasMedicas, alergia_Ali = :alergiasAlimentarias, lesion = :lesion, med_actual = :medicacion, motivo_Med = :motivosMedicacion, discapacidad = :discapacidad, reac_Alergica = :reaccionesAlergicas, vacunado = :vacunado, antitetanica = :tetanos, natacion = :nadar, aficiones = :aficiones, observaciones = :observacionesMed WHERE id_nino = :id');
    $query->execute([
      'id' => $id,
      'alergiasMedicas' => $this->alergiasMedicas,
      'alergiasAlimentarias' => $this->alergiasAlimentarias,
      'lesion' => $this->lesion,
      'medicacion' => $this->medicacion,
      'motivosMedicacion' => $this->motivosMedicacion,
      'discapacidad' => $this->discapacidad,
      'reaccionesAlergicas' => $this->reaccionesAlergicas,
      'vacunado' => $this->vacunado,
      'tetanos' => $this->tetanos,
      'nadar' => $this->nadar,
      'aficiones' => $this->aficiones,
      'observacionesMed' => $this->observacionesMed
    ]);
    return true;
  }
  catch(PDOException $e){
    error_log('NINO::EDITARINFONINO->PDOException ' . $e);
    return false;
  }
}

public function getInfoNino(string $id){
  try{
    $query = $this->prepare('SELECT * FROM info_sanitaria WHERE id_nino = :id');
    $query->execute(['id' => $id]);
    $nino = $query->fetch(PDO::FETCH_ASSOC);
    // $this->setNumTarjeta($nino['TARJETA_SANITARIA']);
    // $this->setAlergiasMedicas($nino['ALERGIA_MED']);
    // $this->setAlergiasAlimentarias($nino['ALERGIA_ALI']);
    // $this->setLesion($nino['LESION']);
    // $this->setMedicacion($nino['MED_ACTUAL']);
    // $this->setMotivosMedicacion($nino['MOTIVO_MED']);
    // $this->setDiscapacidad($nino['DISCAPACIDAD']);
    // $this->setReaccionesAlergicas($nino['REAC_ALERGICA']);
    // $this->setVacunado($nino['VACUNADO']);
    // $this->setTetanos($nino['ANTITETANICA']);
    // $this->setNadar($nino['NATACION']);
    // $this->setAficiones($nino['AFICIONES']);
    // $this->setObservacionesMed($nino['OBSERVACIONES']);
    
    // return $this;
    return $nino;
  }
  catch(PDOException $e){
    error_log('NINO::GETINFONINO->PDOException ' . $e);
    return false;
  }
}
function guardarTarjeta(string $id, $tarjeta){
  try{
    $query = $this->prepare('DELETE FROM tarjetas_sanitarias WHERE ID_NINO = :id');
    $query->execute([
      'id' => $id
    ]);

    $query = $this->prepare('INSERT into tarjetas_sanitarias (ID_NINO, TARJETA, NM_ARCHIVO, TIPO_ARCHIVO, SIZE_ARCHIVO) VALUES (:id, :tarjeta, :nombre, :tipo, :size)');
    $query->execute([
      'id' => $id,
      'tarjeta' => addslashes(file_get_contents($tarjeta['tmp_name'])),
      'nombre' => $tarjeta['name'],
      'tipo' => $tarjeta['type'],
      'size' => $tarjeta['size']
    ]);
    return true;
  }catch(PDOException $e){
    error_log('NINO::guardarTS->PDOException ' . $e);
    return false;
  }
}

//getters y setters

public function getAlergiasMedicas(){  return $this->alergiasMedicas; }
public function getAlergiasAlimentarias(){  return $this->alergiasAlimentarias; }
public function getLesion(){  return $this->lesion; }
public function getMedicacion(){  return $this->medicacion; }
public function getMotivosMedicacion(){  return $this->motivosMedicacion; }
public function getDiscapacidad(){  return $this->discapacidad; }
public function getReaccionesAlergicas(){  return $this->reaccionesAlergicas; }
public function getVacunado(){  return $this->vacunado; }
public function getTetanos(){  return $this->tetanos; }
public function getNadar(){  return $this->nadar; }
public function getAficiones(){  return $this->aficiones; }
public function getObservacionesMed(){  return $this->observacionesMed; }


public function setAlergiasMedicas(string $alergiasMedicas){ $this->alergiasMedicas = $alergiasMedicas; }
public function setAlergiasAlimentarias(string $alergiasAlimentarias){ $this->alergiasAlimentarias = $alergiasAlimentarias; }
public function setLesion(string $lesion){ $this->lesion = $lesion; }
public function setMedicacion(string $medicacion){ $this->medicacion = $medicacion; }
public function setMotivosMedicacion(string $motivosMedicacion){ $this->motivosMedicacion = $motivosMedicacion; }
public function setDiscapacidad(string $discapacidad){ $this->discapacidad = $discapacidad; }
public function setReaccionesAlergicas(string $reaccionesAlergicas){ $this->reaccionesAlergicas = $reaccionesAlergicas; }
public function setVacunado(string $vacunado){ $this->vacunado = $vacunado; }
public function setTetanos(string $tetanos){ $this->tetanos = $tetanos; }
public function setNadar(string $nadar){ $this->nadar = $nadar; }
public function setAficiones(string $aficiones){ $this->aficiones = $aficiones; }
public function setObservacionesMed(string $observacionesMed){ $this->observacionesMed = $observacionesMed; }


}
?>