$(function() {
	$("#products-search-pageChange-form").on("submit", function() {
		var form = $(this),
			category = form.find("input[name='products-search-category']").val(),
			string = form.find("input[name='products-search-string']").val(),
			page = form.find("input[name='products-search-page']").val();
		
		// Idzie do wybranej strony
		PHARMACY_SHOW.search(page, category, string);
		return false;
	});
});