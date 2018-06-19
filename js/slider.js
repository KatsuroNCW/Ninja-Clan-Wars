class Slider {
    constructor(elemSelector) {
        this.currentSlide = 0;
        this.sliderSelector = elemSelector;
        this.slider = null;
        this.slides = null;
        this.prev = null;
        this.next = null;
        this.squares = [];
        this.time = null;

        this.generateSlider();
        this.changeSlide(this.currentSlide);
    }
    generateSlider() {
        this.slider = document.querySelector(this.sliderSelector);
        this.slider.classList.add('slider');

        const sliderContainer = document.createElement('div');
        sliderContainer.classList.add('slider__container');

        this.slides = this.slider.children;
        console.log(this.slides);

        while(this.slides.length) {
            this.slides[0].classList.add('slide');
            sliderContainer.appendChild(this.slides[0]);
        }

        this.slides = sliderContainer.children;
        this.slider.appendChild(sliderContainer);

        this.createButtons();
        this.createSquares();
    }
    createButtons() {
        this.prev = document.createElement('button');
        this.prev.type = "button";
        this.prev.innerText = "<";
        this.prev.classList.add('slider__button');
        this.prev.classList.add('slider__button--prev');
        this.prev.addEventListener('click', this.slidePrev.bind(this));

        this.next = document.createElement('button');
        this.next.type = "button";
        this.next.innerText = ">";
        this.next.classList.add('slider__button');
        this.next.classList.add('slider__button--next');
        this.next.addEventListener('click', this.slideNext.bind(this));

        const nav = document.createElement('div');
        nav.classList.add('slider__nav');
        nav.appendChild(this.prev);
        nav.appendChild(this.next);
        this.slider.appendChild(nav);
    }
    createSquares() {
        const squaresUl = document.createElement('ul');
        squaresUl.classList.add('slider__squares');

        for (let i = 0; i < this.slides.length; i++) {
            const squareLi = document.createElement('li');
            squareLi.classList.add('slider-square');

            squareLi.addEventListener('click', function() {
                this.changeSlide(i);
            }.bind(this));

            squaresUl.appendChild(squareLi);
            this.squares.push(squareLi);
        }

        this.slider.appendChild(squaresUl);
    }
    slidePrev() {
        this.currentSlide--;
        if (this.currentSlide < 0) {
            this.currentSlide = this.slides.length - 1;
        }
        this.changeSlide(this.currentSlide);
    }
    slideNext() {
        this.currentSlide++;
        if (this.currentSlide > this.slides.length - 1) {
            this.currentSlide = 0;
        }
        this.changeSlide(this.currentSlide);
    }
    changeSlide(index) {
        [].forEach.call(this.slides, function(slide) {
            slide.classList.remove('slide--active');
        });

        this.squares.forEach(function(square) {
            square.classList.remove('slider-square--active');
        });

        this.slides[index].classList.add('slide--active');
        this.squares[index].classList.add('slider-square--active');

        this.currentSlide = index;

        clearInterval(this.time);
        this.time = setTimeout(function() {
            this.slideNext();
        }.bind(this), 6000);
    }
}

const slider2 = new Slider('.news-panel');
