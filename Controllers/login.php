<?php

    class Login extends SessionController{
        public $model;
        function __construct()
        {
            parent::__construct();
            error_log("Login::CONSTRUCT -> Inicio de la clase Login");
        }

        function render(){
            error_log("Login::RENDER -> Inicio de la view Login");
            $this->view->render('login', []);
        }

        function signup(){
            error_log("Login::IR A SIGNUP -> Inicio de la view Signup");
            $this->view->render('signup', []);
            return '';
        }

        function authenticate(){
            error_log("Login::LOGIN -> Inicio de la view Login");
            if($this->existPOST(['email', 'password']))
            {
                $username = $_POST['email'];
                $password = $_POST['password'];

                if($username == '' || $password == ''){
                    $this->redirect('', ['error' => 'Usuario o contraseña vacios']);
                }
                error_log("Login::LOGIN -> Comprobacion de usuario".$username."/".$password);
                $user = $this->model->login($username, $password);

                if($user != null){
                    error_log("Login::LOGIN -> Usuario encontrado");
                    $this->initialize($user);
                    $this->redirect('home', []);
                }
                else{
                    error_log("Login::LOGIN -> Usuario no encontrado o desactivado");
                    $this->redirect('', ['error' => 'Usuario o contraseña incorrectos o usuario desactivado']);
                }

            }
            return '';
        }

        function recuperarPassword(){
           if(isset($_POST['email'])){
                $email = $_POST['email'];
                $user = new UserModel();
                if($user->exists($email)){                    
                    $token = hash("md5",$email);
                    $this->send_recovery_email($email, $token);
                    $this->redirect('', ['success' => 'Se ha enviado un correo a su cuenta para recuperar su contraseña']);
                }
                else{
                    $this->redirect('', ['error' => 'No existe un usuario con ese email']);
                }
            }
            else{
                $this->redirect('', ['error' => 'No se ha introducido ningun email']);
           }
        }

        function send_recovery_email(string $email, string $token){
        $recover_link = baseUrl."recoverPassword/render/email=$email~token=$token";
        //cuando son de servidor los parametros se pasan por / en vez de ? tradicionalmente
        // set email subject & body
        $subject = 'Recupera tu contraseña';
        $message = <<<MESSAGE
                ¡Hola!,
                Por favor, haz click en el siguiente enlace para recuperar tu contraseña:<br><br><br><br>
                <a href="$recover_link" type="button" style="background-color: #F3AF50; color: white; padding: 1rem; border: none; cursor: pointer; border-radius: 4px;">
                    recuperar contraseña
                </a>
                <br><br><br><br>
                Si el botón no funciona o no se muestra correctamente, sigue este enlace:<br>
                <br>$recover_link
                MESSAGE;

        // email header
        $header = "From: " . emailHost . "\r\n";
        $header .= "Reply-To: " . emailHost . "\r\n";
        $header .= "X-Mailer: PHP/" . phpversion();
        $header .= "Mime-Version: 1.0\r\n";
        $header .= "Content-Type: text/html; charset=utf-8\r\n";

        // send the email
        mail($email, $subject, $message, $header);
        }
    }

?>