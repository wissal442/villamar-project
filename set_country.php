<?php
session_start();
require 'includes/functions.php';

if (isset($_GET['country'])) {
    $countries = getCountries();
    if (array_key_exists($_GET['country'], $countries)) {
        $_SESSION['country'] = $countries[$_GET['country']];
        $_SESSION['country_code'] = $_GET['country'];
    }
}

header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
exit;