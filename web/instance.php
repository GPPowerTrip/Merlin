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
	'title' => '::Merlin - ',
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
	$logger->error("Need to be logged in to check an instance.");
	exit(0);
}

$uuid = isset($_GET['uuid'])?$_GET['uuid']:null;
$instances = $redis->sMembers('INSTANCES:FROM:' . $user->getUsername() );
if($instances==null) $instances = array();

if(!in_array($uuid, $instances)){
	header('Location: list.php');
	exit(0);
}

$CONTEXT['title'] .= $uuid;
$containers = $crane->listContainers($uuid);
if($containers==null) $containers = array();


require $CONTEXT['basePath'] . 'src/template/head.php';
require $CONTEXT['basePath'] . 'src/template/menu.php';
require $CONTEXT['basePath'] . 'src/template/errors.php';
?>

<div class="center">
	<table class="instanceList inlineBlock">
		<tr>
			<td class="center">
				<b>Name:</b>
			</td>
		</tr>
		<?php foreach($containers as $container):?>
			<tr>
				<td class="left">
					<a href="getLogs.php?offset=0&name=<?=$container?>"><?=prettifyName($container)?></a>
				</td>
			</tr>
		<?php endforeach;?>
	</table>
</div>


<?php require $CONTEXT['basePath'] . 'src/template/bottom.php';?>
