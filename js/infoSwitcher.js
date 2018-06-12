const infoSwitcher = (function() {
    const displayFirstBox = function(boxes) {
        [].forEach.call(boxes, function(box) {
            (box.dataset.item === '1') ? box.style.display = 'block' : box.style.display = 'none';
        });
    };

    const setActiveButton = function(buttons) {
        [].forEach.call(buttons, function(button) {
            const buttonMainClass = button.classList;
            (button.dataset.item === '1') ? button.classList.add(buttonMainClass[0] + '--active') : '' ;
        });
    };

    const init = function(switcher) {
        const switcherButtons = switcher.querySelectorAll('.switcher-nav');
        const switcherBoxes = switcher.querySelectorAll('.switcher-item');

        displayFirstBox(switcherBoxes);
        setActiveButton(switcherButtons);

        [].forEach.call(switcherButtons, function(button) {
            const buttonMainClass = button.classList;
            button.addEventListener('click', function() {
                [].forEach.call(switcherBoxes, function(box) {
                    (box.dataset.item === button.dataset.item) ? box.style.display = 'block' : box.style.display = 'none';
                });
                [].forEach.call(switcherButtons, function(btn) {
                    (button.dataset.item === btn.dataset.item) ? btn.classList.add(buttonMainClass[0] + '--active') : btn.classList.remove(buttonMainClass[0] + '--active');
                });
            });
        });
    };

    return {
        init : init
    }
})();

document.addEventListener("DOMContentLoaded", function() {
    const switchers = document.querySelectorAll('.switcher');
    [].forEach.call(switchers, function(switcher) {
        infoSwitcher.init(switcher);
    });
});
