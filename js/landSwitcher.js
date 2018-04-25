$('[data-land_id=2]').before($('.land-switcher'));

document.addEventListener('DOMContentLoaded', function() {
    const cookie = (function() {
        const showCookie = function(name) {
            if (document.cookie != "") {
                const cookies = document.cookie.split(/; */);

                for (let i=0; i<cookies.length; i++) {
                    const cookieName = cookies[i].split("=")[0];
                    const cookieVal = cookies[i].split("=")[1];
                    if (cookieName === decodeURIComponent(name)) {
                        return decodeURIComponent(cookieVal);
                    }
                }
            }
        };

        const setCoockie = function(value) {
            if (navigator.cookieEnabled) {
                const cookieVal = encodeURIComponent(value);
                let cookieText = "displayedLand=" + cookieVal;
                const data = new Date();
                data.setTime(data.getTime() + (365 * 24*60*60*1000));
                cookieText += "; expires=" + data.toGMTString();

                document.cookie = cookieText;
            }
        };

        const deleteCookie = function(name) {
            const cookieName = encodeURIComponent(name);
            document.cookie = cookieName + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        };

        return {
            showCookie : showCookie,
            setCoockie : setCoockie,
            deleteCookie : deleteCookie
        }
    }());

    const landSwitcher = (function() {
        const landSwitcher = document.querySelector('.land-switcher');
        const switcherButtons = document.querySelectorAll('.land-switcher__land');
        const switcherLands = document.querySelectorAll('.category .section-land');

        const defaultSetup = function() {
            if(cookie.showCookie("displayedLand")) {
                const cookieSwitcherButton = landSwitcher.querySelector('[data-land_button_id="' + cookie.showCookie("displayedLand") + '"]');
                cookieSwitcherButton.classList.add('land-switcher__land--active');
                
                for (let i=0; i<switcherLands.length; i++) {
                    if(switcherLands[i].parentElement.dataset.land_id !== cookie.showCookie("displayedLand")) {
                        switcherLands[i].parentElement.style.display = 'none';
                    }
                }
            } else {
                landSwitcher.querySelector('[data-land_button_id="2"]').classList.add('land-switcher__land--active');
                for (let i=0; i<switcherLands.length; i++) {
                    if(switcherLands[i].parentElement.dataset.land_id !== '2') {
                        switcherLands[i].parentElement.style.display = 'none';
                    }
                }
            }
        };
        
        const switcherButtonsClick = function() {
            for (let i = 0; i < switcherButtons.length; i++) {
                switcherButtons[i].addEventListener('click', function() {
                    const switcherButton = this;

                    if (document.querySelector('.land-switcher__land--active')) {
                        document.querySelector('.land-switcher__land--active').classList.remove('land-switcher__land--active');
                    }
                    switcherButton.classList.add('land-switcher__land--active');

                    for (let j=0; j<switcherLands.length; j++) {
                        land = switcherLands[j].parentElement;
                        land.style.display = (land.dataset.land_id === switcherButton.dataset.land_button_id)? 'flex' : 'none';
                    }
                    cookie.setCoockie(switcherButton.dataset.land_button_id);
                });
            }
        };

        return {
            defaultSetup : defaultSetup,
            switcherButtonsClick : switcherButtonsClick
        }
    }());

    landSwitcher.defaultSetup();
    landSwitcher.switcherButtonsClick();
}());