<?php

class Session{

    private $sessionName = 'user';

    function __construct()
    {
        error_log("sessions STATUS = ".session_status());
        if(session_status() == PHP_SESSION_NONE){
            session_start(['cookie_lifetime' => 86400,]);
        }
    }

    function setCurrentUser($user){
        error_log("Session::setCurrentUser -> Usuario: ".$user."");
        $_SESSION[$this->sessionName] = $user;
    }

    function getCurrentUser(){
        error_log("Sessions::".session_status()." sesion = ".$_SESSION['user']);
        return $_SESSION[$this->sessionName];
    }

    function closeSession(){
        session_unset();
        session_destroy();
        error_log("Session::closeSession -> Sesion cerrada, redireccionando a ".baseUrl."");
        header('Location: '.baseUrl.'');
    }

    public function exist(){
        return isset($_SESSION[$this->sessionName]);
    }
}


?>