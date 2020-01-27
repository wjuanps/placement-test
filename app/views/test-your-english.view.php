<?php include_once 'app/views/partials/head.php'; ?>

  <div class="w-75 mx-auto mt-5">
        <h2>PLACEMENT TEST - BASIC</h2>

        <input type="hidden" id="totalQuestoes" value="<?= count($questoes) ?>" />

        <input type="hidden" id="ques" value="<?= json_encode($questoes) ?>" />

        <hr class="hr" />

        <div class="row" id="loading">
            <lottie-player
                class="mx-auto"
                src="https://assets2.lottiefiles.com/packages/lf20_BH43lc.json" 
                background="transparent"
                speed="1"
                style="width: 400px; height: 400px;"
                loop  autoplay >

            </lottie-player>
        </div>

        <div class="hidden mt-4" id="test">
            <div class="row" id="questoes"></div>

            <div class="row">
                <div class="col-md-12">

                    <button id="end" class="btn btn-success hidden">Finalizar</button>

                    <div class="float-right">
                        <lottie-player
                            id="loadingAnswer"
                            class="float-left hidden"
                            src="https://assets2.lottiefiles.com/packages/lf20_BADN8W/31 - Loading 4.json"  
                            background="transparent"  
                            speed="1"  
                            style="width: 50px; height: 50px; margin-top: -6.2px"
                            loop  autoplay >
                        </lottie-player>

                        <div class="btn-group mb-4" role="group" aria-label="Basic example">
                            <button id="previous" type="button" class="btn btn-primary"><i class="fa fa-chevron-left" aria-hidden="true"></i></button>
                            <button id="next" type="button" class="btn btn-primary"><i class="fa fa-chevron-right" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row text-center mb-5" id="indicator"></div>
        </div>
    </div>

<?php include_once 'app/views/partials/footer.php'; ?>

<script>
    "use strict";

    (function () {
        let placementKey = localStorage.getItem('placement');

        if (placementKey == null || placementKey == undefined) {
            location.pathname = '/register';
        }
    })();

    window.onload = async () => {
        try {
            let questoes = JSON.parse(localStorage.getItem('questions'));
            if (questoes == null || questoes == undefined) {
                questoes = await placement.getQuestions();
                localStorage.setItem('questions', JSON.stringify(questoes.data));

                questoes = JSON.parse(localStorage.getItem('questions'));
            }

            placement.init(questoes);
        } catch (error) { console.error(error); }
    }

    const placement = {
        index: 0,

        caroucel: ()      => document.querySelectorAll('.caroucel'),
        progressItems: () => document.querySelectorAll('.progress-item'),
        indicator: ()     => document.querySelectorAll('.indicator'),

        next: ()     => document.getElementById('next'),
        previous: () => document.getElementById('previous'),

        init(questions) {
            let testView = document.getElementById('test');
            let loading  = document.getElementById('loading');

            loading.classList.add('hidden');
            
            this.loadQuestions(questions);
            this.loadIndicators(questions);
            
            testView.classList.remove('hidden');

            this.addEventListener(questions);

            this.progressItems()[this.index].classList.add('active');

            this.watch();
        },

        watch() {
            const endButton = document.getElementById('end');
            setInterval(() => {
                let questions = JSON.parse(localStorage.getItem('questions'));
                let response = questions.every((question) => question.respondida === 1);

                endButton.classList.remove((response) ? 'hidden' : 't');
            }, 1000);
        },

        addEventListener(questoes) {
            this.caroucel().forEach((element, i) => {
                let alternativas = element.querySelectorAll('.inputGroup .alternativas');

                alternativas.forEach((alternativa) => {
                    alternativa.addEventListener('click', () => {
                        let _questions   = JSON.parse(localStorage.getItem('questions'));
                        let _alternativa = alternativa.getAttribute('id').split('_')[1];

                        let _question = _questions[this.index];
                        _question.resposta   = _alternativa;
                        _question.respondida = 1;

                        _questions[this.index] = _question;

                        localStorage.setItem('questions', JSON.stringify(_questions));

                        this.progressItems()[i].classList.add('respondida');

                        this.saveAnswer(_question.id, alternativa);
                    });
                });
            });

            this.indicator().forEach((element, i) => {
                element.addEventListener('click', (event) => {
                    event.preventDefault();

                    this.index = i;

                    this.changeQuestion(this.index, questoes);
                });
            });

            this.next().removeAttribute('disabled');
            this.previous().setAttribute('disabled', 'disabled');

            this.next().addEventListener('click', () => this.changeQuestion(++this.index, questoes));
            this.previous().addEventListener('click', () => this.changeQuestion(--this.index, questoes));
        },

        getQuestions: async () => await axios(`questoes?placement=${ localStorage.getItem('placement') }`),

        changeQuestion(index, questoes) {
            this.caroucel().forEach((c) => c.classList.add('hidden'));
            this.progressItems().forEach((progressItem) => progressItem.classList.remove('active'));

            this.caroucel()[index].classList.remove('hidden');
            this.progressItems()[index].classList.add('active');

            (index === 0)
                ? this.previous().setAttribute('disabled', 'disabled') 
                : this.previous().removeAttribute('disabled');

            (index === questoes.length - 1)
                ? this.next().setAttribute('disabled', 'disabled')
                : this.next().removeAttribute('disabled');
        },

        async saveAnswer(question, alternativa) {
            let loading = document.getElementById('loadingAnswer');
            loading.classList.remove('hidden');

            let placement = localStorage.getItem('placement');
            let answer    = alternativa.getAttribute('id').split('_')[0];

            const params = new URLSearchParams();
            params.append('placement', placement);
            params.append('question', question);
            params.append('answer', answer);

            await axios.post('save-answer', params);

            loading.classList.add('hidden');
        },

        loadQuestions(questions) {
            let divQuestions = document.getElementById('questoes');
            let html = '';
            
            questions.forEach((question, i) => {
                html += `<div class="col-md-12 caroucel ${ (i > 0) ? 'hidden' : '' }">`;
                html += `<h3 class="mb-4">${ question.enunciado }</h3>`;

                question.alternativas.forEach((alternativa) => {
                    html += `
                        <div class="inputGroup">
                            <input 
                                id="${ alternativa.id }_${ alternativa.alternativa }" 
                                name="radio_${ i }" 
                                class="alternativas" 
                                type="radio" ${ (question.resposta === alternativa.alternativa) ? 'checked' : '' } />

                            <label for="${ alternativa.id }_${ alternativa.alternativa }">${ alternativa.resposta }</label>
                        </div>
                    `;
                });

                html += '</div>';
            });

            divQuestions.innerHTML = html;
        },

        loadIndicators(questions) {
            let divIndicator = document.getElementById('indicator');
            let html = `<div class="dots-container col-md-12" role="navigation">`;

            questions.forEach((question, i) => {
                html += `
                    <li class="progress-item ${ (question.respondida === 1) ? 'respondida' : '' }">
                        <a 
                            href="" 
                            class="progress-dot indicator" 
                            title="Question ${ i + 1 } of ${ questions.length }, Unanswered" 
                            tabindex="-1">
                        </a>
                    </li>  
                `;
            });
            
            html += '</ul>';

            divIndicator.innerHTML = html;
        }
    };
</script>
