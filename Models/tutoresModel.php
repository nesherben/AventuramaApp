<?php

class TutoresModel extends Model
{

    public $id;
    public $email;
    public $nombre;
    public $apellidos;
    public $telefono;
    public $dni;
    public $direccion;
    public $direccion2;
    public $cp;
    public $localidad;
    public $provincia;
    public $idPrincipal;

    public function __construct()
    {
        parent::__construct();

        $this->id = 0;
        $this->email = "";
        $this->nombre = "";
        $this->apellidos = "";
        $this->telefono = "";
        $this->dni = "";
        $this->direccion = "";
        $this->direccion2 = "";
        $this->cp = "";
        $this->localidad = "";
        $this->provincia = "";
        $this->idPrincipal = "";
    }
    ////////////////////////////////////////
    //    Funciones
    ///////////////////////////////////////

    public function save()
    {
        try {
            $query = $this->prepare(
                'INSERT INTO tutores (email, nombre, apellidos, num_tlfn, dni, direccion, direccion2, cp, localidad, provincia, id_principal) 
                     VALUES (:email, :nombre, :apellidos, :telefono, :dni, :direccion, :direccion2, :cp, :localidad, :provincia, :id_principal)'
            );
            $query->execute([
                'email'     => $this->email,
                'nombre'    => $this->nombre,
                'apellidos' => $this->apellidos,
                'telefono'  => $this->telefono,
                'dni'       => $this->dni,
                'direccion' => $this->direccion,
                'direccion2' => $this->direccion2,
                'cp' => $this->cp,
                'localidad' => $this->localidad,
                'provincia' => $this->provincia,
                'id_principal' => $this->idPrincipal
            ]);
            return true;
        } catch (PDOException $e) {
            error_log('USERMODEL::SAVE->PDOException ' . $e);
            return false;
        }
    }
    public function getAll(string $id)
    {
        try {
            $query = $this->prepare('SELECT * FROM tutores WHERE ID_PRINCIPAL = :id');
            $query->execute(['id' => $id]);
            $p = $query->fetchAll(PDO::FETCH_ASSOC);
            return $p;
        } catch (PDOException $e) {
            error_log('USERMODEL::GET->PDOException ' . $e);
        }
    }

    public function get($id, $idPrincipal)
    {
        try {
            $query = $this->prepare('SELECT * FROM tutores WHERE ID_PRINCIPAL = :idPrincipal and ID_TUTOR = :id');
            $query->execute(['id' => $id, 'idPrincipal' => $idPrincipal]);
            $p = $query->fetch(PDO::FETCH_ASSOC);

            return $p;
        } catch (PDOException $e) {
            error_log('USERMODEL::GET->PDOException ' . $e);
        }
    }

    public function delete($id)
    {
        try {
            $query = $this->prepare('DELETE FROM tutores WHERE ID_TUTOR = :id');
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
            $query = $this->prepare('UPDATE tutores SET email = :email, nombre = :nombre, apellidos = :apellidos,
                        num_tlfn = :telefono, dni = :dni, direccion = :direccion,
                        direccion2 = :direccion2, cp = :cp, localidad = :localidad, provincia = :provincia
                        WHERE id_tutor = :id');
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

    public function from($array)
    {
        $this->id           = $array['ID_TUTOR'];
        $this->idPrincipal  = $array['ID_PRINCIPAL'];
        $this->email        = $array['EMAIL'];
        $this->nombre       = $array['NOMBRE'];
        $this->apellidos    = $array['APELLIDOS'];
        $this->telefono     = $array['NUM_TLFN'];
        $this->dni          = $array['DNI'];
        $this->direccion    = $array['DIRECCION'];
        $this->direccion2   = $array['DIRECCION2'];
        $this->cp           = $array['CP'];
        $this->localidad    = $array['LOCALIDAD'];
        $this->provincia    = $array['PROVINCIA'];
    }

    ////////////////////////////////////////
    //    Funciones propias
    ///////////////////////////////////////


    //getters 
    public function getId()
    {
        return $this->id;
    }
    public function getEmail()
    {
        return $this->email;
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
    public function getIdPrincipal()
    {
        return $this->idPrincipal;
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
    public function setIdPrincipal($idPrincipal)
    {
        $this->idPrincipal = $idPrincipal;
    }
}
