<?php 

class SuccessMessages{

    const GENERIC_SUCCESS = 's1';
    private $messageList = [];

    public function __construct()
    {
        $this->messageList = [
            SuccessMessages::GENERIC_SUCCESS => 'Operación realizada con éxito',
        ];
    }

    public function get($key){
        return $this->messageList[$key];
    }

    public function existsKey($key){
        return array_key_exists($key, $this->messageList);
    }
}


?>