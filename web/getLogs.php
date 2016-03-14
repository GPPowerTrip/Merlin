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
	$logger->error("Need to be logged in to check the logs.");
	exit(0);
}

if( !isset($_GET['name'], $_GET['offset']) ) {
	header('Location: list.php');
	exit(0);
}

$name = $_GET['name'];
$offset = intval($_GET['name']);

$instances = $redis->sMembers('INSTANCES:FROM:' . $user->getUsername() );
if($instances==null) $instances = array();

$uuid = explode('_', $name)[0];

$notInArray = true;
foreach($instances as $i){
	if( $uuid == trim(strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", $i))) ) {
		$notInArray = false;
		break;
	}
}

if($notInArray){
	header('Location: list.php');
	exit(0);
}

$wantsPlainText = isset($_GET['plain'])?boolval($_GET['plain']):false;

$CONTEXT = array(
	'title' => "::Merlin - Logs " . $name,
	'noLogo' => true,
	'basePath' => ''
);

$logs = $crane->getLogs($name, $offset);

if($wantsPlainText){
	header('Content-Type: text/plain');
	echo $logs;
	exit();
}else {

	function tab2nbsp($str) {
		return str_replace("\t", '&emsp;', $str);
	}

	/*$logs = tab2nbsp(
		nl2br(
			htmlspecialchars(
				$logs
			)
		)
	);*/

	$logs = htmlspecialchars($logs);

	require $CONTEXT['basePath'] . 'src/template/head.php';
	require $CONTEXT['basePath'] . 'src/template/menu.php';
	require $CONTEXT['basePath'] . 'src/template/errors.php';
	?>

	<div class="title center">Logs from <?=prettifyName($name)?> <a href="<?="?offset=0&name=$name&plain=true"?>">[plainText]</a>:</div>

	<div class="center">
		<div class="codeFont left inlineBlock logs">
<?= $logs ?>
		</div>
	</div>
	<?php require $CONTEXT['basePath'] . 'src/template/bottom.php';
}
?>



