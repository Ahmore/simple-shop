$(function() {
	$("#account-login-form").on("submit", function() {
		var form = $(this),
			username = form.find("input[name='username']").val(),
			password = form.find("input[name='password']").val(),
			data;
		
		// Walidacja dla starszych przeglądarek
		validator.config = {
			username: "isNotEmpty",
			password: "isNotEmpty"
		};
		data = {
			username: username,
			password: password
		};
		validator.validate(data);
		
		if (validator.hasErrors()) {
			alert(validator.messages.join("\n"));
		}
		else {
			
		}
		
		return false;
	});
});