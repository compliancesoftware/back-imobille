<?php
    class ClassLoader {
        public static function load() {
            require '/utils/Jsonify.php';
            require '/utils/ResponseMessage.php';
            require '/utils/LoginSession.php';
            require '/utils/HttpRequest.php';

            require '/models/Teste.php';
            require '/models/Endereco.php';
            require '/models/Usuario.php';
        
            require '/dao/Dao.php';

            require '/dao/impl/EnderecoDao.php';
            require '/dao/impl/UsuarioDao.php';
        
            require '/services/EnderecoService.php';
            require '/services/UsuarioService.php';                     
        }
    }
?>