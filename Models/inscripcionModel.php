<?php 

class InscripcionModel extends Model{
    public $codigo;
    public $nombre;
    public $descripcion;
    public $categoria;
    public $turnos;

    function __construct()
    {         
        parent::__construct();       
        
        $this->codigo = "";
        $this->nombre = "";
        $this->descripcion = "";
        $this->categoria = "";
        $this->turnos = "";
    }

    public function get($codigo){
        try{
            $query = $this->prepare('SELECT * FROM tipos_act WHERE CD_ACTIVIDAD = :codigo');
            $query->execute(['codigo' => $codigo]);
            $p = $query->fetch(PDO::FETCH_ASSOC);
            $item = new InscripcionModel();
            $item->setCodigo($p['CD_ACTIVIDAD']);
            $item->setNombre($p['NM_ACTIVIDAD']);
            $item->setDescripcion($p['DS_ACTIVIDAD']);
            $item->setCategoria($p['CT_ACTIVIDAD']);
            $item->setTurnos($p['TURNOS']);
            return $item;
        }catch(PDOException $e){
            error_log('Inicio_MODEL::GET->PDOException ' . $e);
            return null;
        }
    }
    public function exist(string $codigo){
        try{
            $query = $this->prepare('SELECT count(*) FROM tipos_act WHERE CD_ACTIVIDAD = :codigo');
            $query->execute(['codigo' => $codigo]);
            $p = $query->fetch(PDO::FETCH_ASSOC);            
            return $p > 0;
        }catch(PDOException $e){
            error_log('Inicio_MODEL::GET->PDOException ' . $e);
            return null;
        }
    }
    public function getAllTurnos(string $codigo){
        try{
            $query = $this->prepare('SELECT * FROM plazas_act pa join turnos_act tu on tu.CD_TURNO = pa.CD_TURNO  where pa.CD_ACTIVIDAD = :actividad and (pa.NUM_PLAZAS > pa.PLAZAS_OCUP or tu.CD_TURNO = "TU")');
            $query->execute(['actividad' => $codigo]);
            $turnos = $query->fetchAll(PDO::FETCH_ASSOC);
            return $turnos;
          }  
          catch(PDOException $e){
            error_log('ACTIVIDADES::GETALLTURNOS->PDOException ' . $e);
            return false;
          }
    }    
   

    //getters y setters
    public function getCodigo(){        return $this->codigo;}
    public function getNombre(){        return $this->nombre;}
    public function getDescripcion(){   return $this->descripcion;}
    public function getCategoria(){     return $this->categoria;}
    public function getTurnos(){        return $this->turnos;}

    public function setCodigo($codigo){             $this->codigo = $codigo;}
    public function setNombre($nombre){             $this->nombre = $nombre;}
    public function setDescripcion($descripcion){   $this->descripcion = $descripcion;}
    public function setCategoria($categoria){       $this->categoria = $categoria;}
    public function setTurnos($turnos){             $this->turnos = $turnos;}
}

?>
