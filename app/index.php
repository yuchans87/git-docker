<?php
$log_file = '/var/log/payload/test.log';
$secret = '7Aar9SRcV6PKy';

$deploy = 'dev';

if (!function_exists('getallheaders'))
{
        function getallheaders()
        {
                     $headers = '';
             foreach ($_SERVER as $name => $value)
             {
                     if (substr($name, 0, 5) == 'HTTP_')
                     {
                             $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                     }
             }
             return $headers;
        }
}

// Get Header and Github-Signature(Secret)
$allheaders = getallheaders();
$hubsignature = $allheaders["X-Hub-Signature"];

// Split signature
list($algo, $hash) = explode('=', $hubsignature, 2);

// Get payload
$json = file_get_contents('php://input');

// Decode payload
$params = json_decode($json, true);

// Split branch
$arr_refs = explode('/', $params["ref"], 3);

// Calc hash
$jsonhash = hash_hmac($algo, $json, $secret);

// Check and output
if ($hash !== $jsonhash) {
  // Invalid
  file_put_contents($log_file, date("[Y-m-d H:i:s]") . "\n" . "invalid access not match secret" . "\n" , FILE_APPEND);
} else {
  if ($arr_refs[2] == $deploy) {
  // Output example code
  file_put_contents($log_file, date("[Y-m-d H:i:s]") . "\n" . "OK dev branch" . "\n" . $params["ref"] . " : " . $params["head_commit"]["message"] . "\n" , FILE_APPEND);
  } else {
    file_put_contents($log_file, date("[Y-m-d H:i:s]") . "\n" . "not dev branch" . "\n" . $params["ref"] . " : " . $params["head_commit"]["message"] . "\n" , FILE_APPEND);
  }
}

?>