<?php
/**
 * Use for return easy answer.
 */

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
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {

            // Get replyToken
            $replyToken = $event['replyToken'];

            switch($event['message']['text']) {
                
                case 'tel':
                    $respMessage = '089-5124512';
                    break;
                case 'address':
                    $respMessage = '99/451 Muang Nonthaburi';
                    break;
                case 'boss':
                    $respMessage = '089-2541545';
                    break;
                case 'idcard':
                    $respMessage = '5845122451245';
                    break;
                default:
                    break;
            }

                    // Split message then keep it in database. 
                    $appointments = explode(',', $event['message']['text']);

                    if(count($appointments) == 2) {

                        $host = 'ec2-174-129-223-193.compute-1.amazonaws.com';
                        $dbname = 'd74bjtc28mea5m';
                        $user = 'eozuwfnzmgflmu';
                        $pass = '2340614a293db8e8a8c02753cd5932cdee45ab90bfcc19d0d306754984cbece1';
                        $connection = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass); 
                        
                        $params = array(
                            'time' => $appointments[0],
                            'content' => $appointments[1],
                        );
            
                        $statement = $connection->prepare("INSERT INTO appointments (time, content) VALUES (:time, :content)");
                        $result = $statement->execute($params);
            
                        $respMessage = 'Your appointment has saved.';
                    }else{
                        $respMessage = 'You can send appointment like this "12.00,House keeping." ';
                    }


            $httpClient = new CurlHTTPClient($channel_token);
            $bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret));

            $textMessageBuilder = new TextMessageBuilder($respMessage);
            $response = $bot->replyMessage($replyToken, $textMessageBuilder);

		}
	}
}

echo "OK";
