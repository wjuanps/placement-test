<?php include_once 'app/views/partials/head.php'; ?>

  <div class="w-75 mx-auto mt-5">
        <h2>PLACEMENT TEST - BASIC</h2>

        <hr class="hr" />

        <?php foreach ($questoes as $key => $questao) : ?>
            <div class="row caroucel <?= $key > 0 ? 'hidden' : '' ?>">
                <form class="form col-md-12">
                    <h3 class="mb-4"><?= $questao['enunciado'] ?></h3>

                    <?php foreach ($questao['alternativas'] as $alternativa) : ?>
                        <div class="inputGroup">
                            <input id="<?= $alternativa->id ?>" name="radio" type="radio"/>
                            <label for="<?= $alternativa->id ?>"><?= $alternativa->resposta ?></label>
                        </div>
                    <?php endforeach ?>
                </form>
            </div>
        <?php endforeach ?>

        <div class="row">
            <ul class="dots-container" role="navigation">
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
