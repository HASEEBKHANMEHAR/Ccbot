<?php


include_once __DIR__."/../functions/bot.php";
include __DIR__."/../config/variables.php";
include __DIR__."/../config/config.php";

///////////==[DB Connection]==///////////
$conn = mysqli_connect($config['db']['hostname'],$config['db']['username'],$config['db']['password'],$config['db']['database']);

if(!$conn){
    bot('sendmessage',[
        'chat_id'=>$config['adminID'],
        'text'=>"<b>🛑 DB connection Failed!
        ".json_encode($config['db'])."</b>",
        'parse_mode'=>'html'
    ]);

    logsummary("<b>🛑 DB connection Failed!\n\n".json_encode($config['db'])."</b>");
}

////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////
function fetchUser($userId){
    global $conn;
    $dataf = mysqli_query($conn,"SELECT * FROM users WHERE userid='$userId'");

    if(mysqli_num_rows($dataf) == 0){
        return False;
    }

    $userData = $dataf->fetch_assoc();
    
    return $userData;

}

function isBanned($userId){
    global $chat_id;
    global $message_id;
    $userData = fetchUser($userId);

    if($userData['is_banned'] == "True"){
        bot('sendmessage',[
            'chat_id'=>$chat_id,
            'text'=>"<b>Hehe Boi! Suck your Mum</b>",
        'parse_mode'=>'html',
        'reply_to_message_id'=> $message_id
        ]);
        return True;
    }else{
        return False;
    }

}

function isMuted($userId){
    global $chat_id;
    global $message_id;
    global $conn;
    $userData = fetchUser($userId);

    if($userData['is_muted'] == "True"){
        $muted_for = $userData['mute_timer']-time();

        if($muted_for >= 0){
        bot('sendmessage',[
            'chat_id'=>$chat_id,
            'text'=>"<b>🛑You are Muted!
            
Try Again after <code>".date("F j, Y, g:i a",$userData['mute_timer'])."</code></b>",
        'parse_mode'=>'html',
        'reply_to_message_id'=> $message_id
        ]);
        return True;
        }else{
            mysqli_query($conn,"UPDATE users SET is_muted = 'False',mute_timer = '0' WHERE userid = '$userId'");
            return False;
        }
    }else{
        return False;
    }

}

function addUser($userId, $username){
    global $conn;
    $userData = fetchUser($userId);

    if(!$userData){
        $addtodb = mysqli_query($conn,"INSERT INTO users (userid, username, registered_on, is_banned, is_muted, mute_timer, sk_key, total_checked, total_cvv, total_ccn, subscription_status) VALUES ('$userId', '$username', '".time()."', 'False', 'False', '0', '0', '0', '0', '0', 'free')");
        logsummary("<b>🛑 [LOG] New User - $userId , $username</b>");
        return True;
    }else{
        return False;
    }
}



function muteUser($userId,$time){
    global $conn;
    $userData = fetchUser($userId);

    if(!$userData){
        return "Uhmm, This user isn't in my db!";
    }else{
        $muteuser = mysqli_query($conn,"UPDATE users SET is_muted = 'True',mute_timer = '$time' WHERE userid = '$userId'");
        logsummary("<b>🛑 [LOG] Muted $userId</b>");
        return "Successfully Muted <code>$userId</code> until <code>".date("F j, Y, g:i a",$time)."</code>";
    }

}

function unmuteUser($userId){
    global $conn;
    $userData = fetchUser($userId);

    if(!$userData){
        return "Uhmm, This user isn't in my db!";
    }else{
        $muteuser = mysqli_query($conn,"UPDATE users SET is_muted = 'False',mute_timer = '0' WHERE userid = '$userId'");
        logsummary("<b>🛑 [LOG] Unmuted $userId</b>");
        return "Successfully Unmuted $userId";
    }

}

function banUser($userId){
    global $conn;
    $userData = fetchUser($userId);

    if(!$userData){
        return "Uhmm, This user isn't in my db!";
    }else{
        $muteuser = mysqli_query($conn,"UPDATE users SET is_banned = 'True' WHERE userid = '$userId'");
        logsummary("<b>🛑 [LOG] Banned $userId</b>");
        return "Successfully Banned <code>$userId</code>";
    }

}

