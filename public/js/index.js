"use strict";

window.onload = () => {
    try {
        let caroucel = document.querySelectorAll('.caroucel');

        let lis = document.querySelectorAll('.li');
        lis[0].classList.add('active');

        let indicator = document.querySelectorAll('.indicator');

        indicator.forEach((element, i) => {
            element.addEventListener('click', (event) => {
                event.preventDefault();

                caroucel.forEach((c) => c.classList.add('hidden'));
                lis.forEach((li) => li.classList.remove('active'));

                caroucel[i].classList.remove('hidden');
                lis[i].classList.add('active');
            });
        });
    } catch (error) {
        console.error(error);
    }

    console.log();
}
