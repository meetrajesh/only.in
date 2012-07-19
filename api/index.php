<?php

require '../init.php';
header('Content-type: application/json');

echo json_encode(IndexController::api_route());
