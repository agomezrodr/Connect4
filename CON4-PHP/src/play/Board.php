<?php
require_once '../common/constants.php';


class Board
{
    
    private $num_tokens = 0;
    
    // keep track of how many tokens have been placed
    public $grid = array();
    
    // to store/make the board
    public $winGame = FALSE;
    
    //store coordinates of potential win path and keep updating as you play
    public $play_path = array();
    
    //build an empty board
    private static $EMPTY = "";
    public static function buildEmpty() {
        if (empty ( self::$EMPTY ))
            self::$EMPTY = new self ();
            
            return self::$EMPTY;
    }
    
    //build new board
    function __construct()
    {
        for ($row = 0; $row < constants::$HEIGHT; $row ++)
            for ($col = 0; $col < constants::$WIDTH; $col ++)
                $this->grid[$row][$col] = constants::$EMPTY_SLOT;
    }
    
    // Store info to pid to save
    function writePID($strategy, $pid)
    {
        $info = array(
            "strategy" => $strategy,
            "grid" => $this->grid,
            "play path" => $this->play_path
        );
        
        // put this info into a folder for later access
        
        $file_path = constants::$SAVED_G . $pid . '.txt';
        fopen($file_path, "w+");
        file_put_contents($file_path, json_encode($info));
        
        
        //echo file_exists($file_path);
        
        //echo realpath($file_path);
        //         echo $file_path;
        
        //         echo getcwd();
    }
    
    // from a stored PID... Access stored data
    function storedPID($pid)
    {
        $saved = json_decode(file_get_contents(constants::$SAVED_G . "$pid.txt"), true);
        // New instance to restore game
        $instance = new self();
        
        // Build Board from saved data
        $instance->grid = $saved["grid"];
        
        return $instance;
    }
    
    //get top coord
    function get_top($column){
        
        for ($row = constants::$HEIGHT - 1; $row >= 0; $row --) {
            // Is the slot empty?
            if ($this->grid[$row][$column] == constants::$EMPTY_SLOT) {
                // if empty, the is the highest slot available
                $top = $row;
                break;
            }
        }
        return $top;
    }
    
    // update board once a valid token has been chosen
    function place_token($column, $token)
    {
        // check what is the highest row to make sure it is not full
        $top = $this->get_top($column);
        
        // if placement is valid, update board and keep track of number of tokens
        $this->grid[$top][$column] = $token;
        $this->num_tokens ++;
        
        // check if there isn't a draw once the token has been placed
        // can the game continue or did no one win?
        // Size of board = HEIGHT * WIDTH
        if ($this->num_tokens == constants::$HEIGHT * constants::$WIDTH)
            return constants::$IS_DRAW;
            // check adjacent slots to see if:
            // 1. it is filled with the same player
            // 2. if has been a full 4 connect (only need to check three each direction)
            
            for ($dir = 1; $dir <= 8; $dir ++) {
                $this->winGame = $this->win_check ($top,$column);
                //if game has been won / could be won
                if($this->winGame){
                    $temp_path = $this->play_path;
                    //store the winning path in the temp variabled
                    array_push($temp_path, $column, $top);
                    $this->play_path = $temp_path;
                    return constants::$WIN;
                }
            }
            
            return true;
    }
    
    //function to check if there is a win
    function win_check($row, $column){
        //make a temp array to store the possible win path
        if($this->horizontalLine($row,$column) || $this->verticalLine($row,$column)){
            return true;
        }
        return false;
    }
    
    //function to check if there is a horizontal line win
    function horizontalLine($row, $column){
        $board = $this->grid;
        $count = 0;   //count how many tokens are together for the player
        $place = $this->grid[$row][$column];  //get the current location
        
        //count left side
        for($i = $column; $i >= 0; $i ++){
            if($board[$row][$i] !== $place){
                break;
            }
            $count ++;
        }
        
        //count right side
        for($i = $column + 1; $i < $this->get_top($column); $i++){
            if($board[$row][$i] !== $place){
                break;
            }
            $count++;
        }
        if ($count >= 4){
            return true;
        }
        return false;
    }
    
    //check to see if there is a vertical line win
    function verticalLine($row,$column){
        $board = $this->grid;
        $place = $board[$row][$column];
        
        if ( $row >= (constants::$HEIGHT)-3 ) {
            
            return false;
            
        }
        
        for($i = $row + 1; $i <= $row + 3; $i++){
            if($board[$i][$column] != $place){
                return false;
            }
        }
        return true;
    }
    
    // function checkPlaces($x, $y, $dx, $dy, $player)
    // {
    // // expand to left/lower: $x - $dx, $y - $dy …
    // // expand to right/higher: $x + $dy, $y + $dy …
    // }
}

?>