document.addEventListener('DOMContentLoaded', function () {
    const carousel = document.querySelector('.carousel-items');
    const nextBtn = document.querySelector('.next');
    const prevBtn = document.querySelector('.prev');
  
    nextBtn.addEventListener('click', () => {
      carousel.scrollBy({
        left: 200,
        behavior: 'smooth'
      });
    });
  
    prevBtn.addEventListener('click', () => {
      carousel.scrollBy({
        left: -200,
        behavior: 'smooth'
      });
    });
  });