<?php
    class EnderecoDao extends Dao{
        public function __construct() {
            parent::__construct();
        }

        public function getEnderecoById($id) {
            $endereco = new Endereco();
            return parent::getById($id, $endereco);
        }

        public function listEnderecos() {
            $endereco = new Endereco();
            $enderecos = parent::retrieve($endereco);

            return $enderecos;
        }

        public function createEndereco($endereco) {
            return parent::persist($endereco);
        }

        public function updateEndereco($endereco) {
            return parent::update($endereco);
        }
    }
?>