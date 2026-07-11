let Index = 1;
showSlides(Index);

function plusSlides(n) {
    showSlides(Index += n);
}

function currentSlide(n) {
    showSlides(Index = n);
}

function showSlides(n) {
    let i;
    let slides = document.getElementsByClassName("mySlides");
    if (n > slides.length) {Index = 1}
    if (n < 1) {Index = slides.length}
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    slides[Index-1].style.display = "block";
}