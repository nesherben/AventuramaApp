<?php 

class InicioModel extends Model{
    public $codigo;
    public $nombre;
    public $descripcion;
    public $categoria;
    public $imagen;
    function __construct()
    {         
        parent::__construct();       
        
        $this->codigo = "";
        $this->nombre = "";
        $this->descripcion = "";
        $this->categoria = "";
        $this->imagen = "";
    }

    ////////////////////////////////////////
    //    Funciones
    ///////////////////////////////////////

    public function getAll(){
        try{
            $items = [];
            $query = $this->query('SELECT * FROM tipos_act JOIN categorias_act ON tipos_act.CT_ACTIVIDAD = categorias_act.CD_CATEGORIA where DESACTIVADO = 0 order by CT_ACTIVIDAD desc');
            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new InicioModel();
                $item->setCodigo($p['CD_ACTIVIDAD']);
                $item->setNombre($p['NM_ACTIVIDAD']);
                $item->setDescripcion($p['DS_ACTIVIDAD']);
                $item->setCategoria($p['DS_CATEGORIA']);
                $item->setImagen($p['URL_IMAGEN']);
                array_push($items, $item);
            }
            return $items;
        }catch(PDOException $e){
            error_log('Inicio_MODEL::GET_ALL->PDOException ' . $e);
            return [];
        }
    }

    public function get($codigo){
        try{
            $query = $this->prepare('SELECT * FROM tipos_act WHERE CD_ACTIVIDAD = :codigo');
            $query->execute(['codigo' => $codigo]);
            $p = $query->fetch(PDO::FETCH_ASSOC);
            $item = new InicioModel();
            $item->setCodigo($p['CD_ACTIVIDAD']);
            $item->setNombre($p['NM_ACTIVIDAD']);
            $item->setDescripcion($p['DS_ACTIVIDAD']);
            $item->setCategoria($p['CT_ACTIVIDAD']);
            return $item;
        }catch(PDOException $e){
            error_log('Inicio_MODEL::GET->PDOException ' . $e);
            return null;
        }
    }


    //getters y setters
    public function getCodigo(){        return $this->codigo;}
    public function getNombre(){        return $this->nombre;}
    public function getDescripcion(){   return $this->descripcion;}
    public function getCategoria(){     return $this->categoria;}
    public function getImagen(){        return $this->imagen;}

    public function setCodigo($codigo){             $this->codigo = $codigo;}
    public function setNombre($nombre){             $this->nombre = $nombre;}
    public function setDescripcion($descripcion){   $this->descripcion = $descripcion;}
    public function setCategoria($categoria){       $this->categoria = $categoria;}
    public function setImagen($imagen){             $this->imagen = $imagen;}
}


?>