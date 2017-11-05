<?php

//Select Postgresql 
$host = 'ec2-184-73-247-240.compute-1.amazonaws.com';
$dbname = 'd4mud3p0dor7f7';
$user = 'tovcgvofemgthd';
$pass = '6a0fcc3d6d520632627446b07d5b296f2ee1417b4677fe13838a7a764596bf0e';
$connection = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
$result = $connection->query("SELECT * FROM polls");
if($result !== null) {
echo $result->rowCount();
}

//Insert Postgresql
$params = array(
'user_id' => $event['source']['userId'] ,
'slip_date' => date('Y-m-d'),
'name' => $event['message']['text'],
);
$statement = $connection->prepare('INSERT INTO slips (user_id, slip_date, name) VALUES (:user_id,
:slip_date, :name)');
$statement->execute($params);

//Update Postgresql
$params = array(
'name' => $event['message']['text'],
'slip_date' => date('Y-m-d'),
'user_id' => $event['source']['userId'],
);
$statement = $connection->prepare('UPDATE slips SET name=:name WHERE slip_date=:slip_date AND
user_id=:user_id');
$statement->execute($params);


require_once('./vendor/autoload.php');

//Namespace
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot;
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;

$channel_token = '3/f1DPclnJCB4LG6Yrn0l9K0FqvE+9Bz1HQwsgnO/3CPSPH7bV2EeJGdF6AqtNOCUSwXt+J3U6IGmRgP3tb7lB0yGCirTfT5MbNfzwczSrtlkGt/n5ZfuXTyPqlpRk9c9X2uZWrCVH7Dm3uim/Ap4QdB04t89/1O/w1cDnyilFU=';
$channel_secret = '66758f62f9b2bdacb0b63611748bf681';

//Get message from Line API
$content = file_get_contents('php://input');
$events = json_decode($content, true);

if ($event['type'] == 'message') {
switch($event['message']['type']) {
case 'text':

if (!is_null($events['events'])) {
// Loop through each event
foreach ($events['events'] as $event) {
// Line API send a lot of event type, we interested in message only.
if ($event['type'] == 'message') {
switch($event['message']['type']) {
case 'text':
// Get replyToken
$replyToken = $event['replyToken'];
// Reply message
$respMessage = 'Hello, your message is '. $event['message']['text'];
$httpClient = new CurlHTTPClient($channel_token);
$bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret));
$textMessageBuilder = new TextMessageBuilder($respMessage);
$response = $bot->replyMessage($replyToken, $textMessageBuilder);
break;
}
}
}
}

echo "OK";