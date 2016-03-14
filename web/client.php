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
	'title' => '> ',
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
	$logger->error("Need to be logged in to command an instance.");
	exit(0);
}

$uuid = isset($_GET['uuid'])?$_GET['uuid']:null;
$instances = $redis->sMembers('INSTANCES:FROM:' . $user->getUsername() );
if($instances==null) $instances = array();

if(!in_array($uuid, $instances)){
	header('Location: list.php');
	exit(0);
}

$CONTEXT['title'] = '> ' . $uuid;


require $CONTEXT['basePath'] . 'src/template/head.php';
?>

<div class="clientTerminal">
	<div class="codeFont left terminal" id="terminalOutput"></div>
	<div class="consolePrompt">
		<div class="table">
			<div class="cpgt">&gt;</div>
			<div class="cpinput">
				<input type="text" class="codeFont" name="cmd" id="cmd">
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">var uuid = '<?=$uuid?>';</script>

<script type="text/javascript" src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="<?=$CONTEXT['basePath']?>src/js/client.js"></script>


<?php require $CONTEXT['basePath'] . 'src/template/bottom.php';?>
