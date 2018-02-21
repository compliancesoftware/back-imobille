<?php
    class HttpRequest {
        public static function hasUTF8Entry() {
            return (strpos($_SERVER['CONTENT_TYPE'],'charset=UTF-8') !== false);
        }
        public static function getRequestData() {
            if(HttpRequest::hasUTF8Entry()) {
                return json_decode(file_get_contents('php://input'), true);
            }
            else {
                return null;
            }
        }
    }
?>