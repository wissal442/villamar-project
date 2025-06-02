// includes/currency_functions.php
<?php
function convertCurrency($amount, $from, $to) {
    $rates = [
        'USD' => ['EUR' => 0.85, 'MAD' => 9.5, 'CAD' => 1.25],
        'EUR' => ['USD' => 1.18, 'MAD' => 11.2, 'CAD' => 1.47],
        'MAD' => ['USD' => 0.11, 'EUR' => 0.089, 'CAD' => 0.13],
        'CAD' => ['USD' => 0.80, 'EUR' => 0.68, 'MAD' => 7.6]
    ];
    
    if ($from === $to) return $amount;
    return $amount * ($rates[$from][$to] ?? 1);
}

function formatCurrency($amount, $currencyCode) {
    $formats = [
        'USD' => '$%.2f',
        'EUR' => '%.2f€',
        'MAD' => '%.2f DH',
        'CAD' => '$%.2f CAD'
    ];
    
    return sprintf($formats[$currencyCode] ?? '%.2f', $amount);
}