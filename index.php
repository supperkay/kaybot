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
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {

            // Get replyToken
            $replyToken = $event['replyToken'];

            try {
                // Check to see user already answer
                $host = 'ec2-184-73-247-240.compute-1.amazonaws.com';
                $dbname = 'd4mud3p0dor7f7';
                $user = 'tovcgvofemgthd';
                $pass = '6a0fcc3d6d520632627446b07d5b296f2ee1417b4677fe13838a7a764596bf0e';
                $connection = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass); 
                
                $sql = sprintf("SELECT * FROM poll WHERE user_id='%s' ", $event['source']['userId']);
                $result = $connection->query($sql);

                error_log($sql);

                if($result == false || $result->rowCount() <=0) {
    
                    switch($event['message']['text']) {
                        
                        case '1':
                            // Insert
                            $params = array(
                                'userID' => $event['source']['userId'],
                                'answer' => '1',
                            );
                            
                            $statement = $connection->prepare('INSERT INTO poll ( user_id, answer ) VALUES ( :userID, :answer )');
                            $statement->execute($params);

                            // Query
                            $sql = sprintf("SELECT * FROM poll WHERE answer='1' AND  user_id='%s' ", $event['source']['userId']);
                            $result = $connection->query($sql);
                             
                            $amount = 1;
                            if($result){
                                $amount = $result->rowCount();
                            }
                            $respMessage = 'จำนวนคนตอบว่าเพื่อน = '.$amount;

                            break;
                        
                        case '2':
                            // Insert
                            $params = array(
                                'userID' => $event['source']['userId'],
                                'answer' => '2',
                            );
                            
                            $statement = $connection->prepare('INSERT INTO poll ( user_id, answer ) VALUES ( :userID, :answer )');
                            $statement->execute($params);

                            // Query
                            $sql = sprintf("SELECT * FROM poll WHERE answer='2' AND  user_id='%s' ", $event['source']['userId']);
                            $result = $connection->query($sql);

                            $amount = 1;
                            if($result){
                                $amount = $result->rowCount();
                            }
                            $respMessage = 'จำนวนคนตอบว่าแฟน = '.$amount;

                            break;
                        
                        case '3':
                            // Insert
                            $params = array(
                                'userID' => $event['source']['userId'],
                                'answer' => '3',
                            );
                            
                            $statement = $connection->prepare('INSERT INTO poll ( user_id, answer ) VALUES ( :userID, :answer )');
                            $statement->execute($params);

                            // Query
                            $sql = sprintf("SELECT * FROM poll WHERE answer='3' AND  user_id='%s' ", $event['source']['userId']);
                            $result = $connection->query($sql);

                            $amount = 1;
                            if($result){
                                $amount = $result->rowCount();
                            }
                            $respMessage = 'จำนวนคนตอบว่าพ่อแม่ = '.$amount;
    
                            break;
                        case '4':
                            // Insert
                            $params = array(
                                'userID' => $event['source']['userId'],
                                'answer' => '4',
                            );
                            
                            $statement = $connection->prepare('INSERT INTO poll ( user_id, answer ) VALUES ( :userID, :answer )');
                            $statement->execute($params);

                            // Query
                            $sql = sprintf("SELECT * FROM poll WHERE answer='4' AND  user_id='%s' ", $event['source']['userId']);
                            $result = $connection->query($sql);

                            $amount = 1;
                            if($result){
                                $amount = $result->rowCount();
                            }
                            $respMessage = 'จำนวนคนตอบว่าบุคคลอื่นๆ = '.$amount;

                            break;
                        default:
                            $respMessage = "
                                บุคคลที่โทรหาบ่อยที่สุด คือ? \n\r
                                กด 1 เพื่อน \n\r
                                กด 2 แฟน \n\r
                                กด 3 พ่อแม่ \n\r
                                กด 4 บุคคลอื่นๆ \n\r
                            ";
                            break;
                    }
    
                } else {
                    $respMessage = 'คุณได้ตอบโพลล์นี้แล้ว';
                }
    
                $httpClient = new CurlHTTPClient($channel_token);
                $bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret));
    
                $textMessageBuilder = new TextMessageBuilder($respMessage);
                $response = $bot->replyMessage($replyToken, $textMessageBuilder);

            } catch(Exception $e) {
                error_log($e->getMessage());
            }

		}
	}
}

echo "OK";
