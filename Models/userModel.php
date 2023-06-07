<?php

class UserModel extends Model
{

    public $id;
    public $email;
    public $password;
    public $nombre;
    public $apellidos;
    public $telefono;
    public $dni;
    public $role;
    public $direccion;
    public $direccion2;
    public $cp;
    public $localidad;
    public $provincia;
    public $tieneDNI;

    public function __construct()
    {
        parent::__construct();

        $this->id = 0;
        $this->email = "";
        $this->password = "";
        $this->nombre = "";
        $this->apellidos = "";
        $this->telefono = "";
        $this->dni = "";
        $this->role = "";
        $this->direccion = "";
        $this->direccion2 = "";
        $this->cp = "";
        $this->localidad = "";
        $this->provincia = "";
        $this->tieneDNI = false;
    }
    ////////////////////////////////////////
    //    Funciones
    ///////////////////////////////////////

    public function save()
    {
        try {
            $query = $this->prepare(
                'INSERT INTO usuarios (email, password, nombre, apellidos, num_tlfn, dni, direccion, direccion2, cp, localidad, provincia) 
                     VALUES (:email, :password, :nombre, :apellidos, :telefono, :dni, :direccion, :direccion2, :cp, :localidad, :provincia)'
            );
            $query->execute([
                'email'     => $this->email,
                'password'  => $this->password,
                'nombre'    => $this->nombre,
                'apellidos' => $this->apellidos,
                'telefono'  => $this->telefono,
                'dni'       => $this->dni,
                'direccion' => $this->direccion,
                'direccion2' => $this->direccion2,
                'cp' => $this->cp,
                'localidad' => $this->localidad,
                'provincia' => $this->provincia
            ]);
            return true;
        } catch (PDOException $e) {
            error_log('USERMODEL::SAVE->PDOException ' . $e);
            return false;
        }
    }

    public function getAll()
    {
        try {
            $items = [];
            $query = $this->query('SELECT * FROM usuarios');
            while ($p = $query->fetch(PDO::FETCH_ASSOC)) {
                $item = new UserModel();
                $item->setId($p['ID_USUARIO']);
                $item->setEmail($p['EMAIL']);
                $item->setPassword($p['PASSWORD']);
                $item->setNombre($p['NOMBRE']);
                $item->setApellidos($p['APPELLIDOS']);
                $item->setTelefono($p['NUM_TLFN']);
                $item->setDni($p['DNI']);
                $item->setRole($p['ROL']);
                $item->setDireccion($p['DIRECCION']);
                $item->setDireccion2($p['DIRECCION2']);
                $item->setCp($p['CP']);
                $item->setLocalidad($p['LOCALIDAD']);
                $item->setProvincia($p['PROVINCIA']);
                $item->from($p);
                array_push($items, $item);
            }
            return $items;
        } catch (PDOException $e) {
            error_log('USERMODEL::GETALL->PDOException ' . $e);
            return false;
        }
    }

    public function get($id)
    {
        try {
            $query = $this->prepare('SELECT * FROM usuarios WHERE id_usuario = :id');
            $query->execute(['id' => $id]);
            $p = $query->fetch(PDO::FETCH_ASSOC);

            $this->setId($p['ID_USUARIO']);
            $this->setEmail($p['EMAIL']);
            $this->setPassword($p['PASSWORD']);
            $this->setNombre($p['NOMBRE']);
            $this->setApellidos($p['APELLIDOS']);
            $this->setTelefono($p['NUM_TLFN']);
            $this->setDni($p['DNI']);
            $this->setRole($p['ROL']);
            $this->setDireccion($p['DIRECCION']);
            $this->setDireccion2($p['DIRECCION2']);
            $this->setCp($p['CP']);
            $this->setLocalidad($p['LOCALIDAD']);
            $this->setProvincia($p['PROVINCIA']);
            $this->tieneDNI = $this->descargarDNI($id) != false ? true : false;
            $this->from($p);
            return $this;
        } catch (PDOException $e) {
            error_log('USERMODEL::GET->PDOException ' . $e);
        }
    }

    public function delete($id)
    {
        try {
            $query = $this->prepare('DELETE FROM usuarios WHERE id_usuario = :id');
            $query->execute(['id' => $id]);
            return true;
        } catch (PDOException $e) {
            error_log('USERMODEL::DELETE->PDOException ' . $e);
            return false;
        }
    }

