<?php
	if (!defined("APP") || APP !== TRUE) {
		header('Location: index.php');
		exit;
	}
	
	global $cfg;
?>
<div id="page-name">Zaloguj się</div>
<form method="POST" action="<?php echo $cfg["path"]; ?>/account/login" enctype="multipart/form-data" id="account-login-form">
	<label for="account-login-username">Użytkownik: </label><input type="text" id="account-login-username" name="username" class="input-text" required /><br />
	<label for="account-login-password">Hasło: </label><input type="password" id="account-login-password" name="password" class="input-text" required />
	<input type="submit" value="Loguj" class="btn-submit" />
</form>

