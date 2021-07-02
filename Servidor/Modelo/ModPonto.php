<?php

    class ModPonto {

        private $pontoId;
        private $pontoData;
        private $pontoSituacao;
        private $dtaInc;
        private $dtaAlt;
        private $usrInc;
        private $usrAlt;
        private $pontoPessoas;

        public function __construct($pontoId=0,$pontoData="",$pontoSituacao="",$dtaInc="",$dtaAlt="",$usrInc=0,$usrAlt=0) {  
            $this->pontoId       = $pontoId;
            $this->pontoData     = $pontoData;
            $this->pontoSituacao = $pontoSituacao;
            $this->dtaInc        = $dtaInc;
            $this->dtaAlt        = $dtaAlt;
            $this->usrInc        = $usrInc;
            $this->usrAlt        = $usrAlt;
        }

        public function retornaArray(){
            return get_object_vars($this);
        }

        /**
         * Get the value of pontoId
         */ 
        public function getPontoId()
        {
                return $this->pontoId;
        }

        /**
         * Set the value of pontoId
         *
         * @return  self
         */ 
        public function setPontoId($pontoId)
        {
                $this->pontoId = $pontoId;

                return $this;
        }

        /**
         * Get the value of pontoData
         */ 
        public function getPontoData()
        {
                return $this->pontoData;
        }

        /**
         * Set the value of pontoData
         *
         * @return  self
         */ 
        public function setPontoData($pontoData)
        {
                $this->pontoData = $pontoData;

                return $this;
        }

        /**
         * Get the value of pontoSituacao
         */ 
        public function getPontoSituacao()
        {
                return $this->pontoSituacao;
        }

        /**
         * Set the value of pontoSituacao
         *
         * @return  self
         */ 
        public function setPontoSituacao($pontoSituacao)
        {
                $this->pontoSituacao = $pontoSituacao;

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
         * Get the value of pontoPessoas
         */ 
        public function getPontoPessoas()
        {
                return $this->pontoPessoas;
        }

        /**
         * Set the value of pontoPessoas
         *
         * @return  self
         */ 
        public function setPontoPessoas($pontoPessoas)
        {
                $this->pontoPessoas = $pontoPessoas;

                return $this;
        }
    }

?>