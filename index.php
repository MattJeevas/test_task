<?php
// Для проверки работы API использовал cURL, так как он позволяет легко формировать POST запросы.
function executeCurl($url, $payload){
	$ch = curl_init( $url );
	// Вытаскиваем метод из адреса.
	$method = explode('/', explode("//", $url)[1])[2];
	$payload['Method'] = $method;
	$payload = json_encode( $payload );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	$result = curl_exec($ch);
	$error = curl_error($ch);
	curl_close($ch);
	echo "<pre>$result</pre>";
}
executeCurl("http://localhost/api/Table", array( "table" => "Session" ));
executeCurl("http://localhost/api/SessionSubscribe", array( "userEmail" => "a@a.com", "sessionId" => 2 ));
?>