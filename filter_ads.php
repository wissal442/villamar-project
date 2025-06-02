<?php
session_start();
require 'includes/functions.php';

if (isset($_GET['country'])) {
    $countries = getCountries();
    if ($_GET['country'] === '' || array_key_exists($_GET['country'], $countries)) {
        $_SESSION['country_code'] = $_GET['country'] ?: null;
        $_SESSION['country'] = $_GET['country'] ? $countries[$_GET['country']] : null;
    }
}

header('Location: list.php');
exit;