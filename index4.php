<?php

require_once('./vendor/autoload.php');
date_default_timezone_set('Asia/Bangkok');

use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot;
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;

$channel_token = '3/f1DPclnJCB4LG6Yrn0l9K0FqvE+9Bz1HQwsgnO/3CPSPH7bV2EeJGdF6AqtNOCUSwXt+J3U6IGmRgP3tb7lB0yGCirTfT5MbNfzwczSrtlkGt/n5ZfuXTyPqlpRk9c9X2uZWrCVH7Dm3uim/Ap4QdB04t89/1O/w1cDnyilFU=';
$channel_secret = '66758f62f9b2bdacb0b63611748bf681';

// Get message from Line API
$content = file_get_contents('php://input');
$events = json_decode($content, true);

if (!is_null($events['events'])) {

	// Loop through each event
	foreach ($events['events'] as $event) {
    
        // Line API send a lot of event type, we interested in message only.
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {

            // Get replyToken
            $replyToken = $event['replyToken'];

            $host = 'ec2-184-73-247-240.compute-1.amazonaws.com';
            $dbname = 'd4mud3p0dor7f7';
            $user = 'tovcgvofemgthd';
            $pass = '6a0fcc3d6d520632627446b07d5b296f2ee1417b4677fe13838a7a764596bf0e';
            $connection = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass); 
            
            $params = array(
                'log' => $event['message']['text'],
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
            );

            $statement = $connection->prepare("INSERT INTO logs (log,date,time) VALUES (:log, :date, :time)");
            $result = $statement->execute($params);

            if($result){
                $respMessage = 'Log:'.$event['message']['text'].' Success';
            }else{
                $respMessage = 'Log:'.$event['message']['text'].' Fail';
            }
            
            $httpClient = new CurlHTTPClient($channel_token);
            $bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret));

            $textMessageBuilder = new TextMessageBuilder($respMessage);
            $response = $bot->replyMessage($replyToken, $textMessageBuilder);
 
		}
	}
}

echo "OK";
