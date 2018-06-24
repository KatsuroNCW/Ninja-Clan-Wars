class JutsuList {
  constructor() {
    this.jutsuMainContainer = document.querySelector('.kp-jutsu');
    this.listOfJutsus = [];
    this.selectContainer = null;
    this.globalSettings = (function() {
      var json = null;
      $.ajax({
        'async': false,
        'global': false,
        'url': "json/globalSettings.json",
        'dataType': "json",
        'success': function(data) {
            json = data;
        }
      });
      return json;
    })();
    this.ranks = this.globalSettings.jutsu_rank;
    this.natures = this.globalSettings.jutsu_nature;
    this.cat = this.globalSettings.jutsu_classification;

    this.prepareListOfJutsus();
    this.generateStatistics();
    this.generateOptions();
    this.sortByRank();
  }
  prepareListOfJutsus() {
    const jutsuContainers = this.jutsuMainContainer.querySelectorAll('.kp-jutsu__container');
    this.listOfJutsus = [];
    [].forEach.call(jutsuContainers, container => {
      this.listOfJutsus.push(container.querySelectorAll('.kp-jutsu__item'));
    });
    [].forEach.call(jutsuContainers, container => {
      if(!container.querySelectorAll('.kp-jutsu__item').length) {
          container.remove();
      }
    });
  }
  generateStatistics() {
    const statistics = document.createElement('div');
    statistics.classList.add('jutsu-statistics');
    const heading = document.createElement('h2');
    heading.classList.add('jutsu-statistics__heading');
    heading.innerText = 'Statystyki poznanych technik';
    statistics.appendChild(heading);
    const ranks = document.createElement('div');
    ranks.classList.add('jutsu-statistics__item');
    statistics.appendChild(ranks);
    const natures = document.createElement('div');
    natures.classList.add('jutsu-statistics__item');
    statistics.appendChild(natures);
    const categories = document.createElement('div');
    categories.classList.add('jutsu-statistics__item');
    statistics.appendChild(categories);

    this.ranks.forEach(rank => {
      const p = document.createElement('p');
      let counter = 0;
      [].forEach.call(this.listOfJutsus, jutsuContainer => {
        [].forEach.call(jutsuContainer, jutsu => {
          if(jutsu.dataset.rank === rank) {
            counter++;
          }
        });
      });
      p.innerText = 'Ranga ' + rank + ': ' + counter;
      ranks.appendChild(p);
    });

    this.natures.forEach(nature => {
      const p = document.createElement('p');
      let counter = 0;
      [].forEach.call(this.listOfJutsus, jutsuContainer => {
        [].forEach.call(jutsuContainer, jutsu => {
          if(jutsu.dataset.nature === nature) {
            counter++;
          }
        });
      });

      if(counter) {
        if(nature === 'none') {
          p.innerText = 'Nieżywiołowe: ' + counter;
        } else {
          p.innerText = nature + ': ' + counter;
        }
        natures.appendChild(p);
      }
    });

    this.cat.forEach(cat => {
      const p = document.createElement('p');
      let counter = 0;
      [].forEach.call(this.listOfJutsus, jutsuContainer => {
        [].forEach.call(jutsuContainer, jutsu => {
          if(jutsu.dataset.cat === cat) {
            counter++;
          }
        });
      });
      p.innerText = cat + ': ' + counter;
      categories.appendChild(p);
    });

    this.jutsuMainContainer.appendChild(statistics);
  }
  generateOptions() {
    this.selectContainer = document.createElement('div');
    this.selectContainer.classList.add('kp-jutsu-form');

    const selectHeading = document.createElement('h2');
    selectHeading.classList.add('form__heading');
    selectHeading.innerText = "Sortuj przez: ";
    this.selectContainer.appendChild(selectHeading);

    const select = document.createElement('select');
    select.classList.add('form__input');
    select.classList.add('form__input--select');
    select.addEventListener('change', e => {
      switch (e.target.options[e.target.selectedIndex].value) {
        case 'rank':
          this.sortByRank();
          break;
        case 'cat':
          this.sortByCat();
          break;
        case 'nature':
          this.sortByNature();
          break;
      }
    });
    this.selectContainer.appendChild(select);

    let option = document.createElement('option');
    option.text = 'Rangę';
    option.value = 'rank';
    select.appendChild(option);

    option = document.createElement('option');
    option.text = 'Żywioł';
    option.value = 'nature';
    select.appendChild(option);

    option = document.createElement('option');
    option.text = 'Rodzaj';
    option.value = 'cat';
    select.appendChild(option);

    if(this.listOfJutsus.length) {
      this.jutsuMainContainer.appendChild(this.selectContainer);
    }
  }
  mainSort(jutsus) {
    let sortedList = [];
    this.ranks.forEach(rank => {
      for(let i=0; i<jutsus.length; i++) {
        [].forEach.call(jutsus, jutsu => {
          if(jutsu.dataset.rank === rank) {
            sortedList.push(jutsu);
          }
        });
      }
    });
    return sortedList;
  }
  sortByRank() {
    this.ranks.forEach(rank => {
      const container = document.createElement('div');
      container.classList.add('kp-jutsu__container');

      let jutsus = [];
      [].forEach.call(this.listOfJutsus, jutsuContainer => {
        [].forEach.call(jutsuContainer, jutsu => {
          if(jutsu.dataset.rank === rank) {
            jutsus.push(jutsu);
          }
        });
      });

      const heading = document.createElement('h2');
      heading.classList.add('kp-jutsu__heading');
      heading.innerHTML = 'Jutsu rangi ' + rank + ' - ' + jutsus.length;
      container.appendChild(heading);

      if(jutsus.length) {
          jutsus.forEach(jutsu => container.appendChild(jutsu));
          this.jutsuMainContainer.appendChild(container);
      }
    });
    this.prepareListOfJutsus();
  }
  sortByCat() {
    this.cat.forEach(cat => {
      const container = document.createElement('div');
      container.classList.add('kp-jutsu__container');

      let jutsus = [];
      [].forEach.call(this.listOfJutsus, jutsuContainer => {
        [].forEach.call(jutsuContainer, jutsu => {
            if(jutsu.dataset.cat === cat) {
                jutsus.push(jutsu);
            }
        });
      });

      const heading = document.createElement('h2');
      heading.classList.add('kp-jutsu__heading');
      heading.innerHTML = cat + ' - ' + jutsus.length;
      container.appendChild(heading);

      if(jutsus.length) {
          this.mainSort(jutsus).forEach(jutsu => container.appendChild(jutsu));
          this.jutsuMainContainer.appendChild(container);
      }
    });
    this.prepareListOfJutsus();
  }
  sortByNature() {
    this.natures.forEach(nature => {
      const container = document.createElement('div');
      container.classList.add('kp-jutsu__container');

      let jutsus = [];
      [].forEach.call(this.listOfJutsus, jutsuContainer => {
        [].forEach.call(jutsuContainer, jutsu => {
            if(jutsu.dataset.nature === nature) {
                jutsus.push(jutsu);
            }
        });
      });

      const heading = document.createElement('h2');
      heading.classList.add('kp-jutsu__heading');
      if(nature === 'none') {
        heading.innerHTML = 'Zwykłe - ' + jutsus.length;
      } else {
        heading.innerHTML = nature + ' - ' + jutsus.length;
      }
      container.appendChild(heading);

      if(jutsus.length) {
        this.mainSort(jutsus).forEach(jutsu => container.appendChild(jutsu));
        this.jutsuMainContainer.appendChild(container);
      }
  });
  this.prepareListOfJutsus();
  }
}

const jutsuList = new JutsuList();
