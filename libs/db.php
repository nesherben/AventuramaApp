<?php
class Db{
    private $host;
    private $user;
    private $pass;
    private $db;
    private $charset;

    public function __construct()
    {
        $this -> host = host;
        $this -> user = user;
        $this -> pass = pass;
        $this -> db = db;
        $this -> charset = charset;
    }

    function connect(){
        try{
            $connection = "mysql:host=".$this->host.";dbname=".$this->db.";charset=".$this->charset;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $pdo = new PDO($connection, $this->user, $this->pass, $options);
            error_log('Connection success');
            return $pdo;
        }catch(PDOException $e){
            print_r('Error connection: ' . $e->getMessage());
        }
    }
    
} 

?>