function unbanUser($userId){
    global $conn;
    $userData = fetchUser($userId);

    if(!$userData){
        return "Uhmm, This user isn't in my db!";
    }else{
        $muteuser = mysqli_query($conn,"UPDATE users SET is_banned = 'False' WHERE userid = '$userId'");
        
        logsummary("<b>🛑 [LOG] Unbanned $userId</b>");

        return "Successfully Unbanned <code>$userId</code>";

        
    }

}

function fetchMutelist(){
    global $conn;

    $data = mysqli_query($conn,"SELECT userid FROM users WHERE is_muted = 'True'");
    if(mysqli_num_rows($data) == 0){
        return False;
    }

    $data = $data->fetch_assoc();
    return $data;
}

function fetchMuteTimer($userId){
    global $conn;

    $data = mysqli_query($conn,"SELECT mute_timer FROM users WHERE userid = '$userId'");
    if(mysqli_num_rows($data) == 0){
        return False;
    }

    $data = $data->fetch_assoc();
    return $data;
}

function fetchBanlist(){
    global $conn;

    $data = mysqli_query($conn,"SELECT userid FROM users WHERE is_banned = 'True'");
    if(mysqli_num_rows($data) == 0){
        return False;
    }

    $data = $data->fetch_assoc();
    return $data;
}


function totalBanned(){
    global $conn;

    $data = mysqli_query($conn,"SELECT * FROM users WHERE (is_banned = 'True')");
    return mysqli_num_rows($data);

}

function totalMuted(){
    global $conn;

    $data = mysqli_query($conn,"SELECT * FROM users WHERE (is_muted = 'True')");
    return mysqli_num_rows($data);

}


///////===[ANTI-SPAM]===///////

function existsLastChecked($userId){
    global $conn;
    $dataf = mysqli_query($conn,"SELECT * FROM antispam WHERE userid='$userId'");

    if(mysqli_num_rows($dataf) == 0){
        return False;
    }

    $userData = $dataf->fetch_assoc();
    
    return $userData['last_checked_on'];

}

function antispamCheck($userId){
    global $conn;
    global $config;

    $antiSpamGey = existsLastChecked($userId);
    
    if($userId == $config['adminID']){
        return False;
    }
    if($antiSpamGey == False){
        $addtodb = mysqli_query($conn,"INSERT INTO antispam (userid,last_checked_on) VALUES ('$userId','".time()."')");
        return False;
    }else{
        if(time() - $antiSpamGey > $config['anti_spam_timer']){
            $addtodb = mysqli_query($conn,"UPDATE antispam set last_checked_on = '".time()."' WHERE userid = '$userId'");
            return False;
        }else{
            return $config['anti_spam_timer'] - (time() - $antiSpamGey);
        }
        
    }

}

///////===[CHECKER STATS]===///////

function fetchGlobalStats(){
    global $conn;
    $stats = mysqli_query($conn,"SELECT * FROM global_checker_stats");
    $stats = $stats->fetch_assoc();
    return $stats;

}

function addTotal(){
    global $conn;
    mysqli_query($conn,"UPDATE global_checker_stats SET total_checked = total_checked + 1");

}

function addCVV(){
    global $conn;
    mysqli_query($conn,"UPDATE global_checker_stats SET total_cvv = total_cvv + 1");

}

function addCCN(){
    global $conn;
    mysqli_query($conn,"UPDATE global_checker_stats SET total_ccn = total_ccn + 1");

}


function fetchUserStats($userId){
    global $conn;
    $stats = mysqli_query($conn,"SELECT total_checked,total_cvv,total_ccn FROM users WHERE userid = '$userId'");
    $stats = $stats->fetch_assoc();
    return $stats;

}

function addUserTotal($userId){
    global $conn;
    mysqli_query($conn,"UPDATE users SET total_checked = total_checked + 1 WHERE userid = '$userId'");

}

function addUserCVV($userId){
    global $conn;
    mysqli_query($conn,"UPDATE users SET total_cvv = total_cvv + 1 WHERE userid = '$userId'");

}

function addUserCCN($userId){
    global $conn;
    mysqli_query($conn,"UPDATE users SET total_ccn = total_ccn + 1 WHERE userid = '$userId'");

}

///////===[API KEY]===///////

function fetchAPIKey($userId){
    global $conn;
    $key = mysqli_query($conn,"SELECT sk_key FROM users WHERE userid = '$userId'");
    $key = $key->fetch_assoc();
    return $key['sk_key'];

}

function updateAPIKey($userId,$apikey){
    global $conn;
    mysqli_query($conn,"UPDATE users SET sk_key = '$apikey' WHERE userid = '$userId'");

}


?>