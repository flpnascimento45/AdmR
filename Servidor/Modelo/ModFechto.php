<?php

    class ModFechto {

        private $fechtoId;
        private $fechtoDescricao;
        private $fechtoData;
        private $fechtoSit;
        private $dtaInc;
        private $dtaAlt;
        private $usrInc;
        private $usrAlt;

        public function __construct($fechtoId=0,$fechtoDescricao="",$fechtoData="",$fechtoSit="",$dtaInc="",$dtaAlt="",$usrInc=0,$usrAlt=0) {  
            $this->fechtoId        = $fechtoId;
            $this->fechtoDescricao = $fechtoDescricao;
            $this->fechtoData      = $fechtoData;
            $this->fechtoSit       = $fechtoSit;
            $this->dtaInc          = $dtaInc;
            $this->dtaAlt          = $dtaAlt;
            $this->usrInc          = $usrInc;
            $this->usrAlt          = $usrAlt;
        }

        public function retornaArray(){
            return get_object_vars($this);
        }


        /**
         * Get the value of fechtoId
         */ 
        public function getFechtoId()
        {
                return $this->fechtoId;
        }

        /**
         * Set the value of fechtoId
         *
         * @return  self
         */ 
        public function setFechtoId($fechtoId)
        {
                $this->fechtoId = $fechtoId;

                return $this;
        }

        /**
         * Get the value of fechtoDescricao
         */ 
        public function getFechtoDescricao()
        {
                return $this->fechtoDescricao;
        }

        /**
         * Set the value of fechtoDescricao
         *
         * @return  self
         */ 
        public function setFechtoDescricao($fechtoDescricao)
        {
                $this->fechtoDescricao = $fechtoDescricao;

                return $this;
        }

        /**
         * Get the value of fechtoData
         */ 
        public function getFechtoData()
        {
                return $this->fechtoData;
        }

        /**
         * Set the value of fechtoData
         *
         * @return  self
         */ 
        public function setFechtoData($fechtoData)
        {
                $this->fechtoData = $fechtoData;

                return $this;
        }

        /**
         * Get the value of fechtoSit
         */ 
        public function getFechtoSit()
        {
                return $this->fechtoSit;
        }

        /**
         * Set the value of fechtoSit
         *
         * @return  self
         */ 
        public function setFechtoSit($fechtoSit)
        {
                $this->fechtoSit = $fechtoSit;

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