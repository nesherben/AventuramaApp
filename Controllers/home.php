<?php

    class Home extends SessionController{
        private $user;
        public $model;
        public $lista;
        
        function __construct()
        {         
            parent::__construct();
           
            $this->user = $this->getUserSession();
            error_log("Home::CONSTRUCT -> Inicio de la clase Home"); 
        }

        function render(){
            error_log("Home::RENDER -> ".$this->user->getEmail());
            $this->view->render('home',['user' => $this->user, 'lista' => $this->construirLista()]);
        }

        function construirLista(){            
            $items = $this->model->getAll();
            $categorias = array();
            foreach($items as $item){
                $categoria = $item->getCategoria();
                if(!array_key_exists($categoria, $categorias)){
                    error_log("Home::RENDER -> ".$categoria."");
                    $categorias[$categoria] = array();
              }
            }
            error_log("Home::RENDER -> ".count($categorias)."");
            foreach($items as $item){
              $nombre = $item->getNombre();
              $desc = $item->getDescripcion();
              $codigo = $item->getCodigo();
              $categoria = $item->getCategoria();
              $imagen = $item->getImagen();
              foreach($categorias as $key => $cat){
                if($key == $categoria){
                  array_push($categorias[$key], array("nombre"=>$nombre, "descripcion"=>$desc, "codigo"=>$codigo, "categoria"=>$categoria, "imagen"=>$imagen));
                    error_log("Home::RENDER -> ".count($cat)."");
                  }
                    
                }                
            }

            return $categorias;
        }

    }
