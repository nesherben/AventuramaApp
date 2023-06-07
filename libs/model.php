<?php

class Model{
    public $db;

    function __construct()
    {
        $this->db = new Db();
    }

    function query($query){
        return $this->db->connect()->query($query);
    }

    function prepare($query){
        return $this->db->connect()->prepare($query);
    }

     public function Tpost($tabla, $data){
         $consulta = "insert into ".$tabla." values(null,".$data.")";
         $resultado =$this->query($consulta);
         if($resultado){
             return true;
         }
         else{
             return false;
         }
     }
     public function Tget($tabla,$condicion){
         $elems = array();
         $consulta="select * from ".$tabla." where ".$condicion.";";
             $resultado=$this->query($consulta);
             while($filas = $resultado->FETCHALL(PDO::FETCH_ASSOC)) {
                 $elems[]=$filas;
             }
             return $elems;
         } 
     public function Tupdate($tabla, $data, $condicion){       
         $consulta="update ".$tabla." set ". $data ." where ".$condicion;
         $resultado=$this->query($consulta);
         if ($resultado) {
             return true;
         }else {
             return false;
         }
      }
     public function Tdelete($tabla, $condicion){
         $eliminar="delete from ".$tabla." where ".$condicion;
         $resultado=$this->query($eliminar);
         if ($resultado) {
             return true; 
         }else {
             return false;
         }
     }
}

?>