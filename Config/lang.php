<?php
require_once "global_path.php";
$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'ar';
$_SESSION['lang'] = $lang;
// Security: allow only supported languages
$allowed = ['en', 'ar'];
if (!in_array($lang, $allowed)) {
    $lang = 'ar';
}

$translations = require BASE_PATH . "/Lang/$lang.php";

function __($key)
{
    global $translations;
    return $translations[$key] ?? $key;
}