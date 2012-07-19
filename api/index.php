<?php

require '../init.php';
header('Content-type: application/json');
header('Access-Control-Allow-Origin: ' . BASE_URL);

echo json_encode(IndexController::api_route());
