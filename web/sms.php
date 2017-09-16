<?php
$postData = @file_get_contents('php://input');
file_put_contents('/tmp/sms.txt', var_export($postData, true).PHP_EOL, 8);