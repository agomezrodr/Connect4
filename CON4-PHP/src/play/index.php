<?php
require_once '../play/Board.php';
require_once '../play/moveStrategy.php';
//require_once '/Users/stephanie/Documents/CON4/src/writable/';

// for stored Board
$board = new Board();

$pid = isset($_GET['PID']) ? $_GET['PID'] : " ";
#$pid = '5bbbed3246314';
$move = isset($_GET['move']) ? $_GET['move'] : " ";

// *************start of cheons code******************
// store the info from stored game
// $file = $board->storedPID($pid);

// $json = file_get_contents($file);
// $game = Game::fromJsonString($json);

// $playerMove = $game->makePlayerMove($x, $y);

// //if a stored game is already a win or a draw, unlink file
// if ($playerMove->isWin || $playerMove->isDraw) {
// unlink($file);
// exit();
// }

// $opponentMove = $game->makeOpponentMove();
// if ($opponentMove->isWin || $opponentMove->isDraw) {
// unlink($file);
// exit();
// }
// storeState($file, $game->toJsonString());

// **************end of cheons code*******************

// $move = is_numeric($move) ? intval($move) : $move;

// if pid has not been created, let user know
if (empty($pid))
    $response = array(
        "response" => false,
        "reason" => "Pid has not been given"
    );
    
    // if pid cannot be found
    else if (!file_exists(constants::$SAVED_G . "$pid.txt"))
        $response = array(
            "response" => false,
            "reason" => "Cannot find pid"
        );
        
        // if move has not been chosen
        else if ($move === "")
            $response = array(
                "response" => false,
                "reason" => "Move not specified"
            );
            
            // if move goes over the width
            else if ($move < 0 || $move >= constants::$WIDTH)
                $response = array(
                    "response" => false,
                    "reason" => "$move is not a valid slot."
                );
                
                // start building the board
                else {
                    $board-> storedPID($pid);
                    $result = $board->place_token($move, constants::$USER_T);
                    
                    // if board determined it was a good move..
                    if ($result == constants::$GOOD_MOVE) {
                        $moveStrategy = moveStrategy::fromPID($pid);
                        $col = $moveStrategy->chooseColumn($board, $move); // choose column from selected strategy
                        
                        $response = array(
                            "response" => true,
                            "move" => array(
                                "slot" => $move,
                                "isWin" => false,
                                "isDraw" => false,
                                "row" => array()
                            ),
                            "move" => machine_move($board, $col)
                        );
                        // Save
                        $board->writePID($moveStrategy->toString(), $pid);
                    } else {
                        $response = array(
                            "response" => true,
                            "move" => array(
                                "slot" => $move,
                                "isWin" => $result == constants::$IS_WIN,
                                "isDraw" => $result == constants::$IS_DRAW,
                                "row" => ($result == constants::$IS_WIN) ? $board->play_path : array()
                            )
                        );
                        
                        // Delete the game.
                        unlink(Constants::$SAVED_G . "$pid.txt");
                    }
                }
                echo json_encode($response);
                
                function machine_move($board, $col)
                {
                    $automaticResult = $board->place_token($col, Board::$MACHINE_TOKEN);
                    
                    return array(
                        "slot" => $col,
                        "isWin" => $automaticResult == constants::$IS_WIN,
                        "isDraw" => $automaticResult == constants::$IS_DRAW,
                        "row" => ($automaticResult == constants::$IS_WIN) ? $board->play_path : array()
                    );
                }
                
                ?>