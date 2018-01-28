$(function() {
	$(".basket-products-form, #product-addToBasket").on("submit", function() {
		var form = $(this),
			id = form.find("input")[0].name;
			amount = form.find("input")[0].value;
			
		// WALIDACJA LICZBY
		
		
		PHARMACY_SHOW.updateBasket(id, amount);
		return false;
	});
});


PHARMACY_SHOW.updateBasket = function(id, amount) {
	var searchLink = cfg["BASKET_UPDATE_URI_PATTERN"].replace("$$id", id).replace("$$amount", amount);
	
	window.location.href = searchLink;
}