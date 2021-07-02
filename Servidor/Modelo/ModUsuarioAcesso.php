<?php

    class ModUsuarioAcesso {
        
        private $acessoId;
        private $acessoDescricao;
        private $acessoCodigo;
        private $acessoValor;
        private $usuario;

        /**
         * Get the value of acessoId
         */ 
        public function getAcessoId()
        {
                return $this->acessoId;
        }

        /**
         * Set the value of acessoId
         *
         * @return  self
         */ 
        public function setAcessoId($acessoId)
        {
                $this->acessoId = $acessoId;

                return $this;
        }

        /**
         * Get the value of acessoDescricao
         */ 
        public function getAcessoDescricao()
        {
                return $this->acessoDescricao;
        }

        /**
         * Set the value of acessoDescricao
         *
         * @return  self
         */ 
        public function setAcessoDescricao($acessoDescricao)
        {
                $this->acessoDescricao = $acessoDescricao;

                return $this;
        }

        /**
         * Get the value of acessoCodigo
         */ 
        public function getAcessoCodigo()
        {
                return $this->acessoCodigo;
        }

        /**
         * Set the value of acessoCodigo
         *
         * @return  self
         */ 
        public function setAcessoCodigo($acessoCodigo)
        {
                $this->acessoCodigo = $acessoCodigo;

                return $this;
        }

        /**
         * Get the value of acessoValor
         */ 
        public function getAcessoValor()
        {
                return $this->acessoValor;
        }

        /**
         * Set the value of acessoValor
         *
         * @return  self
         */ 
        public function setAcessoValor($acessoValor)
        {
                $this->acessoValor = $acessoValor;

                return $this;
        }

        /**
         * Get the value of usuario
         */ 
        public function getUsuario()
        {
                return $this->usuario;
        }

        /**
         * Set the value of usuario
         *
         * @return  self
         */ 
        public function setUsuario($usuario)
        {
                $this->usuario = $usuario;

                return $this;
        }

        public function __construct($acessoId=0, $acessoDescricao="", $acessoCodigo="", $acessoValor="", $usuario="") {  
            $this->acessoId        = $acessoId;
            $this->acessoDescricao = $acessoDescricao;
            $this->acessoCodigo    = $acessoCodigo;
            $this->acessoValor     = $acessoValor;
            $this->usuario         = $usuario;
        }

        public function retornaArray(){
            return get_object_vars($this);
        }

    }

?>