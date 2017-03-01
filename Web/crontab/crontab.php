<?php
/**
 *
 * 频次，每5分钟调一次
 */
ini_set("display_errors","on");
error_reporting(7);


define('MODE_NAME', 'cli');
define('BIND_MODULE','Home');
define('BIND_CONTROLLER','Crontab');
define('BIND_ACTION','push');

//define('APP_PATH', '../../Web/');
//define('RUNTIME_PATH', '../');
require '../../ThinkPHP/ThinkPHP.php';