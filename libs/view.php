<?php

class View{
    private $d;
    function __construct()
    {        
        error_log("View::CONSTRUCT -> Inicio de la clase View");
    }

    function render($name, $data = []){
        $this->d = $data;

        $this-> handleMessages();

        require_once('Views/'.$name.'.php');
    }

    function handleMessages(){
        if(isset($_GET['success']) && isset($_GET['error'])){
            // nunca deberia entrar
        }
        else if(isset($_GET['success'])){
            $this->handleSuccess();
        }
        else if(isset($_GET['error'])){
            $this->handleError();
        }        
    }

    function handleSuccess(){
        $hash = $_GET['success'];
        $success = new SuccessMessages();

        if($success->existsKey($hash)){
            $this->d['success'] = $success->get($hash);
        }
        else{
            $this->d['success'] = $hash;
        }
    }

    function handleError(){
        $hash = $_GET['error'];
        $error = new ErrorMessages();

        if($error->existsKey($hash)){
            $this->d['error'] = $error->get($hash);
        }
        else{
            $this->d['error'] = $hash;
        }
    }

    public function showMessages(){
        $this-> showSuccess();
        $this-> showError();


    }

    public function loadCSSJSMessages(){
        echo '<style type="text/css">
        .alertContainer{
            position: absolute;
            width: 100%;
        }
        .alert {
            position: absolute;
            top: -50%;
            width: 50%;
            left: 25%;
            opacity: 1;
            transition: transform 0.5s ease-in-out, opacity 1s ease-in-out;
            z-index: 9999;
        }
        .alert.show {
            transform: translateY(100%);
        }
        .alert.hide {
            opacity: 0;
        }
    </style>';
    echo '<script type="text/javascript">
        
        setTimeout(function(){
            document.querySelectorAll(".alert").forEach(x => {
                x.hidden = true;
                x.classList.add("hide");
                x.classList.remove("show");
            });
        }, 5000);
        setTimeout(function(){
            document.querySelectorAll(".alert").forEach(x => {
                x.classList.remove("show");
                x.hidden = false;
                x.classList.remove("hide");
                x.classList.add("show");
            });
        }, 10);        
    </script>';

    }

    public function showSuccess(){
        if(array_key_exists('success', $this->d)){
            echo '<div class="alertContainer"><div class="alert alert-success" role="alert" id="myAlertS">'.$this->d['success'].'</div></div>';
            
        }
    }

    public function showError(){
        if(array_key_exists('error', $this->d)){
            echo '<div class="alertContainer"><div class="alert alert-danger" role="alert" id="myAlertE">'.$this->d['error'].'</div></div>';
        }
    }

    //esto es para redirijir y eliminar el mensaje de la cabecera
        // sleep(3);
                    
        // $url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        // if (strpos($url, "error=") !== false) {
        //     $url = substr($url, 0, strpos($url, "error="));
        // } else {
        //     $url = substr($url, 0, strpos($url, "success="));
        // }
        // header("Location: ".$url);
    

}

?>