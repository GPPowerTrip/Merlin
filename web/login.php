<?php
$CONTEXT = array(
				'title' => '::Merlin - Authentication',
				'basePath' => ''
);

require 'merlin/RedisFactory.php';
require 'merlin/Session.php';
require 'merlin/User.php';
require 'merlin/UserManager.php';
require 'merlin/Logger.php';

$session = \merlin\Session::getInstance();

if( isset($_GET['logout']) ) $session->end();

if( isset($_POST['username'], $_POST['password']) ) { //User Registration
	$user = new \merlin\User();
	$user->setUsername($_POST['username'])
		 ->setPassword($_POST['password']);

	if(\merlin\UserManager::authenticate($user)){
		$session->set('user', $user->getUsername());
		header('Location: index.php');
	} else {
		\merlin\Logger::getInstance()->error('Wrong credentials.');
	}
}

require $CONTEXT['basePath'] . 'src/template/head.php';
require $CONTEXT['basePath'] . 'src/template/errors.php';
?>
<div class="fullCover">
	<div class="inlineBlock center verticalMiddle">
		<div>
			<svg x="0px" y="0px" width="328.02px" height="426.002px" viewBox="0 0 328.02 426.002">
				<?php require $CONTEXT['basePath'] . 'src/img/merlin.svg'?>
			</svg>
		</div>
		<div>
			<form method="post" action="">
				<p><input name="username" type="text" placeholder="Username"></p>
				<p><input name="password" type="password" placeholder="Password"></p>
				<p><input type="submit" value="Authenticate"></p>
			</form>
		</div>
	</div>
</div>
<?php require $CONTEXT['basePath'] . 'src/template/bottom.php'; ?>