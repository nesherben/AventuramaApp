<?php

    class ErrorController extends SessionController{
        public $user;
        function __construct()
        {         
            parent::__construct();
            $this->user = $this->getUserSession();
            $this->render();
            error_log("error::CONSTRUCT -> Inicio de la clase error"); 
        }
    
        function render(){
            $this->view->render('error',['user' => $this->user]);
        }
    }

?>