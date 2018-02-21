<?php
    class Usuario {
        private $id = null;
		private $nome = '';
		private $email = '';
		private $senha = '';
		private $telefone = '';
		private $permissao = '';
		private $ativo = false;
		private $foto = '';
        private $criadoEm = '';
		private $ultimoAcesso = '';
		private $endereco = null;

		public function getId() {
			return $this->id;
		}
		public function setId($id) {
			$this->id = $id;
		}
		public function getNome() {
			return $this->nome;
		}
		public function setNome($nome) {
			$this->nome = $nome;
		}
		public function getEmail() {
			return $this->email;
		}
		public function setEmail($email) {
			$this->email = $email;
		}
		public function getSenha() {
			return $this->senha;
		}
		public function setSenha($senha) {
			$this->senha = $senha;
		}
		public function getTelefone() {
			return $this->telefone;
		}
		public function setTelefone($telefone) {
			$this->telefone = $telefone;
		}
		public function getPermissao() {
			return $this->permissao;
		}
		public function setPermissao($permissao) {
			$this->permissao = $permissao;
		}
		public function getAtivo() {
			return $this->ativo;
		}
		public function setAtivo($ativo) {
			$this->ativo = $ativo;
		}
		public function getFoto() {
			return $this->foto;
		}
		public function setFoto($foto) {
			$this->foto = $foto;
		}
		public function getCriadoEm() {
			return $this->criadoEm;
		}
		public function setCriadoEm($criadoEm) {
			$this->criadoEm = $criadoEm;
		}
		public function getUltimoAcesso() {
			return $this->ultimoAcesso;
		}
		public function setUltimoAcesso($ultimoAcesso) {
			$this->ultimoAcesso = $ultimoAcesso;
		}
        public function getEndereco() {
			return $this->endereco;
		}
		public function setEndereco($endereco) {
			$this->endereco = $endereco;
		}

        public function serialize() {
            $str = json_encode($this->read());
            $str = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
                return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
			}, $str);

			if($this->endereco != null) {
                $str = str_replace('"endereco":{}','"endereco":'.$this->endereco->serialize(),$str);
			}
			
            return $str;
        }

        public function read() {
            return get_object_vars($this);
        }

        public function entityName() {
            return 'usuario';
        }
    }
?>