    public function update()
    {
        try {
            $query = $this->prepare('UPDATE usuarios SET email = :email, nombre = :nombre, apellidos = :apellidos,
                        num_tlfn = :telefono, dni = :dni, direccion = :direccion,
                        direccion2 = :direccion2, cp = :cp, localidad = :localidad, provincia = :provincia
                        WHERE id_usuario = :id');
            $query->execute([
                'id'        => $this->id,
                'email'     => $this->email,
                'nombre'    => $this->nombre,
                'apellidos' => $this->apellidos,
                'telefono'  => $this->telefono,
                'dni'       => $this->dni,
                'direccion' => $this->direccion,
                'direccion2' => $this->direccion2,
                'cp'        => $this->cp,
                'localidad' => $this->localidad,
                'provincia' => $this->provincia
            ]);
            return true;
        } catch (PDOException $e) {
            error_log('USERMODEL::UPDATE->PDOException ' . $e);
            return false;
        }
    }

    public function changePassword()
    {
        try {
            $query = $this->prepare('UPDATE usuarios SET password = :password WHERE email = :email');
            $query->execute([
                'email'        => $this->email,
                'password'  => $this->password
            ]);
            return true;
        } catch (PDOException $e) {
            error_log('USERMODEL::CHANGEPASSWORD->PDOException ' . $e);
            return false;
        }
    }

    public function from($array)
    {
        $this->id           = $array['ID_USUARIO'];
        $this->email        = $array['EMAIL'];
        $this->password     = $array['PASSWORD'];
        $this->nombre       = $array['NOMBRE'];
        $this->apellidos    = $array['APELLIDOS'];
        $this->telefono     = $array['NUM_TLFN'];
        $this->dni          = $array['DNI'];
        $this->role         = $array['ROL'];
        $this->direccion    = $array['DIRECCION'];
        $this->direccion2   = $array['DIRECCION2'];
        $this->cp           = $array['CP'];
        $this->localidad    = $array['LOCALIDAD'];
        $this->provincia    = $array['PROVINCIA'];
    }

    ////////////////////////////////////////
    //    Funciones propias
    ///////////////////////////////////////

    public function login($email, $password)
    {
        try {
            $query = $this->prepare('SELECT * FROM usuarios WHERE email = :email');
            $query->execute(['email' => $email]);
            $user = $query->fetch(PDO::FETCH_ASSOC);
            if ($user && password_verify($password, $user['password'])) {
                return new UserModel();
            } else {
                return null;
            }
        } catch (PDOException $e) {
            error_log('USERMODEL::LOGIN->PDOException ' . $e);
            return null;
        }
    }

    public function activate($email, $code)
    {
        try {
            $query = $this->prepare('SELECT * FROM usuarios WHERE email = :email');
            $query->execute(['email' => $email]);
            $usuario = $query->fetch(PDO::FETCH_ASSOC);
            if (($usuario['EMAIL'] == $email) && ($code == hash('md5', $usuario['PASSWORD']))) {
                $activacion = $this->prepare('UPDATE usuarios SET activado = 1 WHERE email = :email');
                $activacion->execute(['email' => $email]);
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log('USERMODEL::Activacion->PDOException ' . $e);
            return null;
        }
    }


    public function exists($email)
    {
        try {
            $query = $this->prepare('SELECT * FROM usuarios WHERE email = :email');
            $query->execute(['email' => $email]);
            if ($query->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log('USERMODEL::EXISTS->PDOException ' . $e);
            return false;
        }
    }

    public function comparePasswords($password, $id)
    {
        try {
            $user = $this->get($id);
            return password_verify($password, $user->getPassword());
        } catch (PDOException $e) {
            error_log('USERMODEL::COMPAREPASSWORDS->PDOException ' . $e);
            return false;
        }
    }

    private function getHashedPassword($password)
    {
        return hash('md5', $password);
    }

    function guardarDNI(string $id, $dni)
    {
        try {
            $query = $this->prepare('DELETE FROM dnis_usuarios WHERE ID_USUARIO = :id');
            $query->execute([
                'id' => $id
            ]);

            $query = $this->prepare('INSERT into dnis_usuarios (ID_USUARIO, DNI, NM_ARCHIVO, TIPO_ARCHIVO, SIZE_ARCHIVO) VALUES (:id, :dni, :nombre, :tipo, :size)');
            $query->execute([
                'id' => $id,
                'dni' => addslashes(file_get_contents($dni['tmp_name'])),
                'nombre' => $dni['name'],
                'tipo' => $dni['type'],
                'size' => $dni['size']
            ]);
            return true;
        } catch (PDOException $e) {
            error_log('USUARIOS::guardarDNI->PDOException ' . $e);
            return false;
        }
    }

    function descargarDNI(string $id)
    {
        try {
            $query = $this->prepare('SELECT DNI, NM_ARCHIVO, TIPO_ARCHIVO, SIZE_ARCHIVO FROM dnis_usuarios WHERE ID_USUARIO = :id');
            $query->execute([
                'id' => $id
            ]);
            $dniData = $query->fetch(PDO::FETCH_ASSOC);
            return $dniData;
        } catch (PDOException $e) {
            error_log('usuario::descargarDNI->PDOException ' . $e);
            return false;
        }
    }


    //getters 
    public function getId()
    {
        return $this->id;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getApellidos()
    {
        return $this->apellidos;
    }
    public function getTelefono()
    {
        return $this->telefono;
    }
    public function getDni()
    {
        return $this->dni;
    }
    public function getRole()
    {
        return $this->role;
    }
    public function getDireccion()
    {
        return $this->direccion;
    }
    public function getDireccion2()
    {
        return $this->direccion2;
    }
    public function getCp()
    {
        return $this->cp;
    }
    public function getLocalidad()
    {
        return $this->localidad;
    }
    public function getProvincia()
    {
        return $this->provincia;
    }
    //setters
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function setPassword($password)
    {
        $this->password = $this->getHashedPassword($password);
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    }
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }
    public function setDni($dni)
    {
        $this->dni = $dni;
    }
    public function setRole($role)
    {
        $this->role = $role;
    }
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    }
    public function setDireccion2($direccion2)
    {
        $this->direccion2 = $direccion2;
    }
    public function setCp($cp)
    {
        $this->cp = $cp;
    }
    public function setLocalidad($localidad)
    {
        $this->localidad = $localidad;
    }
    public function setProvincia($provincia)
    {
        $this->provincia = $provincia;
    }
}
