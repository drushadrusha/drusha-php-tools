<?php

//  drusha php framework

/**
 * Sends async GET request to the given URL.
 *
 * @param String $request - URL to send request to.
 */ 
function asyncGetRequest($request){
    exec('bash -c "wget -O '.$request.' > /dev/null 2>&1 &"');
    return;
}

// str_contains polyfill
if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle) {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }
}

/**
 * Send message to Telegram
*/ 
function sendTelegramMessage($message, $token, $userid, $async = false){
    $url = "https://api.telegram.org/bot".$token."/sendMessage?chat_id=".$userid."&text=".$message;
    if($async){
        asyncGetRequest($url);
    } else {
        file_get_contents($url);
    }
    return;
}

function json_headers(){
    header('Content-type:application/json;charset=utf-8');
}

function createPDOConnection($user, $password, $dbname, $host = 'localhost', $charset = 'utf8'){
    $dsn = "mysql:host=".$host.";dbname=".$dbname.";charset=".$charset;

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    return new PDO($dsn, $user, $password, $options);
}

function colorLog($str, $type = 'p'){
    $time = date ('d.m H:i',time());
    switch ($type) {
        case 'e': //error
            echo $time." \033[31m$str \033[0m\n";
        break;
        case 's': //success
            echo $time." \033[32m$str \033[0m\n";
        break;
        case 'w': //warning
            echo $time." \033[33m$str \033[0m\n";
        break;  
        case 'i': //info
            echo $time." \033[36m$str \033[0m\n";
        break;     
        case 'p': //plain
            echo $time." $str\n"; 
        default:
        # code...
        break;
    }
}

/**
 * Write $message to log.txt file.
 * @param String $message message to write.
 */ 
function writeToLog($message, $tag = '', $filename = 'log.txt'){
    $time = date ('d.m H:i',time());
    $log = fopen($filename, 'a');
    if($tag != ''){
        fwrite($log, $time." [".$tag."] ".$message."\n");
    } else {
        fwrite($log, $time." ".$message."\n");
    }
    fclose($log);
}

/**
 * Enables PHP Error reporting
 */ 
function enableErrorReporting(){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

?>