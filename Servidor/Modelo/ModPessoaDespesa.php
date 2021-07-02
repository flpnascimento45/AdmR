<?php

    class ModPessoaDespesa {

        private $pesDesId;
        private $pessoa;
        private $despesa;
        private $pesDesVal;
        private $pesDesSituacao;
        private $dtaInc;
        private $dtaAlt;
        private $usrInc;
        private $usrAlt;
        
        public function __construct($pesDesId=0,$pessoa=0,$despesa=0,$pesDesVal="",$pesDesSituacao="",$dtaInc="",$dtaAlt="",$usrInc=0,$usrAlt=0) {  
            $this->pesDesId       = $pesDesId;
            $this->pessoa         = $pessoa;
            $this->despesa        = $despesa;
            $this->pesDesVal      = $pesDesVal;
            $this->pesDesSituacao = $pesDesSituacao;
            $this->dtaInc         = $dtaInc;
            $this->dtaAlt         = $dtaAlt;
            $this->usrInc         = $usrInc;
            $this->usrAlt         = $usrAlt;
        }

        public function retornaArray(){
            return get_object_vars($this);
        }

        /**
         * Get the value of pesDesId
         */ 
        public function getPesDesId()
        {
                return $this->pesDesId;
        }

        /**
         * Set the value of pesDesId
         *
         * @return  self
         */ 
        public function setPesDesId($pesDesId)
        {
                $this->pesDesId = $pesDesId;

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
         * Get the value of despesa
         */ 
        public function getDespesa()
        {
                return $this->despesa;
        }

        /**
         * Set the value of despesa
         *
         * @return  self
         */ 
        public function setDespesa($despesa)
        {
                $this->despesa = $despesa;

                return $this;
        }

        /**
         * Get the value of pesDesVal
         */ 
        public function getPesDesVal()
        {
                return $this->pesDesVal;
        }

        /**
         * Set the value of pesDesVal
         *
         * @return  self
         */ 
        public function setPesDesVal($pesDesVal)
        {
                $this->pesDesVal = $pesDesVal;

                return $this;
        }

        /**
         * Get the value of pesDesSituacao
         */ 
        public function getPesDesSituacao()
        {
                return $this->pesDesSituacao;
        }

        /**
         * Set the value of pesDesSituacao
         *
         * @return  self
         */ 
        public function setPesDesSituacao($pesDesSituacao)
        {
                $this->pesDesSituacao = $pesDesSituacao;

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