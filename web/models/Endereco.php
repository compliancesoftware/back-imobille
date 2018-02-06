<?php
    class Endereco {
        private $id = null;
        private $endereco = '';
        private $bairro = '';
        private $cidade = '';
        private $estado = '';
        private $complemento = '';
        private $numero = '';
		private $cep = '';
		private $referencias = '';
		private $latitude = '';
		private $longitude = '';

		public function getId() {
			return $this->id;
		}
		public function setId($id) {
			$this->id = $id;
		}
		public function getEndereco() {
			return $this->endereco;
		}
		public function setEndereco($endereco) {
			$this->endereco = $endereco;
		}
		public function getBairro() {
			return $this->bairro;
		}
		public function setBairro($bairro) {
			$this->bairro = $bairro;
		}
		public function getCidade() {
			return $this->cidade;
		}
		public function setCidade($cidade) {
			$this->cidade = $cidade;
		}
		public function getEstado() {
			return $this->estado;
		}
		public function setEstado($estado) {
			$this->estado = $estado;
		}
		public function getComplemento() {
			return $this->complemento;
		}
		public function setComplemento($complemento) {
			$this->complemento = $complemento;
		}
		public function getNumero() {
			return $this->numero;
		}
		public function setNumero($numero) {
			$this->numero = $numero;
		}
		public function getCep() {
			return $this->cep;
		}
		public function setCep($cep) {
			$this->cep = $cep;
		}
		public function getReferencias() {
			return $this->referencias;
		}
		public function setReferencias($referencias) {
			$this->referencias = $referencias;
		}
		public function getLatitude() {
			return $this->latitude;
		}
		public function setLatitude($latitude) {
			$this->latitude = $latitude;
		}
		public function getLongitude() {
			return $this->longitude;
		}
		public function setLongitude($longitude) {
			$this->longitude = $longitude;
		}
        
        public function serialize() {
            $str = json_encode($this->read());
            $str = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
                return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
			}, $str);

            return $str;
        }

        public function read() {
            return get_object_vars($this);
        }

        public function entityName() {
            return 'endereco';
        }
    }
?>
