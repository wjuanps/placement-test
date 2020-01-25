"use strict";

window.onload = async () => {
    try {

        let ques = await axios('questoes');
        ques.data.forEach(element => {
            console.log(element);
        });

        let totalQuestoes = document.getElementById('totalQuestoes');
        
        let index = 0;
        let caroucel = document.querySelectorAll('.caroucel');

        let lis = document.querySelectorAll('.li');
        lis[index].classList.add('active');

        let indicator = document.querySelectorAll('.indicator');

        caroucel.forEach((element, i) => {
            let alternativas = element.querySelectorAll('.inputGroup .alternativas');

            alternativas.forEach((alternativa) => {
                alternativa.addEventListener('click', () => lis[i].classList.add('respondida'));
            });
        });

        indicator.forEach((element, i) => {
            element.addEventListener('click', (event) => {
                index = i;

                event.preventDefault();

                caroucel.forEach((c) => c.classList.add('hidden'));
                lis.forEach((li) => li.classList.remove('active'));

                caroucel[index].classList.remove('hidden');
                lis[index].classList.add('active');
            });
        });

        let next = document.getElementById('next');
        next.removeAttribute('disabled');

        let previous = document.getElementById('previous');
        previous.setAttribute('disabled', 'disabled');

        next.addEventListener('click', () => {
            caroucel.forEach((c) => c.classList.add('hidden'));
            lis.forEach((li) => li.classList.remove('active'));

            caroucel[++index].classList.remove('hidden');
            lis[index].classList.add('active');
            
            if (index === 0) {
                previous.setAttribute('disabled', 'disabled');
            } else {
                previous.removeAttribute('disabled');
            }

            if (index === totalQuestoes.value - 1) {
                next.setAttribute('disabled', 'disabled');
            } else {
                next.removeAttribute('disabled');
            }
        });

        previous.addEventListener('click', () => {
            caroucel.forEach((c) => c.classList.add('hidden'));
            lis.forEach((li) => li.classList.remove('active'));

            caroucel[--index].classList.remove('hidden');
            lis[index].classList.add('active');

            if (index === 0) {
                previous.setAttribute('disabled', 'disabled');
            } else {
                previous.removeAttribute('disabled');
            }

            if (index === totalQuestoes.value - 1) {
                next.setAttribute('disabled', 'disabled');
            } else {
                next.removeAttribute('disabled');
            }
        });
    } catch (error) {
        console.error(error);
    }
}
