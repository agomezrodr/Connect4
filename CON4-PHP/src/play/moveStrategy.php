<?php

//  require_once ("constants.php");
require_once '../play/SmartStrategy.php';
require_once '../play/RandomStrategy.php';

abstract class moveStrategy{
    
    //public static $STRATEGIES = array("Smart", "Random");
    
    public static function fromPID($pid){
        
        
        $saved = json_decode(file_get_contents(Constants::$SAVED_G . "$pid.txt"), true);
        
        if (strtolower($saved["strategy"]) == "smart") {
            return SmartStrategy();
        } else {
            return RandomStrategy();
        }
    }
}




?>