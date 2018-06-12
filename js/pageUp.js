const pageUp = (function() {
    const pageUpButton = document.querySelector('.page-up');
    const userBrowserHeight = window.innerHeight;

    const toggleVisibility = function(button) {
        (window.scrollY > 0.5*userBrowserHeight) ? button.classList.add('page-up--visible') : button.classList.remove('page-up--visible');
    };

    const scrollToTop = function() {
    	$('html, body').animate({ scrollTop: 0 }, 600);
    };

    const scroll = function(button) {
        window.addEventListener('scroll', function() {
            toggleVisibility(button);
        });
    };

    const init = function() {
        toggleVisibility(pageUpButton);

        window.addEventListener('scroll', function() {
            toggleVisibility(pageUpButton);
        });
        pageUpButton.addEventListener('click', function() {
            scrollToTop();
        });
    }

    return {
        init : init
    }
})();

document.addEventListener("DOMContentLoaded", function() {
    pageUp.init();
});
