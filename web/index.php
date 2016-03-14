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
		'title' => '::Merlin',
		'noLogo' => true,
		'basePath' => ''
	);

	$redis = \merlin\RedisFactory::getInstance();
	$session = \merlin\Session::getInstance();
	$logger = \merlin\Logger::getInstance();
	$user = \merlin\UserManager::getLoggedUser();

	if($user==null){
		header('Location: login.php');
		exit(0);
	}

	require $CONTEXT['basePath'] . 'src/template/head.php';
	require $CONTEXT['basePath'] . 'src/template/menu.php';
	require $CONTEXT['basePath'] . 'src/template/errors.php';
?>

<div class="center">
	<div class="inlineBlock center">
		<div>
			<div class="loginMerlinText">Merlin</div>
			<svg x="0px" y="0px" width="328.02px" height="426.002px" viewBox="0 0 328.02 426.002">
				<?php require $CONTEXT['basePath'] . 'src/img/merlin.svg'?>
			</svg>
		</div>
		<div class="center welcomeMsg">Welcome <?=$user->getUsername()?></div>
	</div>
</div>
<?php require $CONTEXT['basePath'] . 'src/template/bottom.php'; ?>


