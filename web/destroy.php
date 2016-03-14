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

$redis->sRem('INSTANCES', $uuid);
$redis->hDel('INSTANCES:OWNERSHIP', $uuid);
$redis->hDel('INSTANCES:SCALE', $uuid);
$redis->sRem('INSTANCES:FROM:' . $user->getUsername(), $uuid);

$crane->destroyEverything($uuid);
header('Location: list.php');
?>
