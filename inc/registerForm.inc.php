<?php
	if (!defined("APP") || APP !== TRUE) {
		header('Location: index.php');
		exit;
	}
	
	global $cfg;
?>
<div id="page-name">Zarejestruj się</div>


<form method="POST" action="<?php echo $cfg["path"]; ?>/account/register" enctype="multipart/form-data" id="account-register-form">
	<label for="account-register-username">Użytkownik: </label>
	<input type="text" id="account-register-username" name="username" class="input-text" value="<?php echo @$_SESSION["register-try-username"]; ?>" required />
	<?php
		if (isset($_SESSION["register-try-errors"]["username"]) && $_SESSION["register-try-errors"]["username"]) {
			echo "<div class='register-error'>Użytkownik o tej nazwie już istnieje.</div>";
		}
	?>
	<br />
	
	<label for="account-register-password">Hasło: </label>
	<input type="password" id="account-register-password" name="password" class="input-text" value="<?php echo @$_SESSION["register-try-password"]; ?>" required />
	
	<br />
	
	<label for="account-register-repeatPassword">Powtórz hasło: </label>
	<input type="password" id="account-register-repeatPassword" name="repeat_password" class="input-text" value="<?php echo @$_SESSION["register-try-repeat_password"]; ?>" required />
	<?php
		if (isset($_SESSION["register-try-errors"]) && $_SESSION["register-try-errors"]["password"]) {
			echo "<div class='register-error'>Podane hasła nie są jednakowe.</div>";
		}
	?>
	<br />
	
	<label for="account-register-email">Email: </label>
	<input type="text" id="account-register-email" name="email" class="input-text" value="<?php echo @$_SESSION["register-try-email"]; ?>" required />
	<?php
		if (isset($_SESSION["register-try-errors"]["email"]) && $_SESSION["register-try-errors"]["email"]) {
			echo "<div class='register-error'>Podany email jest niepoporawny.</div>";
		}
	?>
	
	<input type="submit" value="Rejestruj" class="btn-submit" />
</form>

