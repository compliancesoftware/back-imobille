<?php
    class EnderecoService{
        private $enderecoDao = null;

        public function __construct() {
            $this->enderecoDao = new EnderecoDao();
        }

        public function getEnderecoById($id) {
            return $this->enderecoDao->getEnderecoById($id);
        }

        public function listEnderecos() {
            return $this->enderecoDao->listEnderecos($id);
        }

        public function createEndereco($endereco) {
            return $this->enderecoDao->createEndereco($endereco);
        }

        public function updateEndereco($endereco) {
            return $this->enderecoDao->updateEndereco($endereco);
        }

    }
    
?>