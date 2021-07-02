<?php

    class ModDespesa {

        private $despId;
        private $despNome;
        private $despValor;
        private $despSit;
        private $despTipo;
        private $dtaInc;
        private $dtaAlt;
        private $usrInc;
        private $usrAlt;

        public function __construct($despId=0,$despNome="",$despValor="",$despSit="",$despTipo="",$dtaInc="",$dtaAlt="",$usrInc=0,$usrAlt=0) {  
            $this->despId      = $despId;
            $this->despNome    = $despNome;
            $this->despValor   = $despValor;
            $this->despSit     = $despSit;
            $this->despTipo    = $despTipo;
            $this->dtaInc      = $dtaInc;
            $this->dtaAlt      = $dtaAlt;
            $this->usrInc      = $usrInc;
            $this->usrAlt      = $usrAlt;
        }

        public function retornaArray(){
            return get_object_vars($this);
        }

        /**
         * Get the value of despId
         */ 
        public function getDespId()
        {
                return $this->despId;
        }

        /**
         * Set the value of despId
         *
         * @return  self
         */ 
        public function setDespId($despId)
        {
                $this->despId = $despId;

                return $this;
        }

        /**
         * Get the value of despNome
         */ 
        public function getDespNome()
        {
                return $this->despNome;
        }

        /**
         * Set the value of despNome
         *
         * @return  self
         */ 
        public function setDespNome($despNome)
        {
                $this->despNome = $despNome;

                return $this;
        }

        /**
         * Get the value of despValor
         */ 
        public function getDespValor()
        {
                return $this->despValor;
        }

        /**
         * Set the value of despValor
         *
         * @return  self
         */ 
        public function setDespValor($despValor)
        {
                $this->despValor = $despValor;

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

        /**
         * Get the value of despSit
         */ 
        public function getDespSit()
        {
                return $this->despSit;
        }

        /**
         * Set the value of despSit
         *
         * @return  self
         */ 
        public function setDespSit($despSit)
        {
                $this->despSit = $despSit;

                return $this;
        }

        /**
         * Get the value of despTipo
         */ 
        public function getDespTipo()
        {
                return $this->despTipo;
        }

        /**
         * Set the value of despTipo
         *
         * @return  self
         */ 
        public function setDespTipo($despTipo)
        {
                $this->despTipo = $despTipo;

                return $this;
        }
    }

?>