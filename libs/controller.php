<?php 
class Controller{
    public $model;
    public $view;
    function __construct()
    {
        $this->view = new View();
        $this->model = new Model();
    }

    function loadModel($model){
        $url = 'Models/'.$model.'Model.php';
        if(file_exists($url)){
            require_once $url;
            $modelName = $model.'Model';
            $this->model = new $modelName();
        }
    }

    function existPOST($params){
        foreach($params as $param){
            if(!isset($_POST[$param])){
                error_log("Controller::existPOST -> No existe el parametro: ".$param);
                return false;
            }
        }
        return true;
    }

    function existGET($params){
        foreach($params as $param){
            if(!isset($_GET[$param])){
                error_log("Controller::existGET -> No existe el parametro: ".$param);
                return false;
            }
        }
        return true;
    }

    function redirect($route, $mensajes){
        $data = [];
        $params = '';
        foreach($mensajes as $key => $mensaje){
            array_push($data, $key . "=" . $mensaje);
        }
        $params = join("&", $data);
        if($params != ''){
            $params = "?" . $params;
        }
        header("Location: " ."/". $route . $params);
    }
}

?>