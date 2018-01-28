var validator = {
	// Typy walidacji
	types: {},
	
	// Wiadomości
	messages: [],
	
	// Aktualna konfiguracja walidacji
	// Nazwa - rodzaj walidacji
	config: {},
	
	validate: function(data) {
		var i, j, msg, type, t_types, checker, result_ok;
		
		this.messages = [];
		
		for (i in data) {
			if (!data.hasOwnProperty(i)) continue;
			
			type = this.config[i];
			if (!type) continue;
			
			// Umożliwia kilkustronną walidację danej
			t_types = type.split(" ");
			
			for (j = 0; j < t_types.length; j++) {
				type = t_types[j];
				checker = this.types[type];
				
				if (!checker) {
					throw {
						name: "ValidationError",
						message: "Brak obsługi klucza " + type 
					};
				}
				
				result_ok = checker.validate(data[i]);
				if (!result_ok) {
					msg = "Niepoprawna wartość *" + i + "*; " + checker.instructions;
					this.messages.push(msg);
				}
			}
		}
		return this.hasErrors();
	},
	
	hasErrors: function() {
		return this.messages.length !== 0;
	},
	
	getErrors: function() {
		return this.messages;
	},
	
	clear: function() {
		this.messages = [];
		this.config = {};
	}
};

// Przykładowe typy walidacji
validator.types.isNotEmpty = {
	validate: function(value) {
		return value !== "";
	},
	instructions: "Wartość nie może być pusta"
};

validator.types.isNumber = {
	validate: function(value) {
		return !isNaN(value);
	},
	instructions: "Wartość musi być liczbą"
};

validator.types.isAlphaNum = {
	validate: function(value) {
		return !/[^a-z0-9]/i.test(value);
	},
	instructions: "Wartość może zawierać jedynie litery i cyfry"
};

validator.types.isEmail = {
	validate: function(value) {
		return /^[0-9a-zA-Z_.-]+@[0-9a-zA-Z.-]+\.[a-zA-Z]{2,3}$/i.test(value);
	},
	instructions: "Podany e-mail jest nieprawidłowy"
};