<?php
	//echo "<div class='normal'><pre>".var_dump($_SESSION)."<pre></div>";
	echo "
			<div class='navWrap center fixed full fade dropHeader shadow'>
				<div class='nav'>";

	if (isset($_SESSION['user'])) { // Logged in
				echo '<div class="header_item inline left left_align twenty">';
					echo '<div class="login_item">Welcome, ' . $_SESSION['user']->first . '!</div>';
					echo anchor(site_url('account/logout'),'LOGOUT', 'class="login_item thin"');
					echo anchor(site_url('account/updatePasswordForm'),'CHANGE PASSWORD', 'class="login_item thin"');
				echo '</div>';
	} else {
		// Visitor
				echo '<div class="header_item inline left left_align twenty">';
					echo anchor(site_url('account/loginForm'),'LOGIN', 'class=login_item');
				echo '</div>';
	}

	echo '<div class="inline">';
	echo anchor(site_url(),'CONNECT4', 'class="inline name clear"');
	echo '</div>';
	
	echo '<div class="header_item inline right right_align twenty clear"></div>';
	echo "</div></div>";
?>