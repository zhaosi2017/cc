<?php
$postData = @file_get_contents('php://input');
$postData = json_decode($postData, true);
file_put_contents('/tmp/event.txt', var_export($postData, true).PHP_EOL, 8);
