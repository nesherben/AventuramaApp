<?php

class ActividadesModel extends Model
{

  public $codigo;
  public $nombre;
  public $descripcion;
  public $turnos;
  public $categoria;
  public $desactivado;
  public $imagen;

  function __construct()
  {
    parent::__construct();
    $this->codigo = "";
    $this->nombre = "";
    $this->descripcion = "";
    $this->turnos = "";
    $this->categoria = "";
    $this->desactivado = 0;
    $this->imagen = "";
  }


  public function registrarCategoria($codigo, $nombre){
    try{
      $query = $this->prepare('INSERT INTO categorias_act (CD_CATEGORIA, DS_CATEGORIA) VALUES (:codigo, :nombre)');
      $query->execute([
        'codigo' => $codigo,
        'nombre' => $nombre
      ]);
      return true;

    }catch(PDOException $e){
      
    }
  }

  public function borrarActividad($codigo){
    try{
      $query = $this->prepare('DELETE FROM tipos_act WHERE CD_ACTIVIDAD = :codigo and DESACTIVADO = 1 and CD_ACTIVIDAD not in (SELECT CD_ACTIVIDAD FROM reservas where estado = 6 or estado = 2 or estado = 1)');
      $query->execute([
        'codigo' => $codigo
      ]);
      return true;
    }catch(PDOException $e){
      error_log('ACTIVIDADES::BORRARACTIVIDAD->PDOException ' . $e);
      return false;
    }
  }

  public function insertarActividad()
  {
    try {
      $query = $this->prepare('INSERT INTO tipos_act (CD_ACTIVIDAD, NM_ACTIVIDAD, DS_ACTIVIDAD,CT_ACTIVIDAD,URL_IMAGEN,TURNOS) VALUES (:codigo, :nombre, :descripcion, :categoria,:imagen ,:turnos)');
      $query->execute([
        'codigo' => $this->codigo,
        'nombre' => $this->nombre,
        'descripcion' => $this->descripcion,
        'turnos' => $this->turnos,
        'imagen' => $this->imagen,
        'categoria' => $this->categoria,

      ]);

      $query = $this->prepare('DELETE FROM plazas_act WHERE CD_ACTIVIDAD = :codigo');
      $query->execute([
        'codigo' => $this->codigo
      ]);

      foreach (explode(',', $this->turnos) as $turno) :
        $query = $this->prepare('INSERT INTO plazas_act (CD_ACTIVIDAD, CD_TURNO, NUM_PLAZAS) VALUES (:codigo, :turnos, 0)');
        $query->execute([
          'codigo' => $this->codigo,
          'turnos' => $turno,
        ]);
      endforeach;

      return true;
    } catch (PDOException $e) {
      error_log('ACTIVIDADES::INSERTARACTIVIDAD->PDOException ' . $e);
      return false;
    }
  }

  public function activarDesactivar(string $codigo, int $desactivado)
  {
    try {
      $query = $this->prepare('UPDATE tipos_act SET DESACTIVADO = :desactivado WHERE CD_ACTIVIDAD = :codigo');
      $query->execute([
        'codigo' => $codigo,
        'desactivado' => $desactivado == 0 ? 1 : 0
      ]);

      $desactivado == 1 ? $this->reajustarActividad($codigo) : null;
      return true;
    } catch (PDOException $e) {
      error_log('ACTIVIDADES::DESACTIVARACTIVIDAD->PDOException ' . $e);
      return false;
    }
  }

  function reajustarActividad(string $codigo)
  {
    try {
      $query = $this->prepare('UPDATE plazas_act SET PLAZAS_OCUP = 0 WHERE CD_ACTIVIDAD = :codigo');
      $query->execute([
        'codigo' => $codigo
      ]);

      $query = $this->prepare('UPDATE reservas SET estado = 7 WHERE CD_ACTIVIDAD = :codigo and estado = 5');
      $query->execute([
        'codigo' => $codigo
      ]);

      return true;
    } catch (PDOException $e) {
      error_log('ACTIVIDADES::REAJUSTARACTIVIDAD->PDOException ' . $e);
      return false;
    }
  }

