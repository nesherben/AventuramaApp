<?php

class SessionController extends Controller{
    private $userid;
    private $userSession;
    private $username;

    private $user;
    private $session;
    private $sites;
    private $defaultSites;

    function __construct()
    {
        parent::__construct();
        $this->init();
    }
    public function getUserSession(){
        return $this->user;
    }

    public function getUsername(){
        return $this->username;
    }

    public function getUserId(){
        return $this->userid;
    }
    function init(){
        $this->session = new Session();
        $json = $this->getJsonFileConfig();
        $this->sites = $json['sites'];
        $this->defaultSites = $json['default-sites'];

        $this->validateSession();
    }

    function initialize($user){
        $this->user = $user;
        $this->session->setCurrentUser($user->getId());
        $this->authorizeAccess($user->getRole());
    }

    private function getJsonFileConfig(){
        $string = file_get_contents('access.json');
        $json = json_decode($string, true);
        return $json;
    }

    function getUserSessionData(){
        
        $id = $this->session->getCurrentUser();
        $this->user = new UserModel();
        $this->user->get($id);
        error_log("Session::getUserSessionData -> Usuario: ".$this->user->getEmail());
        return $this->user;
    }
    public function validateSession(){
        
        if($this->existSession()){
            $role = $this->getUserSessionData()->getRole();
            if($this->isPublic()){
                $this->redirectDefaultSiteByRole($role);
            }
            //pagina privada
            else{
                if($this->isAuthorized($role) || $role == 'admin'){
                    //dejar pasar
                }
                else{
                    $this->redirectDefaultSiteByRole($role);
                }
            }

        }
        else{
            //no existe sesion
            if($this->isPublic()){
                //dejar pasar
            }
            else{
                header('Location: '.baseUrl.'');
            }
        }
    }
   
    function existSession(){
        if(!$this->session->exist()){
            error_log("Sessions::".session_status()." sesion = ".isset($_SESSION['user']));
            return false;
        }
        if($this->session->getCurrentUser() == null){
            error_log("Sessions::".session_status()." sesion = ". $_SESSION['user']);

            return false;
        }
        $userid = $this->session->getCurrentUser();
        error_log("Sessions::".session_status()." sesion = ". $_SESSION['user']);
        if($userid) return true;
        return false;
    }
    function isPublic(){
        $currentURL = $this->getCurrentPage();
        $currentURL = preg_replace("/\?.*/", "", $currentURL);

        for($i = 0; $i < sizeof($this->sites); $i++){
            if($currentURL == $this->sites[$i]['site'] && $this->sites[$i]['access'] == 'public'){
                return true;
            }
        }
        return false;
    }
    function isAuthorized($role){
        $currentURL = $this->getCurrentPage();
        error_log("isAuthorized -> URL actual: ".$currentURL."");

        $currentURL = preg_replace("/\?.*/", "", $currentURL);
        error_log("isAuthorized -> URL actual: ".$currentURL."");
        for($i = 0; $i < sizeof($this->sites); $i++){
            if($currentURL == $this->sites[$i]['site'] && $this->sites[$i]['role'] == $role){
                return true;
            }
        }
        return false;
    }
    function authorizeAccess($role){
        switch($role){
            case 'admin':
                $this->redirect($this->defaultSites['admin'], []);
            break;
            case 'user':
                $this->redirect($this->defaultSites['user'], []);
            break;
            default:
                return false;
        }
    }

    function getCurrentPage(){
        $actual = trim($_SERVER['REQUEST_URI']);
        error_log("Session::getCurrentPage -> URL actual: ".$actual);	
        $url = explode('/', $actual);
        error_log("Session::getCurrentPage -> Pagina actual: ".$url[1]);
        return $url[1]; //?
    }

    function redirectDefaultSiteByRole($role){
        $url = '';
        for($i = 0; $i < sizeof($this->sites); $i++){
            if($this->sites[$i]['role'] == $role){
                $url = '/'.$this->sites[$i]['site'];
                break;
            }
        }
        error_log("Session::redirectDefaultSiteByRole -> Redireccionando a: ".$url);
        header('location: '.$url);
    }

    function logout(){
        $this->session->closeSession();        
    }

}

?>