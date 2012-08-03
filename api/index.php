<?php

require '../init.php';
ini_set('html_errors', false);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: ' . BASE_URL);

echo json_encode(IndexController::api_route());
