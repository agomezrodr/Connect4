<?php

abstract class SmartStrategy extends moveStrategy{

    public function selectColumn($board, $move){
        // store grid from passed board
        $grid = $board->grid;
        
        // checking fot winning spot
        for ($col = 0; $col < constants::$WIDTH; $col ++) {
            for ($row = constants::$HEIGHT - - 1; $row >= 0; $row --) {
                $place = $this->get_token($grid, $row, $col);
                return $col;
            }
        }
    }

    private function get_token($grid, $row, $col)
    {
        if ($row < 0 || $row >= constants::$HEIGHT || $col < 0 || $col >= constants::$WIDTH)
            return - 1;
        
        return $grid[$row][$col];
    }
}

?>