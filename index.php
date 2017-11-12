<?php
require_once('./vendor/autoload.php');
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
if ($event['type'] == 'message') {
// Get replyToken
$replyToken = $event['replyToken'];
switch($event['message']['type']) {
case 'sticker':
$messageID = $event['message']['packageId'].":".$event['message']['stickerId'];
// Reply message
$respMessage = 'Hello, your Sticker Package ID:Sticker ID is '. $messageID;
break;
default:
// Reply message
$respMessage = 'Please send Sticker only';
break;
}
$httpClient = new CurlHTTPClient($channel_token);
$bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret));

$textMessageBuilder = new TextMessageBuilder($respMessage);
$response = $bot->replyMessage($replyToken, $textMessageBuilder);
}
}
}
echo "OK";