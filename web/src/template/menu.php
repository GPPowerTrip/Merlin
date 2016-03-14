<?php
/**
 * Created by PhpStorm.
 * User: Jaime
 * Date: 07/01/2016
 * Time: 16:29
 */

?>

<nav>
	<?php if(!isset($CONTEXT['noLogo'])): ?>
	<svg x="0px" y="0px" height="192px" viewBox="0 0 328.02 426.002">
		<?php require $CONTEXT['basePath'] . 'src/img/merlin.svg'?>
	</svg>
	<?php endif; ?>
	<ul>
		<li><a href="index.php">Home</a></li>
		<li><a href="create.php">Create</a></li>
		<li><a href="list.php">List</a></li>
		<li><a href="login.php?logout">Logout</a></li>
	</ul>
</nav>

