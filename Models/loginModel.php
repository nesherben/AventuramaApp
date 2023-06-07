<?php
class LoginModel extends Model{

    function __construct()
    {
        parent::__construct();
    }
    
    public function login($email, $password){
        try{
            $query = $this->db->connect()->prepare('SELECT * FROM usuarios WHERE email = :email and activado = 1');
            $query->execute(['email' => $email]);

            if($query->rowCount() == 0){
                error_log("LoginModel::LOGIN -> Usuario no encontrado o desactivado");
                return null;
            }
            
            $user = new UserModel();
            $user->from($query->fetch(PDO::FETCH_ASSOC));

            if(hash('md5',$password) == $user->getPassword()){
                error_log("LoginModel::LOGIN -> Contraseña correcta");
                return $user;
            }
            else{
                error_log("LoginModel::LOGIN -> Contraseña incorrecta");
                return null;
            }

        }catch(PDOException $e){
            error_log("LoginModel::LOGIN -> Error al realizar la consulta: ".$e->getMessage());
            return null;
        }
    }

}

?>