<?php


include __DIR__."/config/config.php";
include __DIR__."/config/variables.php";
include __DIR__."/functions/bot.php";
include __DIR__."/functions/functions.php";
include __DIR__."/functions/db.php";


date_default_timezone_set($config['timeZone']);


////Modules
include __DIR__."/modules/admin.php";
include __DIR__."/modules/skcheck.php";
include __DIR__."/modules/binlookup.php";
include __DIR__."/modules/iban.php";
include __DIR__."/modules/stats.php";
include __DIR__."/modules/me.php";
include __DIR__."/modules/apikey.php";


include __DIR__."/modules/checker/auth.php";
include __DIR__."/modules/checker/mauth.php";
include __DIR__."/modules/checker/ex.php";
include __DIR__."/modules/checker/mass.php";
include __DIR__."/modules/checker/smass.php";
include __DIR__."/modules/checker/amass.php";
include __DIR__."/modules/checker/schk.php";
include __DIR__."/modules/checker/chk.php";
include __DIR__."/modules/checker/chk1.php";
include __DIR__."/modules/checker/chk2.php";
include __DIR__."/modules/checker/chk5.php";


#====================Start==========================
if (strpos($message, "/start") === 0) {
    if (!isBanned($userId) && !isMuted($userId)) {

        if ($userId == $config['adminID']) {
            $messagesec = "<b>Type /admin to know admin commands</b>";
        }

        addUser($userId,$username);
        bot('sendmessage', [
            'chat_id' => $chat_id,
            'text' => "<b>Hello @$username,

Type /cmds to know all my commands!</b>

$messagesec",
            'parse_mode' => 'html',
            'reply_to_message_id' => $message_id,
            'reply_markup' => json_encode(['inline_keyboard' => [
                [
                    ['text' => "💠 Owner💠", 'url' => "t.me/binnerbhaiya"]
                ],
                [],
            ], 'resize_keyboard' => true])

        ]);
    }
}

//////////////===[CMDS]===//////////////

if(strpos($message, "/cmds") === 0 || strpos($message, "!cmds") === 0){

  if(!isBanned($userId) && !isMuted($userId)){
    bot('sendmessage',[
    'chat_id'=>$chat_id,
    'text'=>"<b>Which commands would you like to check?</b>",
    'parse_mode'=>'html',
    'reply_to_message_id'=> $message_id,
    'reply_markup'=>json_encode(['inline_keyboard'=>[
    [['text'=>"💳 CC Checker Gates",'callback_data'=>"checkergates"]],[['text'=>"🛠 Other Commands",'callback_data'=>"othercmds"]],
    ],'resize_keyboard'=>true])
    ]);
  }
  
  }
if(strpos($message, "/api") === 0 || strpos($message, "!api") === 0){

  if(!isBanned($userId) && !isMuted($userId)){
    $sec = fetchAPIKey($userId);
      if ($sec == 0){
    bot('sendmessage',[
    'chat_id'=>$chat_id,
    'text'=>"<b>Give sk first</b>",
    'parse_mode'=>'html',
    'reply_to_message_id'=> $message_id
    ]);
        } else{
           bot('sendmessage',[
          'chat_id'=>$chat_id,
          'text'=>"<b>Your sk was:$sec</b>",
          'parse_mode'=>'html',
          'reply_to_message_id'=> $message_id
          ]);
          
       }
  }
  
  }
  if($data == "back"){
    bot('editMessageText',[
    'chat_id'=>$callbackchatid,
    'message_id'=>$callbackmessageid,
    'text'=>"<b>Which commands would you like to check?</b>",
    'parse_mode'=>'html',
    'reply_markup'=>json_encode(['inline_keyboard'=>[
    [['text'=>"💳 CC Checker Gates",'callback_data'=>"checkergates"]],[['text'=>"🛠 Other Commands",'callback_data'=>"othercmds"]],
    ],'resize_keyboard'=>true])
    ]);
  }
  
  if($data == "checkergates"){
    bot('editMessageText',[
    'chat_id'=>$callbackchatid,
    'message_id'=>$callbackmessageid,
    'text'=>"<b>━━CC Checker Gates━━</b>
  
<b>/chk | !chk - Stripe Charge 1$</b>
<b>/chk1 | !chk1 - Stripe charge 1$ [Site based]</b>
<b>/ex | !ex - Extrap of any cc</b>
<b>/mass | !mass - Mass cc User Stripe Merchant </b>
<b>/amass | !amass - Mass cc for Owner only </b>
<b>/smass | !smass - Self sk Mass cc Checker </b>
<b>/schk | !schk - self sk cc gate of Stripe Merchant </b>
<b>/auth | !auth - Auth gate of 20$[Site based] </b>
<b>/sk | .sk - Check your sk key </b>

<b>/apikey sk_live_xxx - Add SK Key for /schk gate</b>
<b>/myapikey | !myapikey - View the added SK Key for /schk gate</b>

<b>Bot Made by <a href='t.me/+6T-YczOeDyYzYWNk'>Team Black Hat</a></b>
<b>ϟ Join <a href='t.me/+6T-YczOeDyYzYWNk</a></b>",
    'parse_mode'=>'html',
    'disable_web_page_preview'=>true,
    'reply_markup'=>json_encode(['inline_keyboard'=>[
  [['text'=>"Return",'callback_data'=>"back"]]
  ],'resize_keyboard'=>true])
  ]);
  }
  
  
  if($data == "othercmds"){
    bot('editMessageText',[
    'chat_id'=>$callbackchatid,
    'message_id'=>$callbackmessageid,
    'text'=>"<b>━━Other Commands━━</b>
  
<b>/me | !me</b> - Your Info
<b>/stats | !stats</b> - Checker Stats
<b>/sk | !key</b> - SK Key Checker
<b>/api | !api</b> - Check your input sk
<b>/bin | !bin</b> - Bin Lookup
<b>/iban | !iban</b> - IBAN Checker
  
  <b>ϟ Join <a href='t.me/+6T-YczOeDyYzYWNk'>Team Black Hat</a></b>",
    'parse_mode'=>'html',
    'disable_web_page_preview'=>true,
    'reply_markup'=>json_encode(['inline_keyboard'=>[
  [['text'=>"Return",'callback_data'=>"back"]]
  ],'resize_keyboard'=>true])
  ]);
  }

?>
