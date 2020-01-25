<?php include_once 'app/views/partials/head.php'; ?>

  <div class="w-75 mx-auto mt-5">
        <h2>PLACEMENT TEST - BASIC</h2>

        <input type="hidden" id="totalQuestoes" value="<?= count($questoes) ?>" />

        <input type="hidden" id="ques" value="<?= json_encode($questoes) ?>" />

        <hr class="hr" />

        <div class="row questoes">
            <?php foreach ($questoes as $key => $questao) : ?>
                <div class="col-md-12 caroucel <?= $key > 0 ? 'hidden' : '' ?>">
                    <h3 class="mb-4"><?= $questao['enunciado'] ?></h3>

                    <?php foreach ($questao['alternativas'] as $alternativa) : ?>
                        <div class="inputGroup">
                            <input 
                                id="<?= $alternativa->id ?>" 
                                name="radio_<?= $key ?>" 
                                class="alternativas" 
                                type="radio"/>

                            <label for="<?= $alternativa->id ?>"><?= $alternativa->resposta ?></label>
                        </div>
                    <?php endforeach ?>
                </div>
            <?php endforeach ?>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="btn-group float-right mb-4" role="group" aria-label="Basic example">
                    <button id="previous" type="button" class="btn btn-primary"><i class="fa fa-chevron-left" aria-hidden="true"></i></button>
                    <button id="next" type="button" class="btn btn-primary"><i class="fa fa-chevron-right" aria-hidden="true"></i></button>
                </div>
            </div>
        </div>

        <div class="row text-center mb-5">
            <ul class="dots-container col-md-12" role="navigation">
                <?php foreach ($questoes as $key => $questao) : ?>
                    <li class="progress-item li">
                        <a 
                            href="" 
                            class="progress-dot indicator" 
                            title="Question <?= $key + 1 ?> of <?= count($questoes) ?>, Unanswered" 
                            tabindex="-1">
                        </a>
                    </li>  
                <?php endforeach ?>
            </ul>
        </div>
    </div>

<?php include_once 'app/views/partials/footer.php'; ?>
