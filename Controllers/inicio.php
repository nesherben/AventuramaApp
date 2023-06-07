<?php

    class inicio extends SessionController{
        private $user;
        public $model;
        public $lista;
        
        function __construct()
        {         
            parent::__construct();
           
            $this->user = $this->getUserSession();
            error_log("Inicio::CONSTRUCT -> Inicio de la clase Inicio"); 
        }

        function render(){            
            $this->view->render('inicio', ['user' => $this->user, 'lista' => $this->construirLista()]);
        }

        function construirLista(){            
            $items = $this->model->getAll();
            $categorias = array();
            foreach($items as $item){
                $categoria = $item->getCategoria();
                if(!array_key_exists($categoria, $categorias)){
                    error_log("Inicio::RENDER -> ".$categoria."");
                    $categorias[$categoria] = array();
              }
            }
            error_log("Inicio::RENDER -> ".count($categorias)."");
            foreach($items as $item){
              $nombre = $item->getNombre();
              $desc = $item->getDescripcion();
              $codigo = $item->getCodigo();
              $categoria = $item->getCategoria();
              $imagen = $item->getImagen();
              foreach($categorias as $key => $cat){
                if($key == $categoria){
                  array_push($categorias[$key], array("nombre"=>$nombre, "descripcion"=>$desc, "codigo"=>$codigo, "categoria"=>$categoria, "imagen"=>$imagen));
                    error_log("Inicio::RENDER -> ".count($cat)."");
                  }
                    
                }                
            }

            return $categorias;
        }

    }
