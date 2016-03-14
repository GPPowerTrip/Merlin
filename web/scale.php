<?php

require 'merlin/RedisFactory.php';
require 'merlin/Session.php';
require 'merlin/Logger.php';
require 'merlin/User.php';
require 'merlin/UserManager.php';
require 'merlin/Crane.php';

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$redis = \merlin\RedisFactory::getInstance();
$session = \merlin\Session::getInstance();
$logger = \merlin\Logger::getInstance();
$user = \merlin\UserManager::getLoggedUser();
$crane = \merlin\Crane::getInstance();

if($user==null){
	header('Location: login.php');
	$logger->error("Need to be logged in to destroy an instance.");
	exit(0);
}

$uuid = isset($_GET['uuid'])?$_GET['uuid']:null;
$instances = $redis->sMembers('INSTANCES:FROM:' . $user->getUsername() );
if($instances==null) $instances = array();

if( !$redis->sIsMember('INSTANCES:FROM:' . $user->getUsername(), $uuid) ){
	header('Location: list.php');
	exit(0);
}

$botCount = intval($redis->hGet('INSTANCES:SCALE', $uuid));
$delta = 0;

if( isset($_GET['decr']) && $botCount > 1 ) {
	$crane->setBotCount($uuid,  $botCount - 1);
	$delta = -1;
}else if(isset($_GET['incr']) && $botCount < 20) {
	$crane->setBotCount($uuid,  $botCount + 1);
	$delta = 1;
}
$redis->hIncrBy('INSTANCES:SCALE', $uuid, $delta);

header('Location: list.php');
?>
