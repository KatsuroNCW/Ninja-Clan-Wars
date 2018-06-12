const mobileMenu = (function() {
	const menuContainer = document.querySelector('.mobile-nav');
	const menuButtons = menuContainer.querySelectorAll('.mobile-nav__item a');
	const landSwitcher = document.querySelector('.land-switcher');
	const mainNav = document.querySelector('.main-navigation');
	const submenuButtons = mainNav.querySelectorAll('.has-submenu > a');
	const pageUrl = new String(document.URL);
	const reg = /forum\.php/;
	let lastScrollPosition = 0;

	const overlay = document.createElement("div");
	overlay.classList.add("nav-overlay");
	const body = document.querySelector('body');

	const init = function() {
		[].forEach.call(menuButtons, function(button) {
			if(!reg.test(pageUrl) && button.dataset.click === 'land_switcher') {
				button.parentElement.remove();
			}
			if(button.dataset.click !== undefined) {
				button.addEventListener('click', function(e) {
					e.preventDefault();
					if(button.dataset.click === 'menu') {
						body.appendChild(overlay);
						mainNav.classList.add('main-navigation--active');
					} else if(button.dataset.click === 'land_switcher') {
						landSwitcher.classList.toggle('land-switcher--active');
					}
				});
			}
		});

		[].forEach.call(submenuButtons, function(button) {
			button.addEventListener('click', function(e) {
				e.preventDefault();
			});
		});

		overlay.addEventListener('click', function() {
			overlay.remove();
			mainNav.classList.remove('main-navigation--active');
		});
	};
	const display = function() {
		(window.scrollY > lastScrollPosition) ? menuContainer.classList.add('mobile-nav--hidden') : menuContainer.classList.remove('mobile-nav--hidden');
		lastScrollPosition = window.scrollY;
	};

	return {
		init : init,
		display : display
	}
})();

document.addEventListener("DOMContentLoaded", function() {
    mobileMenu.init();

	window.addEventListener('scroll', function() {
		mobileMenu.display();
	});
});
