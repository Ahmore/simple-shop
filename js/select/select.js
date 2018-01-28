(function($) {
	$.fn.customSelect = function(options) {
		var settings = $.extend({
			amount: 4
		}, options),
		
		Select = function(select) {
			this.memory = {
				select: select,
				container: null,
				customSelect: null,
				customSelectText: null,
				customSelectTextText: null,
				customSelectArrow: null,
				customSelectOptions: null,
				customSelectInput: null,
				value: null,
				index: null,
				amount: null,
				state: 0
			};
			
			this.init();
		};
		
		Select.prototype.init = function() {
			var self = this,
				memory = self.memory,
				select = memory.select,
				option;
				
			// Tworzy strukturê
			memory.container = select.wrap("<div/>").parent().addClass("select-container");
			memory.customSelect = $("<div/>").appendTo(memory.container).addClass("select-customSelect");
			memory.customSelectText = $("<div/>").appendTo(memory.customSelect).addClass("select-customSelect-text");
			memory.customSelectTextText = $("<div/>").appendTo(memory.customSelectText).addClass("select-customSelect-text-text");
			memory.customSelectArrow = $("<div/>").appendTo(memory.customSelectText).addClass("select-customSelect-arrow");
			memory.customSelectOptions = $("<ul/>").appendTo(memory.customSelect).addClass("select-customSelect-options");
			memory.customSelectInput = $("<input/>").appendTo(memory.container).css({
				width: 0,
				height: 0
			});
			memory.value = select.val();
			
			// Tworze opcje na podstawie selecta
			select.find("option").each(function(index) {
				option = $(this);
				$("<li/>").appendTo(memory.customSelectOptions).addClass("select-customSelect-option").attr("value", option.val()).append(option.text()).on("click", function(e) {
					e.stopPropagation();
					self.change.apply(self, [$(this)]);
				}).data("index", index);
				
				if (memory.value === option.val()) {
					memory.index = index;
				}
				memory.amount = index;
			});
			
			// Ustawia wymiary
			memory.container.css({
				left: select.css("marginLeft"),
				right: select.css("marginRight"),
				top: select.css("marginTop"),
				bottom: select.css("marginBottom")
			});
			
			memory.customSelectText.css({
				width: select.outerWidth(),
				height: select.outerHeight()
			});
			
			memory.customSelectTextText.width(select.width() - 24).text(self.getText());
			
			memory.select.css({
				paddingLeft: 0,
				paddingRight: 0,
				paddingTop: 0,
				paddingBottom: 0,
				marginLeft: 0,
				marginRight: 0,
				marginTop: 0,
				marginBottom: 0
			});
			
			memory.customSelectOptions.css({
				maxHeight: settings.amount * memory.customSelectOptions.find("li").outerHeight(),
				maxWidth: 1.5 * select.outerWidth()
			});
			
			// Dodaje zdarzenia
			memory.customSelect.on("click", function(e) {
				e.stopPropagation();
				self.toggleSelect.apply(self);
			});
			
			memory.customSelectInput.on("keypress", function(e) {
				self.shortCut.apply(self, [e]);
			});
			
			$(document).on("click", function() {
				self.toggleSelect.apply(self, [true]);
			})
		};
		
		// Pobiera aktualn¹ wartoœæ tekstow¹ selecta
		Select.prototype.getText = function() {
			var select = this.memory.select;
			
			return select.find("option[value='" + select.val() + "']").text();
		};
		
		// Otwiera/zamyka selecta
		Select.prototype.toggleSelect = function(window) {
			var memory = this.memory;
			
			if (!window || memory.state === 1) {
				memory.customSelectOptions.toggle();
				memory.customSelectArrow.toggleClass("active");
				memory.customSelectText.toggleClass("active");
				
				// Zmienia stan aktywnoœci selecta
				memory.state = (memory.state+1)%2;
				if (memory.state === 0) {
					memory.customSelectInput.blur();
				}
				else {
					memory.customSelectInput.focus();
				}
			}
			
			this.setActive();
			this.setScroll(true);
		};
		
		// Zmiana wartoœci
		Select.prototype.change = function(option) {
			var memory = this.memory,
				index = option.data("index"),
				value = option.attr("value"),
				text;
			
			this.toggleSelect();
			
			
			if (value != memory.select.val()) {
				memory.select.val(memory.value = value);
				memory.customSelectTextText.text(this.getText());
				memory.index = index;
				
				memory.select.change();
			}
		};
		
		// Sterowanie klawiatur¹
		Select.prototype.shortCut = function(e) {
			var code = e.keyCode,
				memory = this.memory;
			
			switch(code) {
				case 38:
					if (memory.index > 0) {
						memory.index -= 1;
						this.setActive();
						this.setScroll();
					}
					break;
				
				case 40:
					if (memory.index < memory.amount) {
						memory.index += 1;
						this.setActive();
						this.setScroll();
					}
					break;
					
				case 13:
					this.triggerActive();
					
					return false;
					break;
			}
		};
		
		// Ustawia 
		Select.prototype.setActive = function() {
			var memory = this.memory,
				select = memory.customSelectOptions,
				index = memory.index + 1;
			
			select.find(".active").removeClass("active");
			select.children(":nth-child(" + index + ")").addClass("active");
		};
		
		// Aktywuje wybranie opcji z klawiatury
		Select.prototype.triggerActive = function() {
			var memory = this.memory,
				select = memory.customSelectOptions,
				index = memory.index + 1;
				
			select.children(":nth-child(" + index + ")").click();
		};
		
		Select.prototype.setScroll = function(window) {
			var memory = this.memory,
				ul = memory.customSelectOptions,
				index = memory.index,
				ulHeight = ul.height(),
				ulScroll = ul.scrollTop(),
				liHeight = ul.find("li").outerHeight(),
				liScroll = index * liHeight;
			
			if (liScroll <= ulScroll) {
				ul.scrollTop(liScroll);
			}
			else if (liScroll >= ulHeight + ulScroll) {
				ul.scrollTop(liScroll - ulHeight + liHeight);
			}
			
			if (window) {
				ul.scrollTop(liScroll);
			}
		};
		
		
		
		return this.each(function() {
			new Select($(this));
		});
	}
})(jQuery)