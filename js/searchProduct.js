$(function() {
	$("#searchProduct").on("submit", function(e) {
		var form = $(this),
			category = encodeURIComponent(form.find("select[name='searchCategory']").val()),
			string = encodeURIComponent(form.find("input[name='searchString']").val());
		
		PHARMACY_SHOW.search(1, category, string);
		
		return false;
	});
	
	$("select").customSelect();
});

PHARMACY_SHOW.search = function(page, category, string) {
	var searchLink = cfg["SEARCH_URI_PATTERN"].replace("$$page", page).replace("$$string", string).replace("$$category", category);
	
	window.location.href = searchLink;
}