const infoSwitcher = (function() {
    const displayFirstBox = function(boxes) {
        [].forEach.call(boxes, function(box) {
            const boxMainClass = box.classList;
            (box.dataset.item === '1') ? box.classList.add(boxMainClass[0] + '--active') : box.classList.remove(boxMainClass[0] + '--active');
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
                    const boxMainClass = box.classList;
                    (box.dataset.item === button.dataset.item) ? box.classList.add(boxMainClass[0] + '--active') : box.classList.remove(boxMainClass[0] + '--active');
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
