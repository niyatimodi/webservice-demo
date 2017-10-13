<?php
//rajan@2305

function sendNotificationToIOS($message,$deviceToken,$notificationCount,$senderId,$msgcontent,$msgType)
{
	//echo $message."--".$deviceToken.'!!'.$notificationCount."##".$senderId."--".$notificationType;die;
	//$deviceToken = "DF3EB1D0A8CEBDEA12B5A82247DF5CDA009FA4B6069C234F187260AAE22C64BA";
	$passphrase = 'iverve';
	//	$passphrase = 'Niraj@123';
	// Put your alert message here:
	//$message = 'You have a message...';
	$ctx = stream_context_create();
	stream_context_set_option($ctx, 'ssl', 'local_cert', '/home/catracker/admin/certificate/DevPushServices.pem');
	stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

	// Open a connection to the APNS server
	$fp = stream_socket_client(
	//'ssl://gateway.push.apple.com:2195', $err,
	'ssl://gateway.sandbox.push.apple.com:2195', $err,
	$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

	 /*if (!$fp)
	 {
		exit("Failed to connect: $err $errstr" . PHP_EOL);
	 }
	 else
	 {
		 echo 'Connected to APNS' . PHP_EOL;
	 }
	
	
	die; */
	
	$msgName = $message.' has sent you a new message';
	$body['aps']= array(
	'alert'=> $msgName,
	'badge'=>$notificationCount,
	'senderid'=>$senderId,
	'msgcontent'=> $msgcontent,
	'msgtype'=> $msgType,
	);
//	echo "<pre>";
//	print_r($body); die;
	
	// Encode the payload as JSON
	$payload = json_encode($body);
	
	// Build the binary notification
	$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
	
	// Send it to the server
	$result = fwrite($fp, $msg, strlen($msg));
	
	/*if (!$result)
	echo 'Message not delivered' . PHP_EOL;
	else
	echo 'Message successfully delivered' . PHP_EOL;*/
	// Close the connection to the server
	fclose($fp);
	return;
	//header('Location: myschool2_apps.php?');
}
//------------------- Android ----------------------//
function sendNotificationToAndroid($message,$deviceToken,$notificationCount)
{
	// Replace with real BROWSER API key from Google APIs
	$apiKey = "AIzaSyAylteHj7pIBfqD3DW2xXGeRWrQRQsV2l0";
	//$apiKey = "AIzaSyCbdw0KaKv0v50Bo7vFBrxK2m3Cb3mqB40";
	
	// Replace with real client registration IDs
	$registrationIDs = array($deviceToken);
	
	// Message to be sent
	
	// Set POST variables
	$url = 'https://android.googleapis.com/gcm/send';
	
	$fields = array(
	'registration_ids' => $registrationIDs,
	'data' => array( "message" => $message ),
	);
	
	$headers = array(
	'Authorization: key=' . $apiKey,
	'Content-Type: application/json'
	);
	
	// Open connection
	$ch = curl_init();
	
	// Set the url, number of POST vars, POST data
	curl_setopt( $ch, CURLOPT_URL, $url );
	
	curl_setopt( $ch, CURLOPT_POST, true );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	
	curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );
	
	// Execute post
	$result = curl_exec($ch);
	
	// Close connection
	curl_close($ch);
	//return;
	//echo $result;
}

?>