<?php

require 'merlin/RedisFactory.php';
require 'merlin/Session.php';
require 'merlin/Logger.php';
require 'merlin/User.php';
require 'merlin/UserManager.php';
require 'merlin/Crane.php';

define('REDIS_HISTORY', 'HISTORY');

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$redis = \merlin\RedisFactory::getInstance();
$session = \merlin\Session::getInstance();
$logger = \merlin\Logger::getInstance();
$user = \merlin\UserManager::getLoggedUser();
$crane = \merlin\Crane::getInstance();

if($user==null){
	echo "Need to be logged in to execute commands.";
	exit(0);
}

$uuid = isset($_REQUEST['uuid'])?$_REQUEST['uuid']:null;
$cmd = isset($_REQUEST['cmd'])?$_REQUEST['cmd']:null;

$instances = $redis->sMembers('INSTANCES:FROM:' . $user->getUsername() );
if($instances==null) $instances = array();

if( !$redis->sIsMember('INSTANCES:FROM:' . $user->getUsername(), $uuid) ){
	echo 'Error';
	exit(0);
}

$redis->rPush(REDIS_HISTORY, $user->getUsername() . "::$uuid::" . time() . "::" . $cmd);

$output = $crane->executeCommand($uuid, $cmd);
echo htmlspecialchars($output);
?>
