// Program Toggle
document.addEventListener("DOMContentLoaded", () => {
    const dropdownToggles = document.querySelectorAll(".dropdown-toggle");

    dropdownToggles.forEach(toggle => {
        toggle.addEventListener("click", () => {
            const content = toggle.nextElementSibling;
            content.classList.toggle("open");

            // Menyesuaikan teks tombol
            if (content.classList.contains("open")) {
                toggle.textContent = toggle.textContent;
            } else {
                toggle.textContent = toggle.textContent;
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const sliderContainer = document.querySelector('.slider-container');
    const prevButton = document.querySelector('.prev-button');
    const nextButton = document.querySelector('.next-button');
    const cards = document.querySelectorAll('.testimonial-card');

    let currentIndex = 0;
    const cardWidth = cards[0].offsetWidth + 20; // Include margin

    function updateSlider() {
        sliderContainer.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
    }

    prevButton.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            updateSlider();
        }
    });

    nextButton.addEventListener('click', () => {
        if (currentIndex < cards.length - 1) {
            currentIndex++;
            updateSlider();
        }
    });

    // Responsive handling
    window.addEventListener('resize', () => {
        currentIndex = 0;
        updateSlider();
    });
});