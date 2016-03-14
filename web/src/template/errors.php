<?php
$errors = \merlin\Logger::getInstance()->getErrors();
if($errors!=null && is_array($errors) && count($errors) > 0):
	echo '<div class="errorList">';
	foreach($errors as $error) {
		echo "<li>$error</li>";
	}
	echo '</div>';
endif;
?>