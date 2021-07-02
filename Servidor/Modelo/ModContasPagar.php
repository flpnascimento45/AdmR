<?php

    class ModContasPagar {

        private $pagarId;
        private $pessoa;
        private $despesa;
        private $pagarSituacao;
        private $pagarDtVencto;
        private $pagarDtPagto;
        private $pagarDtDocto;
        private $pagarValor;
        private $pagarValorPago;
        private $pagarNumDoc;
        private $pagarSeq;
        private $pagarObs;
        private $dtaInc;
        private $dtaAlt;
        private $usrInc;
        private $usrAlt;

        public function __construct($pagarId=0,$pessoa="",$despesa="",$pagarSituacao="",$pagarDtVencto="",$pagarDtPagto="",$pagarDtDocto="",$pagarValorPago=0,$pagarValor=0,$pagarNumDoc="",$pagarSeq=0,$pagarObs="",$dtaInc="",$dtaAlt="",$usrInc=0,$usrAlt=0) {  
            $this->pagarId        = $pagarId;
            $this->pessoa         = $pessoa;
            $this->despesa        = $despesa;
            $this->pagarSituacao  = $pagarSituacao;
            $this->pagarDtVencto  = $pagarDtVencto;
            $this->pagarDtPagto   = $pagarDtPagto;
            $this->pagarDtDocto   = $pagarDtDocto;
            $this->pagarValor     = $pagarValor;
            $this->pagarValorPago = $pagarValorPago;
            $this->pagarNumDoc    = $pagarNumDoc;
            $this->pagarSeq       = $pagarSeq;
            $this->pagarObs       = $pagarObs;
            $this->dtaInc         = $dtaInc;
            $this->dtaAlt         = $dtaAlt;
            $this->usrInc         = $usrInc;
            $this->usrAlt         = $usrAlt;
        }

        public function retornaArray(){
            return get_object_vars($this);
        }


        /**
         * Get the value of pagarId
         */ 
        public function getPagarId()
        {
                return $this->pagarId;
        }

        /**
         * Set the value of pagarId
         *
         * @return  self
         */ 
        public function setPagarId($pagarId)
        {
                $this->pagarId = $pagarId;

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
         * Get the value of pagarSituacao
         */ 
        public function getPagarSituacao()
        {
                return $this->pagarSituacao;
        }

        /**
         * Set the value of pagarSituacao
         *
         * @return  self
         */ 
        public function setPagarSituacao($pagarSituacao)
        {
                $this->pagarSituacao = $pagarSituacao;

                return $this;
        }

        /**
         * Get the value of pagarDtVencto
         */ 
        public function getPagarDtVencto()
        {
                return $this->pagarDtVencto;
        }

        /**
         * Set the value of pagarDtVencto
         *
         * @return  self
         */ 
        public function setPagarDtVencto($pagarDtVencto)
        {
                $this->pagarDtVencto = $pagarDtVencto;

                return $this;
        }

        /**
         * Get the value of pagarDtPagto
         */ 
        public function getPagarDtPagto()
        {
                return $this->pagarDtPagto;
        }

        /**
         * Set the value of pagarDtPagto
         *
         * @return  self
         */ 
        public function setPagarDtPagto($pagarDtPagto)
        {
                $this->pagarDtPagto = $pagarDtPagto;

                return $this;
        }

        /**
         * Get the value of pagarDtDocto
         */ 
        public function getPagarDtDocto()
        {
                return $this->pagarDtDocto;
        }

        /**
         * Set the value of pagarDtDocto
         *
         * @return  self
         */ 
        public function setPagarDtDocto($pagarDtDocto)
        {
                $this->pagarDtDocto = $pagarDtDocto;

                return $this;
        }

        /**
         * Get the value of pagarNumDoc
         */ 
        public function getPagarNumDoc()
        {
                return $this->pagarNumDoc;
        }

        /**
         * Set the value of pagarNumDoc
         *
         * @return  self
         */ 
        public function setPagarNumDoc($pagarNumDoc)
        {
                $this->pagarNumDoc = $pagarNumDoc;

                return $this;
        }

        /**
         * Get the value of pagarSeq
         */ 
        public function getPagarSeq()
        {
                return $this->pagarSeq;
        }

        /**
         * Set the value of pagarSeq
         *
         * @return  self
         */ 
        public function setPagarSeq($pagarSeq)
        {
                $this->pagarSeq = $pagarSeq;

                return $this;
        }

        /**
         * Get the value of pagarObs
         */ 
        public function getPagarObs()
        {
                return $this->pagarObs;
        }

        /**
         * Set the value of pagarObs
         *
         * @return  self
         */ 
        public function setPagarObs($pagarObs)
        {
                $this->pagarObs = $pagarObs;

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
         * Get the value of pagarValor
         */ 
        public function getPagarValor()
        {
                return $this->pagarValor;
        }

        /**
         * Set the value of pagarValor
         *
         * @return  self
         */ 
        public function setPagarValor($pagarValor)
        {
                $this->pagarValor = $pagarValor;

                return $this;
        }

        /**
         * Get the value of pagarValorPago
         */ 
        public function getPagarValorPago()
        {
                return $this->pagarValorPago;
        }

        /**
         * Set the value of pagarValorPago
         *
         * @return  self
         */ 
        public function setPagarValorPago($pagarValorPago)
        {
                $this->pagarValorPago = $pagarValorPago;

                return $this;
        }
    }

?>