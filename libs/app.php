<?php

class App{
   public function __construct(){  
        $url = isset($_GET['url']) ? $_GET['url'] : '';
        $url = rtrim($url, '/');
        $url = explode('/', $url);
        //error_log("App::CONSTRUCT -> url: ".$url);
        if(empty($url[0])){            
            $file_controller = 'Controllers/login.php';
            require_once($file_controller);
            $controller = new Login();
            $controller->loadModel('login');
            $controller->render();
            return; //antes retornaba false
        }

        $file_controller = 'Controllers/'.$url[0].'.php';
        if(file_exists($file_controller)){
            require_once $file_controller;
            $controller = new $url[0];
            $controller->loadModel($url[0]);
            error_log("App::CONSTRUCT -> url 0: ".$url[0]."");
            if(isset($url[1])){
                error_log("App::CONSTRUCT -> url 1: ".$url[1]."");
                if(method_exists($controller, $url[1])){
                    if(isset($url[2])){
                        error_log("App::CONSTRUCT -> url 2: ".$url[2]);
                        $params = explode('~', $url[2]);
                        $nparams = count($params);
                        for($i = 0; $i < $nparams; $i++){
                            $param = explode('=', $params[$i]);
                            $params[$param[0]] = $param[1];
                            error_log("App::CONSTRUCT -> param: ".$param[0]);
                            error_log("App::CONSTRUCT -> value: ".$params[$param[0]]);
                        }
                        $controller->{$url[1]}($params);
                    }else{
                        $controller->{$url[1]}();
                    }
                } else {
                    $controller = new ErrorController();
                    //$controller->redirect('', ['error' => 'No existe el metodo '.$url[1].' en el controller '.$url[0].'Controller.php']);
                }
            }else{
                //cargo la vista si solo llamo al controller
                $controller->render();
            }
        }else{
            $controller = new ErrorController();
        }
    }
}
