<?php
	if (!defined("APP") || APP !== TRUE) {
		header('Location: index.php');
		exit;
	}
	
	global $cfg;
?>
<link rel="stylesheet" href="<?php echo $cfg["path"]; ?>/js/select/select.css">
<script type="text/javascript" src="<?php echo $cfg["path"]; ?>/js/select/select.js"></script>
<script type="text/javascript" src="<?php echo $cfg["path"]; ?>/js/searchProduct.js"></script>

<div id="header-container">
	<a href="<?php echo $cfg["path"]; ?>" id="header-name">Apteka Internetowa</a>

	<div id="header-userinfo">
		<?php
			if (Login::isLogged()) {
				?>
					.: Zalogowany <a href="<?php echo $cfg["path"]; ?>/account/info" class="link"><?php echo Login::getUserName(); ?></a>,
					<a href="<?php echo $cfg["path"]; ?>/account/logout" class="link"> wyloguj się.</a>
				<?php
			}
			else {
				?>
					Niezalogowany, <a href='<?php echo $cfg["path"]; ?>/account/login' class='link'>zaloguj się</a>.<br />
					Nie posiadasz konta, <a href='<?php echo $cfg["path"]; ?>/account/register' class='link'>zarejestruj się</a>.
				<?php
			}
		?>
	</div>

	<form id="searchProduct">
		<input type="text" name="searchString" value='<?php echo @$_SESSION["string"]; ?>'/>
		<select name="searchCategory">
			<option value="*" selected>Wszystkie</option>
			<?php
				$categories = DataManager::getCategories();
				$category = @$_SESSION["category"];
				
				while ($row = $categories->fetch_array(MYSQLI_ASSOC)) {
					$encodedName = $row["encodedName"];
					$name = $row["name"];
					$selected = "";
					
					if ($encodedName == $category) {
						$selected = "selected";
					}
					
					echo "<option value='$encodedName' $selected>$name</option>";
				}
			?>
		</select>
		<input type="submit" value="Wyszukaj" />
	</form>

	<div id="header-basket">
		<a href="<?php echo $cfg["path"]; ?>/basket/info" class="link">Koszyk</a>
		<div>
			<?php
				// require_once "/modules/Basket/Model.php";
				// $model = new Basket_Model();
				// $products = $model->info(array("demand" => true));
				
				if (!isset($_SESSION["basket"])) {
					$_SESSION["basket"] = array();
				}
				
				$basket = array_keys($_SESSION["basket"]);
				$products = DataManager::getProductsByIds($basket);
				
				$fullAmount = count(@$_SESSION["basket"]);
				$fullPrice = 0;
				
				if ($fullAmount > 0 && $products && $products->num_rows != 0) {
					while ($row = $products->fetch_array(MYSQLI_ASSOC)) {
						$id = $row["id"];
						$price = $row["price"];
						$amount = $_SESSION["basket"][$id];
						
						$fullPrice += $price * $amount;
					}
				}
				echo "Liczba produktów : $fullAmount<br/>";
				echo "Wartość: $fullPrice zł"
			?>
		</div>
	</div>
</div>

<?php
	// Jeśli zalogowany jest administrator
	if (Login::isLogged() && Login::getUserType() == 1) {
		global $cfg;
		
		?>
			<div id="admin-panel">
				<span id="admin-panel-name">Panel Administratora</span>
				<a href="<?php echo "{$cfg["path"]}/admin/productAdd" ?>" class="link">Dodaj produkt</a>
				<a href="<?php echo "{$cfg["path"]}/admin/categories" ?>" class="link">Kategorie</a>
				<a href="<?php echo "{$cfg["path"]}/admin/newOrders" ?>" class="link">Zamówienia</a>
			</div>
		<?php
	}
?>