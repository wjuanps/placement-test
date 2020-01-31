<?php include_once 'app/views/partials/head.php'; ?>

  <div class="w-75 mx-auto mt-5">
    <h2>PLACEMENT TEST</h2>

    <hr class="hr" />

    <div class="row">
        <div class="col-md-4 mb-2">
          <div class="card">
            <h5 class="card-header bg-primary text-white text-right">INFORMATION</h5>
            <img class="card-img-top img-fluid" src="public/img/placement_test.png" alt="">
            <ul class="list-group list-group-flush">
              <li class="list-group-item"><i class="fa fa-clock-o" aria-hidden="true"></i> Length <span class="float-right">30 minutes</span></li>
              <li class="list-group-item"><i class="fa fa-graduation-cap" aria-hidden="true"></i> Subject <span class="float-right">Test</span></li>
            </ul>
          </div>
        </div>
  
        <div class="col-md-8">
          <div class="card">
            <h5 class="card-header bg-primary text-white text-right">ABOUT THIS TRAINING</h5>
            <div class="card-body">
              <p class="card-text">Agora você irá realizar seu  <strong>Placement Test</strong>  ou <strong>Teste de Nivelamento</strong>.</p>
              <p class="card-text">Faça a prova com calma e em um ambiente tranquilo.</p>
              <p class="card-text">Você terá até 30 minutos para responder a 50 perguntas.</p>
              <p class="card-text">Se você queira deixar alguma pergunta para responder depois, observe as marcas(<strong>•••••••••••</strong>) que indicam a(s) pergunta(s) que faltam. Para voltar, basta clicar nas bolinhas não marcadas.</p>
              <p class="card-text">Após responder a última pergunta, clique em <strong>FINISH</strong> para finalizar o teste.</p>
              <p class="card-text">Boa Prova</p>
            </div>

            <div class="card-footer">
              <div class="row" id="button">

              </div>
            </div>
          </div>
        </div>
    </div>
  </div>

<?php include_once 'app/views/partials/footer.php'; ?>

<script>
  window.onload = () => {
    let button    = document.getElementById('button');
    let questions = localStorage.getItem('questions');

    let result = JSON.parse(localStorage.getItem('result'));
    if (questions != null && questions != undefined) {
      buttons = `<a href="/register" class="btn btn-primary col-md-6" style="border-radius: 0">INICIAR NOVO TESTE</a>`;

      buttons += (result != null && result != undefined)
                  ? `<button id="result" class="btn btn-success col-md-6" style="border-radius: 0">VISUALIZAR RESULTADO</button>`
                  : `<a href="/test-your-english" class="btn btn-success col-md-6" style="border-radius: 0">CONTINUAR TESTE</a>`;

      button.innerHTML = buttons;
    } else {
      button.innerHTML = `
        <a href="/register" class="btn btn-primary col-md-12" style="border-radius: 0">INICIAR TESTE</a>
      `;
    }

    document.getElementById('result')
      .addEventListener(
        'click', 
        () => window.open(`/result?total=${ result.total }&percent=${ result.percent }`, '_self')
      );
  }

  const visualizarResultado = (total, percent) => {
    console.log(result);
    location.pathname = `/result?total=${ result.total }&percent=${ result.percent }`;
  }
</script>
