<?php

    class ModFechtoPessoa {
        
        private $fecDespId;
        private $fechto;
        private $pessoa;
        private $fecDespValorOrig;
        private $fecDespConsCredFunc;
        private $fecDespValorFinal;
        private $fecDespGerCredFunc;
        private $fecDespValorPago;
        private $dtaInc;
        private $dtaAlt;
        private $usrInc;
        private $usrAlt;
        private $despesas;

        public function __construct($fecDespId=0, $fechto="", $pessoa="", $fecDespValorOrig=0, $fecDespConsCredFunc=0,
                                    $fecDespValorFinal=0, $fecDespGerCredFunc=0, $fecDespValorPago=0, $dtaInc="", 
                                    $dtaAlt="", $usrInc=0, $usrAlt=0, $despesas=""){
            $this->fecDespId           = $fecDespId;
            $this->fechto              = $fechto;
            $this->pessoa              = $pessoa;
            $this->fecDespValorOrig    = $fecDespValorOrig;
            $this->fecDespConsCredFunc = $fecDespConsCredFunc;
            $this->fecDespValorFinal   = $fecDespValorFinal;
            $this->fecDespGerCredFunc  = $fecDespGerCredFunc;
            $this->fecDespValorPago    = $fecDespValorPago;
            $this->dtaInc              = $dtaInc;
            $this->dtaAlt              = $dtaAlt;
            $this->usrInc              = $usrInc;
            $this->usrAlt              = $usrAlt;
            $this->despesas            = $despesas;
        }

        public function retornaArray(){
                return get_object_vars($this);
        }

        /**
         * Get the value of fecDespId
         */ 
        public function getFecDespId()
        {
                return $this->fecDespId;
        }

        /**
         * Set the value of fecDespId
         *
         * @return  self
         */ 
        public function setFecDespId($fecDespId)
        {
                $this->fecDespId = $fecDespId;

                return $this;
        }

        /**
         * Get the value of fechto
         */ 
        public function getFechto()
        {
                return $this->fechto;
        }

        /**
         * Set the value of fechto
         *
         * @return  self
         */ 
        public function setFechto($fechto)
        {
                $this->fechto = $fechto;

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
         * Get the value of fecDespValorOrig
         */ 
        public function getFecDespValorOrig()
        {
                return $this->fecDespValorOrig;
        }

        /**
         * Set the value of fecDespValorOrig
         *
         * @return  self
         */ 
        public function setFecDespValorOrig($fecDespValorOrig)
        {
                $this->fecDespValorOrig = $fecDespValorOrig;

                return $this;
        }

        /**
         * Get the value of fecDespConsCredFunc
         */ 
        public function getFecDespConsCredFunc()
        {
                return $this->fecDespConsCredFunc;
        }

        /**
         * Set the value of fecDespConsCredFunc
         *
         * @return  self
         */ 
        public function setFecDespConsCredFunc($fecDespConsCredFunc)
        {
                $this->fecDespConsCredFunc = $fecDespConsCredFunc;

                return $this;
        }

        /**
         * Get the value of fecDespValorFinal
         */ 
        public function getFecDespValorFinal()
        {
                return $this->fecDespValorFinal;
        }

        /**
         * Set the value of fecDespValorFinal
         *
         * @return  self
         */ 
        public function setFecDespValorFinal($fecDespValorFinal)
        {
                $this->fecDespValorFinal = $fecDespValorFinal;

                return $this;
        }

        /**
         * Get the value of fecDespGerCredFunc
         */ 
        public function getFecDespGerCredFunc()
        {
                return $this->fecDespGerCredFunc;
        }

        /**
         * Set the value of fecDespGerCredFunc
         *
         * @return  self
         */ 
        public function setFecDespGerCredFunc($fecDespGerCredFunc)
        {
                $this->fecDespGerCredFunc = $fecDespGerCredFunc;

                return $this;
        }

        /**
         * Get the value of fecDespValorPago
         */ 
        public function getFecDespValorPago()
        {
                return $this->fecDespValorPago;
        }

        /**
         * Set the value of fecDespValorPago
         *
         * @return  self
         */ 
        public function setFecDespValorPago($fecDespValorPago)
        {
                $this->fecDespValorPago = $fecDespValorPago;

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
         * Get the value of despesas
         */ 
        public function getDespesas()
        {
                return $this->despesas;
        }

        /**
         * Set the value of despesas
         *
         * @return  self
         */ 
        public function setDespesas($despesas)
        {
                $this->despesas = $despesas;

                return $this;
        }
    }

?>