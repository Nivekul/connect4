
<!DOCTYPE html>

<html>
	<head>
		<script src="http://code.jquery.com/jquery-latest.js"></script>
		<script>
		</script>
	</head> 
<body>  
	<div class='forms center fade'>
			<div class='inGrid half middle center'>
				<div class='title_small'>Change Password</div>
<?php

	echo form_open('account/updatePassword');
	echo form_password('oldPassword',set_value('oldPassword'),"required placeholder='Old Password'");
	echo '<br>';
	echo form_error('oldPassword');
	echo '<br>';
	echo form_password('newPassword','',"id='pass1' required placeholder='New Password'");
	echo '<br>';
	echo form_error('newPassword');
	echo '<br>';
	echo form_password('passconf','',"id='pass2' required placeholder='Password Confirmation'");
	echo '<br>';
	echo form_error('passconf');
	echo '<br>'; 

	if (isset($errorMsg)) {
		echo "<p>" . $errorMsg . "</p></br>";
	}

	echo form_submit('submit', 'Change Password');
	echo "<br><br><div class='anchor inline'>" . anchor('arcade/index','Go Back') . "</div>";
	echo form_close();
?>
</div></div>
</body>

</html>

