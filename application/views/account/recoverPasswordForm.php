
<!DOCTYPE html>

<html>
	<head>

	</head> 
<body>  
	<div class='forms center fade'>
		<div class='inGrid half middle center'>
			<div class='title_small'>Recover Password</div>
<?php 
	echo form_open('account/recoverPassword');
	echo form_input('email',set_value('email'),"required placeholder='E-mail Address'");
	echo '<br>';
	echo form_error('email');
	echo '<br>';
	echo form_submit('submit', 'Recover Password');
	echo '<br>';
	if (isset($errorMsg)) {
		echo "<p>" . $errorMsg . "</p>";
	}
	echo "<br><br><div class='anchor inline'>" . anchor('account/loginForm','Go Back') . "</div>";
	echo form_close();
?>	
</body>

</html>

