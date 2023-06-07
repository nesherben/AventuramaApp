<?php

class RecoverPassword extends SessionController{

    function __construct(){
        parent::__construct();
    }

    public function render(array $params = []){
        $this->view->render('recoverPassword', $params);       
    }

    public function recover(){
      error_log("recover::recover -> recupeprar contraseña");

      if($this->existPOST(['email', 'token', 'password', 'password2'])){
          $email = $_POST['email'];
          $token = $_POST['token'];
          $password = $_POST['password'];
          $password2 = $_POST['password2'];
          
          if($token != hash("md5",$email)){
              error_log("recover::recover -> token no coincide");
              $this->redirect('recoverPassword', ['error' => 'Token no coincide']);
          }

          if($password != $password2){
              error_log("recover::recover -> contraseñas no coinciden");
              $this->redirect('recoverPassword', ['error' => 'Las contraseñas no coinciden']);
          }
          else{
              $user = new UserModel();
              $user->setEmail($email);
              $user->setPassword($password);
              error_log("recover::recover -> comprobacion bbdd");

              if(!$user->exists($email)){
                  error_log("Signup::newUser -> usuario no existe");
                  exit();
              } else if ($user->changePassword()) {
                  $this->redirect('', ['success' => 'Contraseña cambiada correctamente']);
              } else{
                  error_log("recover::recover -> error al crear usuario");
                  $this->redirect('recoverPassword', ['error' => 'Error al cambiar la contraseña']);
              }
          }
      }
      else{
          error_log("recover::recover -> faltan datos");
          $this->redirect('recoverPassword', ['error' => 'Faltan datos']);         
      }
    }
}

?>