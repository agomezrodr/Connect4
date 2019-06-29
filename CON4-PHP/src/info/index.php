<?php // index.php
//echo '{"width":7,"height":6,"strategies":["Smart","Random"]}';

require_once '/common/constants.php';

// create a structure: a class or an array (of key-value pairs)
$output = array (
    "width" => 6,
    "height" => 7,
    "strategies" => array("Smart", "Random")
);
echo json_encode ( $output );


?>
