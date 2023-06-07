<?php

class Session{

    private $sessionName = 'user';

    function __construct()
    {
        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }
    }

    function setCurrentUser($user){
        error_log("Session::setCurrentUser -> Usuario: ".$user."");
        $_SESSION[$this->sessionName] = $user;
    }

    function getCurrentUser(){
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