<?php
    class Jsonify{
        public static function arrayToJson($arr) {
            $sampleObj = $arr[0];
            $className = get_class($sampleObj);
            $listName = strtolower($className).'_list';

            $json_string = '{"'.$listName.'":[';
            $len = count($arr);
            for($i = 0;$i < $len;$i++) {
                $json_string = $json_string.''.$arr[$i]->serialize();
                if($i != ($len - 1)) {
                    $json_string = $json_string.',';
                }
            }
            $json_string = $json_string.']}';

            return $json_string;
        }

    }
?>