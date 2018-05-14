const validateForm = (function() {
	let options = {};

	const showValidateInfo = function(input, inputIsValid, errorMsg) {
		let label = '';
		if(input.parentElement.className == 'form__prompt-label') {
			label = input.parentElement;
		} else {
			label = input.parentElement.parentElement.parentElement;
		}
		const errorMessage = document.createElement('span');
		errorMessage.setAttribute('class', 'label-error-msg');
		errorMessage.innerText = errorMsg;

		if(!inputIsValid) {
			input.classList.add(options.classError);
			if(label.querySelector('.label-error-msg') === null) {
				label.appendChild(errorMessage);
			}
		} else {
			if(label.querySelector('.label-error-msg') !== null) {
				input.classList.remove(options.classError);
				const elementToDelete = label.querySelector('.label-error-msg');
				label.removeChild(elementToDelete);
			}
		}
	};

	const testLogin = function(input) {
		const reg = /^[^0-9]{2,30}$/;
		const clansNames = ["Uchiha", "Senju", "Nara", "Kiyoshi", "Ayatsuri", "Namikaze", "Sabaku", "Hyuga", "Yuki", "Inuzuka", "Kaguya", "Akimichi", "Aburame", "Futago", "Kuran Sen", "Kakukuro"];
		const charaktersNames = ["Naruto", "Sasuke", "Hinata", "Kakashi", "Sakura", "Neji", "Sai", "Kabuto", "Madara", "Shikaramu", "Gai", "Rock Lee", "Konohamaru", "Kyuubi", "Boruto", "Obito", "Sarada", "Itachi", "Nagato", "Hinabi", "Orochimaru", "Minato", "Hagoromo", "Gaara", "Kurama", "Tsunade", "Hashirama", "Jiraiya", "Kushina", "Hiruzen", "Shisui", "Deirada", "Kisame", "Danzo", "Temari", "Ino", "Rin", "Mito", "Tobirama", "Sasori", "Kakuzu", "Hidan", "Asuma", "Karin", "Konan", "Hiashi", "Yamato", "Tenten", "Kiba", "Zabuza", "Zetsu", "Anko", "Yashiko", "Choji", "Kurenai", "Kimimaro", "Shino", "Shukaku", "Karui", "Mei", "Nawaki", "Kankuro", "Suigetsu", "Hanzo", "Haku", "Matatabi", "Darui", "Shizune", "Jugo", "Ao", "Yagura", "Guren", "Izuna", "Utakata", "Iruka", "Shikaku", "Yugito", "Kurotsuchi", "Chojuro", "Gamabunta", "Tobi", "Inoichi", "Raiga", "Onoki", "Hizashi", "Dan", "Chiyo", "Ibiki", "Omoi", "Gamamaru", "Kinkaku", "Manda", "Torune", "Mifune", "Gengetsu", "Yukimaru", "Hayate", "Sakon", "Ukon", "Akamaru", "Ebisu", "Yashamaru", "Gamakichi", "Ranmaru", "Moegi", "Katsuyu", "Ginkaku", "Udon", "Baki", "Tazuna", "Enma", "Fukasaku", "Kidomaru"];
		let validated = true;

		clansNames.forEach(function(clanName) {
			let tempReg = new RegExp(clanName, "i");
			if(tempReg.test(input.value)) validated = false;
		});

		charaktersNames.forEach(function(charakterName) {
			let tempReg = new RegExp(charakterName, "i");
			if(tempReg.test(input.value)) validated = false;
		});

		if(!reg.test(input.value)) validated = false;

		if(validated) {
			showValidateInfo(input, true);
			return true;
		} else {
			showValidateInfo(input, false, 'Niepoprawny login!');
			return false;
		}
	};

	const testEmail = function(input) {
		const reg = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

		if(reg.test(input.value)) {
			showValidateInfo(input, true);
			return true;
		} else {
			showValidateInfo(input, false, 'Niepoprawny email!');
			return false;
		}
	}

	const testPassword = function(input) {
		const reg = /^[a-zA-Z0-9]{6,}$/;

		if(reg.test(input.value)) {
			showValidateInfo(input, true);
			return true;
		} else {
			showValidateInfo(input, false, 'Niepoprawne hasło!');
			return false;
		}
	}

	const testPasswordMatch = function(input) {
		const passwordInput = document.querySelector('[data-validateForm="password"]');

		if(input.value === passwordInput.value) {
			showValidateInfo(input, true);
			return true;
		} else {
			showValidateInfo(input, false, 'Podane hasła nie pasują do siebie!');
			return false;
		}
	}

	const testCheckbox = function(input) {
		if(input.checked) {
			showValidateInfo(input, true);
			return true;
		} else {
			showValidateInfo(input, false, 'Musisz potwierdzić regulamin!');
			return false;
		}
	}

	const prepareElements = function() {
		const elements = options.form.querySelectorAll(':scope [required]');

		[].forEach.call(elements, function(element) {
			const data = element.dataset.validateform;

			if (element.nodeName.toUpperCase() == 'INPUT') {

				if(data == "login") {
					element.addEventListener('keyup', function() {testLogin(element)});
					element.addEventListener('blur', function() {testLogin(element)});
				}
				if(data == "email") {
					element.addEventListener('keyup', function() {testEmail(element)});
					element.addEventListener('blur', function() {testEmail(element)});
				}
				if(data == "password") {
					element.addEventListener('keyup', function() {testPassword(element)});
					element.addEventListener('blur', function() {testPassword(element)});
				}
				if(data == 'password_again') {
					element.addEventListener('keyup', function() {testPasswordMatch(element)});
					element.addEventListener('blur', function() {testPasswordMatch(element)});
				}
			} else if (element.nodeName.toUpperCase() == 'LABEL') {
				if(data == 'rules') {
					const checkboxLabel = element.parentElement;
					const checkbox = checkboxLabel.querySelector('.form__input--checkbox');
					checkboxLabel.addEventListener('click', function() {testCheckbox(checkbox)});
				}
			}
		});
	};

	const formSubmit = function() {
		options.form.addEventListener('submit', function(e) {
		 	let validated = true;
		 	const elements = options.form.querySelectorAll(':scope [required]');

		 	[].forEach.call(elements, function(element) {
		 		const data = element.dataset.validateform;

				if (element.nodeName.toUpperCase() == 'INPUT') {
					if(data == "login") {
						if(!testLogin(element)) validated = false;
					}
					if(data == "email") {
						if(!testEmail(element)) validated = false;
					}
					if(data == "password") {
						if(!testPassword(element)) validated = false;
					}
					if(data == 'password_again') {
						if(!testPasswordMatch(element)) validated = false;
					}
				} else if (element.nodeName.toUpperCase() == 'LABEL') {
					if(data == 'rules') {
						const checkboxLabel = element.parentElement;
						const checkbox = checkboxLabel.querySelector('.form__input--checkbox');
						if(!testCheckbox(checkbox)) validated = false;
					}
				}
			});

			if (!validated) {
		        e.preventDefault();
		        return false;
		    } else {
		    	return true;
		    }
		});
	};

	const init = function(_options) {
		options = {
			form : _options.form || null,
			classError : _options.classError || 'form__input--error'
		}

		if (options.form == null || options.form == undefined || options.form.length == 0) {
			console.warn('validateForm: źle przekazany formularz');
			return false;
		}

		options.form.setAttribute('novalidate', 'novalidate');

		prepareElements();
		formSubmit();
	};

	return {
		init : init
	}

})();

document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector('.form');
    validateForm.init({form : form})
});