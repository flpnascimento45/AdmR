<?php

    class ModUsuario {

        private $usrId;
        private $usrLogin;
        private $usrEmail;
        private $usrSenha;
        private $usrSituacao;
        private $dtaInc;
        private $dtaAlt;
        private $usrInc;
        private $usrAlt;
        private $acessos;
        private $chaveSessao;

        public function __construct($usrId=0,$usrLogin="",$usrEmail="",$usrSenha="",$usrSituacao="",$dtaInc="",$dtaAlt="",$usrInc=0,$usrAlt=0) {  
            $this->usrId       = $usrId;
            $this->usrLogin    = $usrLogin;
            $this->usrEmail    = $usrEmail;
            $this->usrSenha    = $usrSenha;
            $this->usrSituacao = $usrSituacao;
            $this->dtaInc      = $dtaInc;
            $this->dtaAlt      = $dtaAlt;
            $this->usrInc      = $usrInc;
            $this->usrAlt      = $usrAlt;
        }

        public function retornaArray(){
            return get_object_vars($this);
        }

        /**
         * Get the value of usrId
         */ 
        public function getUsrId()
        {
                return $this->usrId;
        }

        /**
         * Set the value of usrId
         *
         * @return  self
         */ 
        public function setUsrId($usrId)
        {
                $this->usrId = $usrId;

                return $this;
        }

        /**
         * Get the value of usrLogin
         */ 
        public function getUsrLogin()
        {
                return $this->usrLogin;
        }

        /**
         * Set the value of usrLogin
         *
         * @return  self
         */ 
        public function setUsrLogin($usrLogin)
        {
                $this->usrLogin = $usrLogin;

                return $this;
        }

        /**
         * Get the value of usrEmail
         */ 
        public function getUsrEmail()
        {
                return $this->usrEmail;
        }

        /**
         * Set the value of usrEmail
         *
         * @return  self
         */ 
        public function setUsrEmail($usrEmail)
        {
                $this->usrEmail = $usrEmail;

                return $this;
        }

        /**
         * Get the value of usrSenha
         */ 
        public function getUsrSenha()
        {
                return $this->usrSenha;
        }

        /**
         * Set the value of usrSenha
         *
         * @return  self
         */ 
        public function setUsrSenha($usrSenha)
        {
                $this->usrSenha = $usrSenha;

                return $this;
        }

        /**
         * Get the value of usrSituacao
         */ 
        public function getUsrSituacao()
        {
                return $this->usrSituacao;
        }

        /**
         * Set the value of usrSituacao
         *
         * @return  self
         */ 
        public function setUsrSituacao($usrSituacao)
        {
                $this->usrSituacao = $usrSituacao;

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
         * Get the value of acessos
         */ 
        public function getAcessos()
        {
                return $this->acessos;
        }

        /**
         * Set the value of acessos
         * @return self
         */ 
        public function setAcessos($acessos)
        {
                $this->acessos = $acessos;

                return $this;
        }

        /**
         * Get the value of chaveSessao
         */ 
        public function getChaveSessao()
        {
                return $this->chaveSessao;
        }

        /**
         * Set the value of chaveSessao
         *
         * @return  self
         */ 
        public function setChaveSessao($chaveSessao)
        {
                $this->chaveSessao = $chaveSessao;

                return $this;
        }

    }

?>