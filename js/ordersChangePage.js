$(function() {
	$("#admin-newOrders-pageChange-form").on("submit", function() {
		var form = $(this),
			sort = form.find("input[name='admin-newOrders-sort']").val(),
			page = form.find("input[name='admin-newOrders-page']").val();
		
		alert(sort);
		alert(page);
		// Idzie do wybranej strony
		PHARMACY_SHOW.searchNewOrders(page, sort);
		return false;
	});
	
	$("#admin-oldOrders-pageChange-form").on("submit", function() {
		var form = $(this),
			sort = form.find("input[name='admin-oldOrders-sort']").val(),
			page = form.find("input[name='admin-oldOrders-page']").val();
		
		// Idzie do wybranej strony
		PHARMACY_SHOW.searchOldOrders(page, sort);
		return false;
	});
});

PHARMACY_SHOW.searchNewOrders = function(page, sort) {
	var searchLink = cfg["SEARCH_NEWORDERS_URI_PATTERN"].replace("$$page", page).replace("$$sort", sort);
	
	window.location.href = searchLink;
}

PHARMACY_SHOW.searchOldOrders = function(page, sort) {
	var searchLink = cfg["SEARCH_OLDORDERS_URI_PATTERN"].replace("$$page", page).replace("$$sort", sort);
	
	window.location.href = searchLink;
}