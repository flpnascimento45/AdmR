<?php

    class ModPontoPessoa {

        private $ponPesId;
        private $pessoa;
        private $ponto;
        private $ponPesPresente;
        private $dtaInc;
        private $dtaAlt;
        private $usrInc;
        private $usrAlt;

        public function __construct($ponPesId=0,$pessoa="",$ponto="",$ponPesPresente="",$dtaInc="",$dtaAlt="",$usrInc=0,$usrAlt=0) {  
            $this->ponPesId       = $ponPesId;
            $this->pessoa         = $pessoa;
            $this->ponto          = $ponto;
            $this->ponPesPresente = $ponPesPresente;
            $this->dtaInc         = $dtaInc;
            $this->dtaAlt         = $dtaAlt;
            $this->usrInc         = $usrInc;
            $this->usrAlt         = $usrAlt;
        }

        public function retornaArray(){
            return get_object_vars($this);
        }

        /**
         * Get the value of ponPesId
         */ 
        public function getPonPesId()
        {
                return $this->ponPesId;
        }

        /**
         * Set the value of ponPesId
         *
         * @return  self
         */ 
        public function setPonPesId($ponPesId)
        {
                $this->ponPesId = $ponPesId;

                return $this;
        }

        /**
         * Get the value of pessoa
         */ 
        public function getPessoa()
        {
                return $this->pessoa;
        }

        /**
         * Set the value of pessoa
         *
         * @return  self
         */ 
        public function setPessoa($pessoa)
        {
                $this->pessoa = $pessoa;

                return $this;
        }

        /**
         * Get the value of ponto
         */ 
        public function getPonto()
        {
                return $this->ponto;
        }

        /**
         * Set the value of ponto
         *
         * @return  self
         */ 
        public function setPonto($ponto)
        {
                $this->ponto = $ponto;

                return $this;
        }

        /**
         * Get the value of ponPesPresente
         */ 
        public function getPonPesPresente()
        {
                return $this->ponPesPresente;
        }

        /**
         * Set the value of ponPesPresente
         *
         * @return  self
         */ 
        public function setPonPesPresente($ponPesPresente)
        {
                $this->ponPesPresente = $ponPesPresente;

                return $this;
        }

        /**
         * Get the value of dtaInc
         */ 
        public function getDtaInc()
        {
                return $this->dtaInc;
        }

        /**
         * Set the value of dtaInc
         *
         * @return  self
         */ 
        public function setDtaInc($dtaInc)
        {
                $this->dtaInc = $dtaInc;

                return $this;
        }

        /**
         * Get the value of dtaAlt
         */ 
        public function getDtaAlt()
        {
                return $this->dtaAlt;
        }

        /**
         * Set the value of dtaAlt
         *
         * @return  self
         */ 
        public function setDtaAlt($dtaAlt)
        {
                $this->dtaAlt = $dtaAlt;

                return $this;
        }

        /**
         * Get the value of usrInc
         */ 
        public function getUsrInc()
        {
                return $this->usrInc;
        }

        /**
         * Set the value of usrInc
         *
         * @return  self
         */ 
        public function setUsrInc($usrInc)
        {
                $this->usrInc = $usrInc;

                return $this;
        }

        /**
         * Get the value of usrAlt
         */ 
        public function getUsrAlt()
        {
                return $this->usrAlt;
        }

        /**
         * Set the value of usrAlt
         *
         * @return  self
         */ 
        public function setUsrAlt($usrAlt)
        {
                $this->usrAlt = $usrAlt;

                return $this;
        }

    }

?>