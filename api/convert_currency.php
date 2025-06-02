<?php
// api/convert_currency.php
require '../includes/currency_functions.php';

header('Content-Type: application/json');

$amount = floatval($_GET['amount'] ?? 0);
$from = $_GET['from'] ?? 'USD';
$to = $_GET['to'] ?? 'EUR';

if ($amount <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Montant invalide']);
    exit;
}

$convertedAmount = convertCurrency($amount, $from, $to);

echo json_encode([
    'amount' => $amount,
    'from' => $from,
    'to' => $to,
    'convertedAmount' => $convertedAmount,
    'formattedResult' => formatCurrency($convertedAmount, $to)
]);