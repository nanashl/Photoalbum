// Функция для подгрузки изображений
function loadImages(page = 1) {
    const category = document.querySelector('select[name="category"]').value;
    const tag = document.querySelector('input[name="tag"]').value;
    const date = document.querySelector('input[name="date"]').value;

    // Создаем параметры для отправки в GET-запросе
    const params = new URLSearchParams();
    params.append('category', category);
    params.append('tag', tag);
    params.append('date', date);
    params.append('page', page);

    // Отправляем запрос на сервер
    fetch(`index.php?${params.toString()}`)
        .then(response => response.json())
        .then(data => {
            const gallery = document.querySelector('.image-gallery');
            if (page === 1) {
                gallery.innerHTML = '';  // Очистка галереи при первом запросе
            }

            data.forEach(image => {
                const imageCard = document.createElement('div');
                imageCard.classList.add('image-post');
                imageCard.innerHTML = `
                    <img src="${image.image_url}" alt="${image.title}">
                    <div class="image-info">
                        <h3>${image.title}</h3>
                        <p>${image.description}</p>
                        <div class="actions">
                            <button class="like-btn">👍 20</button>
                            <button class="comment-btn">💬 5</button>
                        </div>
                    </div>
                `;
                gallery.appendChild(imageCard);
            });
        })
        .catch(error => console.error('Error loading images:', error));
}

// Функция для обработки фильтрации
function handleFilterChange() {
    loadImages(1); // Сбросить страницу при изменении фильтров
}

// Инициализация фильтров
document.querySelector('form').addEventListener('submit', (event) => {
    event.preventDefault();
    handleFilterChange();
});

// Подгрузка новых изображений при прокрутке страницы
let currentPage = 1;
window.addEventListener('scroll', () => {
    if (window.scrollY + window.innerHeight >= document.documentElement.scrollHeight) {
        currentPage++;
        loadImages(currentPage);
    }
});

// Загружаем изображения при первом посещении страницы
loadImages();

//// SLIDE SHOW
let slideIndex = 0;
let slides = [];

// Загружаем изображения из папки через PHP
function loadSlideshowImages() {
    fetch('load_slideshow.php')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error(data.error);
                return;
            }
            slides = data; // Записываем данные в массив slides
            renderSlides();
            startSlideshow();
        })
        .catch(error => console.error('Ошибка загрузки слайд-шоу:', error));
}

// Создаём HTML для слайд-шоу
function renderSlides() {
    const slideshowContainer = document.querySelector('.slideshow-container');
    if (!slideshowContainer) {
        console.error('Слайд-шоу контейнер не найден');
        return;
    }

    console.log('Генерация слайд-шоу с данными:', slides); // Отладка
    slideshowContainer.innerHTML = ''; // Очищаем контейнер

    slides.forEach((slide, index) => {
        const slideElement = document.createElement('div');
        slideElement.className = 'slideshow-slide';
        slideElement.style.display = index === 0 ? 'block' : 'none'; // Показываем только первый слайд
        slideElement.innerHTML = `<img src="${slide}" alt="Слайд ${index + 1}">`;
        slideshowContainer.appendChild(slideElement);
    });

    // Добавляем кнопки управления
    const prevButton = document.createElement('a');
    prevButton.className = 'prev';
    prevButton.innerHTML = '&#10094;';
    prevButton.onclick = () => changeSlide(-1);

    const nextButton = document.createElement('a');
    nextButton.className = 'next';
    nextButton.innerHTML = '&#10095;';
    nextButton.onclick = () => changeSlide(1);

    slideshowContainer.appendChild(prevButton);
    slideshowContainer.appendChild(nextButton);
}


// Показываем текущий слайд
function showSlide(n) {
    const slideshowSlides = document.querySelectorAll('.slideshow-slide');
    if (n >= slides.length) slideIndex = 0; // Если последний слайд, переходим к первому
    if (n < 0) slideIndex = slides.length - 1; // Если первый слайд, переходим к последнему

    slideshowSlides.forEach((slide, index) => {
        slide.style.display = index === slideIndex ? 'block' : 'none';
    });
}

// Меняем слайд (вперёд или назад)
function changeSlide(n) {
    showSlide((slideIndex += n));
}

// Автоматическое переключение слайдов
function startSlideshow() {
    setInterval(() => {
        changeSlide(1);
    }, 5000); // Переключение каждые 5 секунд
}

// Запускаем загрузку слайдов при загрузке страницы
document.addEventListener('DOMContentLoaded', () => {
    loadSlideshowImages();
});


function loadGallery() {
    fetch('index.php')
        .then(response => response.json())
        .then(data => {
            console.log('Загруженные данные:', data); // Отладка
            const gallery = document.querySelector('.image-gallery .container');
            if (!gallery) {
                console.error('Контейнер галереи не найден');
                return;
            }
            gallery.innerHTML = '';

            data.forEach(image => {
                const imageCard = document.createElement('div');
                imageCard.classList.add('image-card');
                imageCard.innerHTML = `
                    <img src="${image.image_url}" alt="${image.title}" class="gallery-image">
                    <h3>${image.title}</h3>
                    <p>${image.description}</p>
                    <p>Категория: ${image.category}</p>
                `;
                gallery.appendChild(imageCard);
            });
        })
        .catch(error => console.error('Ошибка загрузки изображений:', error));
}

document.addEventListener('DOMContentLoaded', loadGallery);




function logUserAction(actionType) {
    fetch('log_user_action.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            user_id: 1, // ID авторизованного пользователя
            action_type: actionType,
            page_url: window.location.href
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            console.log('Действие залогировано.');
        } else {
            console.error('Ошибка логирования:', data.message);
        }
    });
}

// Пример вызова логирования
document.addEventListener('DOMContentLoaded', () => logUserAction('visit'));
