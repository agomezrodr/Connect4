<?php // index.php

// File: new/index.php
require_once '../common/constants.php';
require_once '../common/utils.php';
require_once '../play/Game.php';
require_once '../play/Board.php';

//define('STRATEGY', 'strategy'); // constant

$response = array ();

// Fetch the strategy
$strategy = isset ( $_GET ['strategy'] ) ? $_GET ['strategy'] : "";

//$strategy = 'Smart';

//if there's no strategy assigned
if (empty ($strategy)) {
    $response = array (
        "response" => false,
        "message" => "Strategy has not been specified");
    //exit();
} else {
    $pid = uniqid();
    $response = array(
        "response" => true,
        "pid" => $pid
    );
    
    //create a new board
    $file = new Board();
    $file->buildEmpty();
    //store it
    $file->writePID($strategy, $pid);
    
}

echo json_encode($response);

?>