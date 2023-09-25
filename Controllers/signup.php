<?php 

class Signup extends SessionController{

    public $user;

    function __construct()
    {
        parent::__construct();
        error_log("Signup::CONSTRUCT -> Inicio de la clase Signup");
    }

    function render(){
        $this->view->render('signup', []);
    }

    function newUser(){
        error_log("Signup::newUser -> nuevo usuario");

        if($this->existPOST(['email', 'password', 'password2', 'nombre', 'apellidos', 'telefono'])){
            $email = $_POST['email'];
            $password = $_POST['password'];
            $password2 = $_POST['password2'];
            $nombre = $_POST['nombre'];
            $apellidos = $_POST['apellidos'];
            $telefono = $_POST['telefono'];

            if($password != $password2){
                error_log("Signup::newUser -> contraseñas no coinciden");
                $this->redirect('signup', ['error' => 'Las contraseñas no coinciden']);
            }
            else{
                $this->user = new UserModel();
                $this->user->setEmail($email);
                $this->user->setPassword($password);
                $this->user->setNombre($nombre);
                $this->user->setApellidos($apellidos);
                $this->user->setTelefono($telefono);
                $this->user->setRole('user');
                error_log("Signup::newUser -> comprobacion bbdd");

                if($this->user->exists($email)){
                    error_log("Signup::newUser -> usuario ya existe");
                    exit();
                } else if ($this->user->save()) {
                    error_log("Signup::newUser -> manda email");
                    if($this->send_activation_email($_POST['email'], hash('md5', $this->user->getPassword())))    
                        $this->redirect('', ['success' => 'Usuario creado correctamente, revise su correo para activar su cuenta']);
                    else{
                        error_log("Signup::newUser -> error al mandar email");
                        $this->redirect('signup', ['error' => 'Error al mandar el correo de activación']);
                    }
                } else{
                    error_log("Signup::newUser -> error al crear usuario");
                    $this->redirect('signup', ['error' => 'Error al crear el usuario']);
                }
            }
        }
        else{
            error_log("Signup::newUser -> faltan datos");
            $this->redirect('signup', ['error' => 'Faltan datos']);
        }
    }

    function reactivate(){
        if($this->existPOST(['email', 'password'])){
            $email = $_POST['email'];
            $password = $_POST['password'];

            $this->user = new UserModel();
            $this->user->setEmail($email);
            $this->user->setPassword($password);

            if($this->user->exists($email)){
                if($this->send_activation_email($email, hash('md5', $this->user->getPassword())))
                    $this->redirect('', ['success' => 'Se ha reenviado el correo de activación']);
                else{
                    $this->redirect('', ['error' => 'Error al reenviar el correo de activación']);
                }
            } else{
                $this->redirect('', ['error' => 'El usuario no existe']);
            }
        } else{
            $this->redirect('', ['error' => 'Faltan datos']);
        }
    }
    
    function send_activation_email(string $email, string $activation_code)
    {        
        // create the activation link
        $activation_link = baseUrl."signup/activate_account/email=$email~activation_code=$activation_code";
        //cuando son de servidor los parametros se pasan por / en vez de ? tradicionalmente
        // set email subject & body
        $subject = 'Por favor, activa tu cuenta';
        $message = <<<MESSAGE
                ¡Muchas gracias por haberte registrado en Aventurama!<br><br>
                Falta un último paso para que puedas disfrutar de todas las actividades de Aventurama, que es confirmar tu cuenta. Para ello pincha en este enlace:<br><br><br><br>
                <a href="$activation_link" type="button" style="background-color: #4CAF50; color: white; padding: 1rem; border: none; cursor: pointer; border-radius: 4px;">
                    Activar cuenta
                </a>
                <br><br><br><br>
                Si el botón no funciona o no se muestra correctamente, sigue este enlace:<br>
                <br>$activation_link
                MESSAGE;

        // email header
        $header = "From: " . emailHost . "\r\n";
        $header .= "Reply-To: " . emailHost . "\r\n";
        $header .= "X-Mailer: PHP/" . phpversion();
        $header .= "Mime-Version: 1.0\r\n";
        $header .= "Content-Type: text/html; charset=utf-8\r\n";

        // send the email
        return mail($email, $subject, $message, $header);
    }

    function activate_account(array $params = []): void
    {
        $this->user = new UserModel();
        // get the email and activation code from the URL
        $email = $params['email'];
        $activation_code = $params['activation_code'];
        if(!isset($email) || !isset($activation_code)){
            $this->redirect('', ['error' => 'Activación incorrecta']);
        }
        // call the activate() method from the UserModel class
        if($this->user->activate($email, $activation_code)){
            $this->redirect('', ['success' => 'Tu cuenta ha sido activada, ahora puedes iniciar sesión.']);
        }
    }
}

?>