<?php
//setting header to json
header('Content-Type: application/json');



$data = array(123,122,34,234,321,222,99,86);

echo json_encode($data);