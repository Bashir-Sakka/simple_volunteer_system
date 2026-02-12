<?php
session_start();

require_once 'Config/lang.php';
require_once 'Components/navbar.php';
require_once 'Controller\BeneficiaryController.php';
require_once 'Controller\SupportController.php';
require_once 'Controller\ReasonController.php';
require_once 'Controller\DeleteController.php';
require_once 'Controller\FilteringController.php';

require_once 'Components/navbar.php';
require_once 'Components/navbar.php';
$pdo = require 'Config/db.php';
// Define the base path once


$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

// Normalize URI for the router
if (str_starts_with($uri, BASE_URL)) {
    $uri = substr($uri, strlen(BASE_URL));
}
if (empty($uri)) {
    $uri = '/';
}
$query = $_GET;
$routes = [
    '/' => 'Views/index.php',
    '/settings' => 'Views/settings.php',
    '/add_beneficiary' => 'Views/add_beneficiary.php',
    '/view_beneficiary' => 'Views/view_beneficiary.php',
    '/filtering' => 'Views/filtering.php'
];

if (array_key_exists($uri, $routes)) {

    require $routes[$uri];
} else {

}