<?php
    class Teste {
        private $mensagem = '';

		public function getMensagem() {
			return $this->mensagem;
		}
		public function setMensagem($mensagem) {
			$this->mensagem = $mensagem;
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
            return 'teste';
        }
    }
?>
