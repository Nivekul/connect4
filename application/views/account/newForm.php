
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
				<div class='title_small'>Create an Account</div>
<?php 
	echo form_open('account/createNew');
	echo form_input('username',set_value('username'),"required placeholder='Username'");
	echo '<br>';
	echo form_error('username');
	echo '<br>';
	echo form_password('password','',"id='pass1' required placeholder='Password'");
	echo '<br>';
	echo form_error('password');
	echo '<br>';
	echo form_password('passconf','',"id='pass2' required placeholder='Password Confirmation'");
	echo '<br>';
	echo form_error('passconf');
	echo '<br>';
	echo form_input('first',set_value('first'),"required placeholder='First Name'");
	echo '<br>';
	echo form_error('first');
	echo '<br>';
	echo form_input('last',set_value('last'),"required placeholder='Last Name'");
	echo '<br>';
	echo form_error('last');
	echo '<br>';
	echo form_input('email',set_value('email'),"required placeholder='E-mail Address'");
	echo '<br>';
	echo form_error('email');
	echo '<br>';
	echo '<div class="half inline">';
	echo form_input('captcha',set_value(''),"required placeholder='Enter letters as shown' class='inline'");
	echo $image['image'];
	echo '</div>';
	echo '<br>';
	echo form_error('captcha');
	echo '<br>';
	echo form_submit('submit', 'Register');
	echo "<br><br><div class='anchor inline'>" . anchor('account/loginForm','Already have an account?') . "</div>";
	echo form_close();
?>	
</div></div>
</body>

</html>

