document.addEventListener('DOMContentLoaded', function() {
	(function() {
		const mobileMenu = document.querySelector('.mobile-nav__menu');
		const landSwitcher = document.querySelector('.land-switcher');
		const landSwitcherButton = mobileMenu.querySelector('.mobile-nav__item--switcher');
		const mainNav = document.querySelector('.main-navigation');
		const menuButton = mobileMenu.querySelector('.mobile-nav__item--menu');
		const submenuButtons = mainNav.querySelectorAll('.has-submenu > a');
		console.log(submenuButtons);

		landSwitcherButton.addEventListener('click', function(e) {
			e.preventDefault();
			landSwitcher.classList.toggle('land-switcher--active')
		});

		menuButton.addEventListener('click', function(e) {
			e.preventDefault();
			mainNav.classList.toggle('main-navigation--active');
		});

		for (var i=0; i<submenuButtons.length; i++) {
			submenuButtons[i].addEventListener('click', function(e) {
				e.preventDefault();
				this.parentElement.querySelector('.submenu').classList.toggle('submenu--active');
			});
		}
	}());
}());
