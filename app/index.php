<?php
$LOG_FILE = '/var/log/payload/hook.log';
$SECRET_KEY = '7Aar9SRcV6PKy';

if ( isset($_GET['key'])&& $_GET['key'] === $SECRET_KEY )
{
  $json = file_get_contents('php://input');
  $params = json_decode($json, true);
  error_log(print_r($params, true), 3, '/var/log/payload/hook.log');
} else {
  file_put_contents($LOG_FILE, date("[Y-m-d H:i:s]")." invalid access: ".$_SERVER['REMOTE_ADDR']."\n", FILE_APPEND|LOCK_EX);
}
?>