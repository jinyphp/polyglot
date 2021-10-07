<?php

require '../../../../vendor/autoload.php';

$msg = polyglot();
$msg->enableTrans("transkey.php");

$str = "오류가 발생하였습니다.";
echo $msg->echo($str,"en");

