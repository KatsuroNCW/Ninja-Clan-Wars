document.addEventListener('DOMContentLoaded', function() {
	(function() {
		const buttons = document.querySelectorAll('.bbcode__item, .bbcode__button');

		for (let i = 0; i < buttons.length; i++) {
			buttons[i].addEventListener('click', function(e) {
				e.preventDefault();
				const button = this;
				if(!buttons[i].dataset.need_value) {
					var module = button.parentElement.parentElement;
				} else {
					var module = button.parentElement.parentElement.parentElement;
					const buttonInput = button.parentElement.firstElementChild;
					const valueInput = buttonInput.value;
				}
				const textarea = module.querySelector('.form__input--textarea');
				const buttonId = button.getAttribute('id');
				const tag = button.dataset.bbcode_button;
				const text = textarea.value;
				const selectedText = text.substring(textarea.selectionStart, textarea.selectionEnd);
		        const beforeSelected = text.substring(0, textarea.selectionStart);
		        const afterSelected = text.substring(textarea.selectionEnd, text.length);

				if(buttons[i].dataset.bbcode_button && !buttons[i].dataset.need_value) {
					textarea.value = beforeSelected + '[' + tag + ']' + selectedText + '[/' + tag + ']' + afterSelected;
		        	cursorPosition = beforeSelected + '[' + tag + ']' + selectedText + '[/' + tag + ']';
				} else {
					textarea.value = beforeSelected + '[' + tag + valueInput + ']' + selectedText + '[/' + tag + ']' + afterSelected;
		        	cursorPosition = beforeSelected + '[' + tag + valueInput + ']' + selectedText + '[/' + tag + ']';
				}

				textarea.selectionEnd = cursorPosition.length;
		        textarea.focus();
			});      
		}
	}());
}());