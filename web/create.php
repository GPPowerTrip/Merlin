<?php

require 'merlin/RedisFactory.php';
require 'merlin/Session.php';
require 'merlin/Logger.php';
require 'merlin/User.php';
require 'merlin/UserManager.php';
require 'merlin/Crane.php';

define('RATE_LIMITING_DELAY', 0);

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
	$logger->error("Need to be logged in to create instances.");
	exit(0);
}


$uuid = preg_replace("/[^a-zA-Z0-9_]+/", "", "{$redis->sRandMember('ADJECTIVES')}_{$redis->sRandMember('NOUNS')}");

$lastCreation = intval($redis->hGet('INSTANCES:LASTCREATION', $user->getUsername()));
$interval = time() - $lastCreation;

if($interval < RATE_LIMITING_DELAY){
	header('Location: list.php');
	$logger->error("You're creating clusters way too fast! Slow down and make sure to delete the unused ones.");
	exit(0);
}

if($redis->sIsMember('INSTANCES', $uuid)){
	$logger->error("Damn! Duplicate name! You're lucky as fuck :o Save this message and I'll pay you a beer!");
	header('Location: index.php');
	exit(0);
}

$redis->sAdd('INSTANCES', $uuid);
$redis->hSet('INSTANCES:OWNERSHIP', $uuid, $user->getUsername());
$redis->hSet('INSTANCES:SCALE', $uuid, 1);
$redis->sAdd('INSTANCES:FROM:' . $user->getUsername(), $uuid );
$redis->hSet('INSTANCES:LASTCREATION', $user->getUsername(), time());

$crane->startExcalibot($uuid);


header('Location: list.php');
?>
