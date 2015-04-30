<?php
$LOG_FILE = '/var/log/payload/hook.log';
// $secret = '7Aar9SRcV6PKy';
$secret = 'error';

$headers = getallheaders();
$hubSignature = $headers['X-Hub-Signature'];

list($algo, $hash) = explode('=', $hubSignature, 2);

$payload = file_get_contents('php://input');
$params = json_decode($payload, true);

$payloadHash = hash_hmac($algo, $payload, $secret);

if ( $hash !== $payloadHash ) {
  file_put_contents($LOG_FILE, date("[Y-m-d H:i:s]")." invalid access: ".$_SERVER['REMOTE_ADDR']."\n", FILE_APPEND|LOCK_EX);
} else {
  file_put_contents($LOG_FILE, date("[Y-m-d H:i:s]")." OK access: ".$params['head_commit']['message']." ".$_SERVER['REMOTE_ADDR']."\n", FILE_APPEND|LOCK_EX);
}
?>