<?php 

class ErrorMessages{
    const GENERIC_ERROR = "e1";
    private $errorList = [];

    public function __construct()
    {
        $this->errorList = [
            ErrorMessages::GENERIC_ERROR=> "No se ha realizado la operación, Error desconocido",
        ];
    }

    public function get($key){
        return $this->errorList[$key];
    }

    public function existsKey($key){
        return array_key_exists($key, $this->errorList);
    }
}


?>