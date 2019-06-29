<?php

abstract class RandomStrategy extends moveStrategy{
    //Create the random strategy
    public static function selectColumn($board, $userMove){
        //Check which of the col is not full
        $grid = $board->grid;
        $freeColumns = array();
        for($col = 0; $col < Board::$WIDTH; $col ++){
            if ($grid[0][$col] == Board::$EMPTY_SLOT){
                array_push($freeColumns, $col);
            }
        }
        //Choose any of the col that are not full
        return array_rand($freeColumns, 1);
    }
    public function toString(){
        return ("Random Strategy");
    }
}

?>