  public function editarActividad(string $codigo)
  {
    try {
      $query = $this->prepare('UPDATE tipos_act SET NM_ACTIVIDAD = :nombre, DS_ACTIVIDAD = :descripcion, CT_ACTIVIDAD = :categoria, CD_ACTIVIDAD = upper(:codigo) , URL_IMAGEN = :imagen WHERE CD_ACTIVIDAD = :codigoA');
      $query->execute([
        'codigoA' => $codigo,
        'codigo' => $this->codigo,
        'nombre' => $this->nombre,
        'descripcion' => $this->descripcion,
        'imagen' => $this->imagen,
        'categoria' => $this->categoria
      ]);

      return true;
    } catch (PDOException $e) {
      error_log('ACTIVIDADES::EDITARACTIVIDAD->PDOException ' . $e);
      return false;
    }
  }

  public function setPlazaTurno(string $codigo, string $turno, int $plazas)
  {
    try {
      $query = $this->prepare('UPDATE plazas_act SET NUM_PLAZAS = :plazas WHERE CD_ACTIVIDAD = :codigo AND CD_TURNO = :turno');
      $query->execute([
        'codigo' => $codigo,
        'turno' => $turno,
        'plazas' => $plazas
      ]);
      return true;
    } catch (PDOException $e) {
      error_log('ACTIVIDADES::SETPLAZATURNO->PDOException ' . $e);
      return false;
    }
  }

  public function getTurnosPlazas(string $actividad)
  {
    try {
      $query = $this->prepare('SELECT CD_ACTIVIDAD, concat(ta.CD_TURNO, " - " , ta.DS_TURNO ) as TURNO, NUM_PLAZAS, PLAZAS_OCUP  FROM plazas_act pa join turnos_act ta on ta.CD_TURNO = pa.CD_TURNO WHERE pa.CD_ACTIVIDAD = :actividad');
      $query->execute([
        'actividad' => $actividad
      ]);
      $turnos = $query->fetchAll(PDO::FETCH_ASSOC);
      return $turnos;
    } catch (PDOException $e) {
      error_log('ACTIVIDADES::GETTURNOSPLAZA->PDOException ' . $e);
      return false;
    }
  }

  public function getActividad(string $codigo)
  {
    try {
      $query = $this->prepare('SELECT * FROM tipos_act WHERE CD_ACTIVIDAD = :codigo');
      $query->execute([
        'codigo' => $codigo
      ]);
      $actividad = $query->fetch(PDO::FETCH_ASSOC);
      return $actividad;
    } catch (PDOException $e) {
      error_log('ACTIVIDADES::GETACTIVIDAD->PDOException ' . $e);
      return false;
    }
  }

