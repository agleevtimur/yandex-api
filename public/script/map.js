let marks = [];
let statistics = [];
let myMap;
let optionValueList;

window.onload = async () => {
    document.body.classList.add('loaded_hiding');
    await prepareInitialData();
    await ymaps.ready(init); // Дождёмся загрузки API и готовности DOM.

    const buttonReset = document.getElementById('reset_button');
    optionValueList = document.body.getElementsByClassName('statistics__value')[0];
    buttonReset.addEventListener('click', reset);
    renderStatistics();

    window.setTimeout(async function () {
        document.body.classList.add('loaded');
        document.body.classList.remove('loaded_hiding');
    }, 1000);
}

async function prepareInitialData() {
    localStorage.removeItem('mark');
    await fetch('/page/render')
        .then(response => response.json())
        .then(data => {
            document.body.innerHTML = data['view'];
            marks = data['marks'];
            statistics = data['statistics'];
        });
}

function init() {
    myMap = new ymaps.Map('map', {
        center: [55.8, 49.1], // Казань
        zoom: 10
    }, {
        searchControlProvider: 'yandex#search'
    });

    marks.forEach(m => {
        let mark = new Mark(m['x'], m['y'], m['distance']);
        setMark(mark);
        resolvePrevMarkAndCreateLink(mark);
    });

    myMap.events.add('click', handleClick);
}

async function handleClick(e) {
    e.preventDefault();

    const coords = e.get('coords');
    let markNew = new Mark(coords[0], coords[1]);
    setMark(markNew);
    const prevMark = resolvePrevMarkAndCreateLink(markNew);
    if (prevMark) {
        addDistanceToPrevMark(prevMark, markNew);
    }

    await sendMark(markNew);
    updateStatistics();
}

function setMark(mark) {
    myMap.geoObjects.add(new ymaps.Placemark([mark.x, mark.y], {
        balloonContent: `Координаты метки: ${mark.x}, ${mark.y}`
    }, {
        preset: 'islands#dotIcon',
        iconColor: '#735184'
    }));
}

async function sendMark(mark) {
    await fetch('/mark/save', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json;charset=utf-8'
        },
        body: JSON.stringify(mark)
    }).then(r => {
        console.log(r.statusText);
    });
}

function resolvePrevMarkAndCreateLink(mark) {
    let previousMark = Mark.getFromStorage();
    Mark.putToStorage(mark);
    if (previousMark !== null) {
        addLink(previousMark, mark);
    }

    return previousMark;
}

function addDistanceToPrevMark(previousMark, mark) {
    const distance = ymaps.coordSystem.geo.getDistance([previousMark.x, previousMark.y], [mark.x, mark.y]);
    mark.setDistanceToPreviousPoint(distance);
}

function addLink(markFirst, markSecond) {
    let link = new ymaps.GeoObject({
        geometry: {
            type: 'LineString',
            coordinates: [
                [markFirst.x, markFirst.y],
                [markSecond.x, markSecond.y]
            ]
        }
    }, {
        draggable: false,
        strokeColor: "#FFFF00",
        strokeWidth: 5
    });

    myMap.geoObjects.add(link);
}

async function reset(event) {
    event.preventDefault();

    localStorage.removeItem('mark');
    myMap.geoObjects.removeAll();
    await fetch('/reset').then(response => console.log(response.statusText));

    for (let i = 0; i < optionValueList.children.length; i++) {
        optionValueList.children[i].innerText = 0;
    }
}

function renderStatistics() {
    const optionNameList = document.body.getElementsByClassName('statistics__name')[0];
    const render = (list, item) => {
        const li = document.createElement('li');
        li.innerText = item;
        list.appendChild(li);
    }

    Object.keys(statistics).forEach(name => render(optionNameList, name));
    Object.values(statistics).forEach(value => render(optionValueList, value));
}

async function updateStatistics() {
    await fetch('/statistics/update')
        .then(data => data.json())
        .then(data => statistics = data['statistics']);

    const values = Object.values(statistics);
    for (let i = 0; i < optionValueList.children.length; i++) {
        optionValueList.children[i].innerText = values[i];
    }
}

class Mark {
    constructor(x, y, distance = 0) {
        this.x = (+x).toPrecision(8);
        this.y = (+y).toPrecision(8);
        this.distance = distance;
    }

    setDistanceToPreviousPoint(value) {
        this.distance = value;
    }

    static getFromStorage() {
        let mark = JSON.parse(localStorage.getItem('mark'));
        if (!mark) {
            return null;
        }

        return new Mark(mark['x'], mark['y']);
    }

    static putToStorage(value) {
        localStorage.setItem('mark', JSON.stringify(value));
    }
}