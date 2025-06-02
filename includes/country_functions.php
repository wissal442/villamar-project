<?php
// includes/country_functions.php

function getCountries() {
    return [
        'MA' => ['name' => 'Maroc', 'currency' => 'DH'],
        'FR' => ['name' => 'France', 'currency' => '€'],
        'BE' => ['name' => 'Belgique', 'currency' => '€'],
        'CA' => ['name' => 'Canada', 'currency' => '$'],
        'US' => ['name' => 'États-Unis', 'currency' => '$'],
         
        'TN' => ['name' => 'Tunisie', 'currency' => 'TND'],
        'EG' => ['name' => 'Égypte', 'currency' => 'EGP'],
        'SA' => ['name' => 'Arabie Saoudite', 'currency' => 'SAR'],
        'AE' => ['name' => 'Émirats Arabes Unis', 'currency' => 'AED'],
        'QA' => ['name' => 'Qatar', 'currency' => 'QAR'],
        'KW' => ['name' => 'Koweït', 'currency' => 'KWD'],
        'OM' => ['name' => 'Oman', 'currency' => 'OMR'],
        'BH' => ['name' => 'Bahreïn', 'currency' => 'BHD'],
        'JO' => ['name' => 'Jordanie', 'currency' => 'JOD'],
        'LB' => ['name' => 'Liban', 'currency' => 'LBP'],
        'IQ' => ['name' => 'Irak', 'currency' => 'IQD'],
     

    ];
}

function getCountryName($code) {
    $countries = getCountries();
    return $countries[$code]['name'] ?? 'Inconnu';
}

function getCountryCurrency($code) {
    $countries = getCountries();
    return $countries[$code]['currency'] ?? '';
}

function setUserCountry($countryCode) {
    if (array_key_exists($countryCode, getCountries())) {
        $_SESSION['country_code'] = $countryCode;
        $_SESSION['country_name'] = getCountryName($countryCode);
        $_SESSION['currency'] = getCountryCurrency($countryCode);
        
        if (isset($_SESSION['user_id'])) {
            global $pdo;
            $stmt = $pdo->prepare("UPDATE users SET country_code = ? WHERE id = ?");
            $stmt->execute([$countryCode, $_SESSION['user_id']]);
        }
        return true;
    }
    return false;
}