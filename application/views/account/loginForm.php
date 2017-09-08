
<!DOCTYPE html>

<html>
<body> 
	<div class='forms center fade'>
			<div class='inGrid half middle center'>
				<div class='title_small'>Login</div>
<?php

	echo form_open('account/login');
	echo form_input('username',set_value('username'),"required placeholder='Username' ");
	echo '<br>';
	echo form_error('username');
	echo '<br>';
	echo form_password('password','',"required placeholder='Password' ");
	echo '<br>';
	echo form_error('password');
	echo '<br>';

	echo form_submit('submit', 'LOGIN');
	echo '<br>';

	if (isset($errorMsg)) {
		echo "<p>" . $errorMsg . "</p><br>";
	}
	echo '<br>';

	echo '<div class="inline">';
	echo "<div class='anchor inline'>" . anchor('account/newForm','Create Account') . "</div>";
	echo '<div class="inline space"></div>';
	echo "<div class='anchor inline'>" . anchor('account/recoverPasswordForm','Recover Password') . "</div>";
	echo '</div>';
	
	echo form_close();
?>	
</div></div>
</body>

</html>

