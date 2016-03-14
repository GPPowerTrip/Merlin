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
	'title' => '::Merlin - Instances',
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


$instances = $redis->sMembers('INSTANCES:FROM:' . $user->getUsername());
if($instances==null) $instances = array();



require $CONTEXT['basePath'] . 'src/template/head.php';
require $CONTEXT['basePath'] . 'src/template/menu.php';
require $CONTEXT['basePath'] . 'src/template/errors.php';
?>
<div class="center">
	<div class="title">List of your instances.</div>
	<table class="instanceList inlineBlock">
		<tr>
			<td class="center">
				<b>&emsp;Name:&emsp;</b>
			</td>
			<td class="center">&emsp;<b>Client</b>&emsp;</td>
			<td class="center">
				<b>&emsp;Knights:&emsp;</b>
			</td>
			<td class="center">&emsp;&emsp;</td>
			<td class="center">&emsp;&emsp;</td>
			<td class="center">
				&emsp;&emsp;&emsp;
			</td>
		</tr>
	<?php foreach($instances as $instance):?>
		<tr>
			<td class="left">
				<a href="instance.php?uuid=<?=$instance?>"><?=prettifyName($instance)?></a>
			</td>
			<td class="center">
				<span class="cmdIconPop" onclick="openTerm('<?=$instance?>')"></span>
				<a target="_blank" href="client.php?uuid=<?=$instance?>"><span class="cmdIcon"></span></a>
			</td>
			<td class="center">
				<?=$redis->hGet('INSTANCES:SCALE', $instance);?>
			</td>
			<td class="center">&emsp;<a href="scale.php?uuid=<?=$instance?>&incr">+</a>&emsp;</td>
			<td class="center">&emsp;<a href="scale.php?uuid=<?=$instance?>&decr">-</a>&emsp;</td>
			<td class="center destroy_btn">
				<a href="destroy.php?uuid=<?=$instance?>">Destroy</a>
			</td>
		</tr>
	<?php endforeach;?>
	</table>
</div>

<script type="text/javascript" src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="<?=$CONTEXT['basePath']?>src/js/list.js"></script>

<?php require $CONTEXT['basePath'] . 'src/template/bottom.php';?>
