<?php

include __DIR__."/../config/config.php";
include_once __DIR__."/../functions/bot.php";

function capture($string, $start, $end)
{
    $str = explode($start, $string);
    $str = explode($end, $str[1]);
    return $str[0];
}

function logsummary($summary)
{
    global $config;
    bot('sendmessage', [
        'chat_id' => $config['logsID'],
        'text' => $summary,
        'parse_mode' => 'html'
    ]);
}
function GetStr($string, $start, $end){
$str = explode($start, $string);
$str = explode($end, $str[1]);  
return $str[0];
};
function add_days($timestamp, $days)
{
    $future = $timestamp + (60 * 60 * 24 * str_replace('d', '', $days));
    return $future;
}

function add_minutes($timestamp, $minutes)
{
    $future = $timestamp + (60 * str_replace('m', '', $minutes));
    return $future;
}

function multiexplode($delimiters, $string)
{
    $one = str_replace($delimiters, $delimiters[0], $string);
    $two = explode($delimiters[0], $one);
    return $two;
}

function array_in_string($str, array $arr)
{
    foreach ($arr as $arr_value) {
        if (stripos($str, $arr_value) !== false) return true;
    }
    return false;
}


// Check if the request was successful and the SK Key is live
function getBalance($sk)
{
    // Use the Stripe API to fetch the balance
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/balance');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERPWD, $sk. ':' . '');
    $headers = array();
    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);

    // Check if the request was successful and the SK Key is live
    if ($info['http_code'] === 200) {
        $balanceData = json_decode($result, true);

        // Extract the available amount
        $availableAmount = 0;
        if (isset($balanceData['available']) && is_array($balanceData['available'])) {
            foreach ($balanceData['available'] as $balance) {
                if (isset($balance['amount'])) {
                    $availableAmount += $balance['amount'];
                }
            }
        }
        $availableFormatted = number_format($availableAmount / 100, 2); // Convert to decimal currency format

        // Extract the pending amount
        $pendingAmount = 0;
        if (isset($balanceData['pending']) && is_array($balanceData['pending'])) {
            foreach ($balanceData['pending'] as $balance) {
                if (isset($balance['amount'])) {
                    $pendingAmount += $balance['amount'];
                }
            }
        }
        $pendingFormatted = number_format($pendingAmount / 100, 2); // Convert to decimal currency format

        // Extract the country and currency from the balanceData
        $currency = $balanceData['available'][0]['currency'];

        // Return the balance details along with the country and currency
        return [
            'available' => $availableFormatted,
            'pending' => $pendingFormatted,
            'currency' => $currency
        ];
    }

    // Return false if the SK Key is not live or if there was an error fetching the balance or account info
    return false;
}

?>