  public function getActividades()
  {
    try {
      $query = $this->prepare('SELECT distinct
                              ta.CD_ACTIVIDAD as CD_ACTIVIDAD,
                              ta.NM_ACTIVIDAD as NM_ACTIVIDAD,
                              ta.DS_ACTIVIDAD as DS_ACTIVIDAD,
                              ta.CT_ACTIVIDAD as CT_ACTIVIDAD,
                              ta.DESACTIVADO as DESACTIVADO,
                              ta.URL_IMAGEN as URL_IMAGEN,
                              ta.TURNOS as TURNOS,
                              ca.DS_CATEGORIA as DS_CATEGORIA
                               FROM tipos_act ta 
                              join categorias_act ca on ca.CD_CATEGORIA = ta.CT_ACTIVIDAD 
                             order by ta.CD_ACTIVIDAD desc');
      $query->execute();
      $actividades = $query->fetchAll(PDO::FETCH_ASSOC);
      return $actividades;
    } catch (PDOException $e) {
      error_log('ACTIVIDADES::GETACTIVIDADES->PDOException ' . $e);
      return false;
    }
  }

  public function getAllTurnos()
  {
    try {
      $query = $this->prepare('SELECT * FROM turnos_act');
      $query->execute();
      $turnos = $query->fetchAll(PDO::FETCH_ASSOC);
      return $turnos;
    } catch (PDOException $e) {
      error_log('ACTIVIDADES::GETALLTURNOS->PDOException ' . $e);
      return false;
    }
  }

  public function getEstadosPorActividad(string $codigo)
  {
    try {
      $query = $this->prepare('SELECT distinct re.CD_ACTIVIDAD,ESTADO,DS_ESTADO, TURNO,DS_TURNO FROM reservas re
    left join turnos_act ta on ta.CD_TURNO = re.TURNO
    join tipos_act ti on ti.CD_ACTIVIDAD = re.CD_ACTIVIDAD
    join estados_reserva ea on ea.CD_ESTADO = re.ESTADO    
    where re.CD_ACTIVIDAD = :actividad 
    and (re.ESTADO = "5" or re.ESTADO = "6" or re.ESTADO = "2")');
      $query->execute([
        'actividad' => $codigo,
      ]);
      $estados = $query->fetchAll(PDO::FETCH_ASSOC);
      return $estados;
    } catch (PDOException $e) {
      error_log('ACTIVIDADES::GETESTADOSPORCAMPAMENTO->PDOException ' . $e);
      return false;
    }
  }
  public function setEstadosPorCampamento(string $codigo, string $turno)
  {
    try {
      $query = $this->prepare('UPDATE reservas set
                              ESTADO = case ESTADO
                              when 2 then 6
                              when 6 then 5
                              else ESTADO
                              end
                              where CD_ACTIVIDAD = :actividad
                              and TURNO = :turno
                          ');
      $query->execute([
        'actividad' => $codigo,
        'turno' => $turno
      ]);

      //SOLO DEBERIA SACAR LOS 2 ESTADOS QUE SE HAN CAMBIADO
      $query2 = $this->prepare('SELECT U.EMAIL, R.ESTADO FROM USUARIOS U JOIN reservas R ON R.ID_USUARIO = U.ID_USUARIO WHERE CD_ACTIVIDAD = :id and turno = :turno
      and R.ESTADO = 5 or R.ESTADO = 6');
      $query2->execute([
        'id' => $codigo,
        'turno' => $turno
      ]);
      $info = $query2->fetchAll(PDO::FETCH_ASSOC);

      $reservaModel = new ReservasModel();
      //mandar un email a todos los usuarios que tengan una reserva en ese campamento y el estado cambie
      //(como esto cambia el estado de forma global, se manda el email a todos los usuarios)
      foreach ($info as $i) {
        $reservaModel->mandarCorreoUsuario($i["ESTADO"], $i["EMAIL"]);
      }
      return true;
    } catch (PDOException $e) {
      error_log('ACTIVIDADES::GETESTADOSPORCAMPAMENTO->PDOException ' . $e);
      return false;
    }
  }

  public function getAllCategorias()
  {
    try {
      $query = $this->prepare('SELECT * FROM categorias_act');
      $query->execute();
      $categorias = $query->fetchAll(PDO::FETCH_ASSOC);
      return $categorias;
    } catch (PDOException $e) {
      error_log('ACTIVIDADES::GETALLCATEGORIAS->PDOException ' . $e);
      return false;
    }
  }

  public function eliminarActividad(string $codigo)
  {
    try {
      $query = $this->prepare('DELETE FROM tipos_act WHERE codigo = :codigo');
      $query->execute([
        'codigo' => $codigo
      ]);
      return true;
    } catch (PDOException $e) {
      error_log('ACTIVIDADES::ELIMINARACTIVIDAD->PDOException ' . $e);
      return false;
    }
  }
  //getters y setters

  function setCodigo(string $codigo)
  {
    $this->codigo = $codigo;
  }
  function setNombre(string $nombre)
  {
    $this->nombre = $nombre;
  }
  function setDescripcion(string $descripcion)
  {
    $this->descripcion = $descripcion;
  }
  function setTurnos(array $turnos)
  {
    $misturnos = '';
    foreach ($turnos as $key => $turno) {
      $misturnos = $misturnos . $turno . ',';
    }
    $this->turnos = substr($misturnos, 0, strlen($misturnos) - 1);
  }
  function setCategoria(string $categoria)
  {
    $this->categoria = $categoria;
  }
  function setDesactivado(int $desactivado)
  {
    $this->desactivado = $desactivado;
  }
  function setImagen(string $imagen)
  {
    $this->imagen = $imagen;
  }

  function getCodigo()
  {
    return $this->codigo;
  }
  function getNombre()
  {
    return $this->nombre;
  }
  function getDescripcion()
  {
    return $this->descripcion;
  }
  function getTurnos()
  {
    return $this->turnos;
  }
  function getCategoria()
  {
    return $this->categoria;
  }
  function getDesactivado()
  {
    return $this->desactivado;
  }
  function getImagen()
  {
    return $this->imagen;
  }
}
