<?php
$json = file_get_contents('php://input');
$params = json_decode($json, true);
error_log(print_r($params, true), 3, '/var/log/payload/hook.log');
?>