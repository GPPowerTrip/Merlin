<?php
$CONTEXT = array(
	'title' => '::Merlin - Register',
	'basePath' => ''
);

require 'merlin/RedisFactory.php';
require 'merlin/Session.php';
require 'merlin/User.php';
require 'merlin/UserManager.php';
require 'merlin/Logger.php';



if( isset($_POST['username'], $_POST['password'], $_POST['passwordVerification']) ) { //User Registration
	$user = htmlspecialchars($_POST['username']);
	$pass = htmlspecialchars($_POST['password']);
	$newUser = new \merlin\User();
	$newUser->setUsername($user)
			->setPassword($pass);
	try{
		if($_POST['passwordVerification'] != $newUser->getPassword()) throw new Exception("Typed passwords do not match.");
		\merlin\UserManager::createUser($newUser);
		header('Location: login.php');
	}catch(Exception $e){
		\merlin\Logger::getInstance()->error($e->getMessage());
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
				<p><input name="passwordVerification" type="password" placeholder="Password"></p>
				<p><input type="submit" value="Register"></p>
			</form>
		</div>
	</div>
</div>
<?php require $CONTEXT['basePath'] . 'src/template/bottom.php'; ?>