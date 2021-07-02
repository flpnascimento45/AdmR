<?php

    class ModPessoa {

        private $pessoaId;
        private $pessoaFantasia;
        private $pessoaRazao;
        private $pessoaTelefone;
        private $pessoaCelular;
        private $pessoaCpfCnpj;
        private $pessoaSituacao;
        private $pessoaFun;
        private $pessoaFor;
        private $pessoaPsv;
        private $pessoaObs;
        private $dtaInc;
        private $dtaAlt;
        private $usrInc;
        private $usrAlt;
        private $pessoaValCredito;
        private $pessoaDespesas;

        public function __construct($pessoaId=0,$pessoaFantasia="",$pessoaRazao="",$pessoaTelefone="",$pessoaCelular="",$pessoaCpfCnpj="",$pessoaSituacao="",$pessoaFun="",$pessoaFor="",$pessoaPsv="",$pessoaObs="",$dtaInc="",$dtaAlt="",$usrInc=0,$usrAlt=0,$pessoaValCredito=0) {  
            $this->pessoaId         = $pessoaId;
            $this->pessoaFantasia   = $pessoaFantasia;
            $this->pessoaRazao      = $pessoaRazao;
            $this->pessoaTelefone   = $pessoaTelefone;
            $this->pessoaCelular    = $pessoaCelular;
            $this->pessoaCpfCnpj    = $pessoaCpfCnpj;
            $this->pessoaSituacao   = $pessoaSituacao;
            $this->pessoaFun        = $pessoaFun;
            $this->pessoaFor        = $pessoaFor;
            $this->pessoaPsv        = $pessoaPsv;
            $this->pessoaObs        = $pessoaObs;
            $this->dtaInc           = $dtaInc;
            $this->dtaAlt           = $dtaAlt;
            $this->usrInc           = $usrInc;
            $this->usrAlt           = $usrAlt;
            $this->pessoaValCredito = $pessoaValCredito;
        }

        public function retornaArray(){
            return get_object_vars($this);
        }

        /**
         * Get the value of pessoaId
         */ 
        public function getPessoaId()
        {
                return $this->pessoaId;
        }

        /**
         * Set the value of pessoaId
         *
         * @return  self
         */ 
        public function setPessoaId($pessoaId)
        {
                $this->pessoaId = $pessoaId;

                return $this;
        }

        /**
         * Get the value of pessoaFantasia
         */ 
        public function getPessoaFantasia()
        {
                return $this->pessoaFantasia;
        }

        /**
         * Set the value of pessoaFantasia
         *
         * @return  self
         */ 
        public function setPessoaFantasia($pessoaFantasia)
        {
                $this->pessoaFantasia = $pessoaFantasia;

                return $this;
        }

        /**
         * Get the value of pessoaRazao
         */ 
        public function getPessoaRazao()
        {
                return $this->pessoaRazao;
        }

        /**
         * Set the value of pessoaRazao
         *
         * @return  self
         */ 
        public function setPessoaRazao($pessoaRazao)
        {
                $this->pessoaRazao = $pessoaRazao;

                return $this;
        }

        /**
         * Get the value of pessoaTelefone
         */ 
        public function getPessoaTelefone()
        {
                return $this->pessoaTelefone;
        }

        /**
         * Set the value of pessoaTelefone
         *
         * @return  self
         */ 
        public function setPessoaTelefone($pessoaTelefone)
        {
                $this->pessoaTelefone = $pessoaTelefone;

                return $this;
        }

        /**
         * Get the value of pessoaCelular
         */ 
        public function getPessoaCelular()
        {
                return $this->pessoaCelular;
        }

        /**
         * Set the value of pessoaCelular
         *
         * @return  self
         */ 
        public function setPessoaCelular($pessoaCelular)
        {
                $this->pessoaCelular = $pessoaCelular;

                return $this;
        }

        /**
         * Get the value of pessoaCpfCnpj
         */ 
        public function getPessoaCpfCnpj()
        {
                return $this->pessoaCpfCnpj;
        }

        /**
         * Set the value of pessoaCpfCnpj
         *
         * @return  self
         */ 
        public function setPessoaCpfCnpj($pessoaCpfCnpj)
        {
                $this->pessoaCpfCnpj = $pessoaCpfCnpj;

                return $this;
        }

        /**
         * Get the value of pessoaSituacao
         */ 
        public function getPessoaSituacao()
        {
                return $this->pessoaSituacao;
        }

        /**
         * Set the value of pessoaSituacao
         *
         * @return  self
         */ 
        public function setPessoaSituacao($pessoaSituacao)
        {
                $this->pessoaSituacao = $pessoaSituacao;

                return $this;
        }

        /**
         * Get the value of pessoaFun
         */ 
        public function getPessoaFun()
        {
                return $this->pessoaFun;
        }

        /**
         * Set the value of pessoaFun
         *
         * @return  self
         */ 
        public function setPessoaFun($pessoaFun)
        {
                $this->pessoaFun = $pessoaFun;

                return $this;
        }

        /**
         * Get the value of pessoaFor
         */ 
        public function getPessoaFor()
        {
                return $this->pessoaFor;
        }

        /**
         * Set the value of pessoaFor
         *
         * @return  self
         */ 
        public function setPessoaFor($pessoaFor)
        {
                $this->pessoaFor = $pessoaFor;

                return $this;
        }

        /**
         * Get the value of pessoaPsv
         */ 
        public function getPessoaPsv()
        {
                return $this->pessoaPsv;
        }

        /**
         * Set the value of pessoaPsv
         *
         * @return  self
         */ 
        public function setPessoaPsv($pessoaPsv)
        {
                $this->pessoaPsv = $pessoaPsv;

                return $this;
        }

        /**
         * Get the value of pessoaObs
         */ 
        public function getPessoaObs()
        {
                return $this->pessoaObs;
        }

        /**
         * Set the value of pessoaObs
         *
         * @return  self
         */ 
        public function setPessoaObs($pessoaObs)
        {
                $this->pessoaObs = $pessoaObs;

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
         * Get the value of pessoaDespesas
         */ 
        public function getPessoaDespesas()
        {
                return $this->pessoaDespesas;
        }

        /**
         * Set the value of pessoaDespesas
         *
         * @return  self
         */ 
        public function setPessoaDespesas($pessoaDespesas)
        {
                $this->pessoaDespesas = $pessoaDespesas;

                return $this;
        }

        /**
         * Get the value of pessoaValCredito
         */ 
        public function getPessoaValCredito()
        {
                return $this->pessoaValCredito;
        }

        /**
         * Set the value of pessoaValCredito
         *
         * @return  self
         */ 
        public function setPessoaValCredito($pessoaValCredito)
        {
                $this->pessoaValCredito = $pessoaValCredito;

                return $this;
        }
    }

?>