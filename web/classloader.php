<?php
    session_start();
    
    class ClassLoader {
        public static function load() {
            require '/utils/Jsonify.php';
            require '/utils/ResponseMessage.php';

            require '/models/Endereco.php';
            require '/models/Teste.php';
        
            require '/dao/Dao.php';

            require '/dao/impl/EnderecoDao.php';
        
            require '/services/EnderecoService.php';
        }
    }
?>