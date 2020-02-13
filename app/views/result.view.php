<?php include_once 'app/views/partials/head.php'; ?>

	<input type="hidden" id="total" value="<?= $total ?>" />
	<input type="hidden" id="percent" value="<?= $percent ?>" />

  <div class="w-75 mx-auto mt-5">
		<h2>PLACEMENT TEST - BASIC</h2>

		<hr class="hr" />

		<div class="align-middle text-center">
			<h3 class="mt-4">Seu resultado / Your result:</h3>

			<div class="w-50 row mx-auto mt-4">
				<div class="col-md-8 mb-4">
					<div class="progress">
						<div
							id="progress"
							class="progress-bar bg-success" 
							role="progressbar" 
							aria-valuenow="0" 
							aria-valuemin="0" 
							aria-valuemax="100">
						</div>
					</div>
				</div>

				<div style="margin-top: -10px" class="col-md-4">
					<span 
						class="font-weight-bold" 
						style="font-size: 18pt">
						
							<?= $total ?> 
							<span class="font-weight-light">/</span> 50
					</span>
				</div>
			</div>

			<div class="w-50 row mx-auto mt-4">
				<h5 class="text-info font-weight-bold">
					<?php if ($percent <= 30) echo 'Your level is Beginner - Module One / Módulo 1'; ?>
					<?php if ($percent > 30 && $percent <= 48) echo 'Your level is Elementary - Module One / Módulo 1'; ?>
					<?php if ($percent > 48 && $percent <= 84) echo 'Your level is Pre-Intermediate - Module Two / Módulo 2'; ?>
					<?php if ($percent > 84 && $percent <= 98) echo 'Your level is Intermediate - Module Three / Módulo 3'; ?>
					<?php if ($percent > 98) echo 'Your level is Upper-Intermediate - Module Four / Módulo 4'; ?>
				</h5>
			</div>

			<div class="row w-50 mx-auto mt-5">
				<a 
					href="<?= $url ?>"
					target="_blank"
					class="btn btn-danger btn-block text-uppercase p-2" 
					style="border-radius: 0">
					
					Matricule-se agora e ganhe um desconto
				</a>
			</div>

			<div class="row w-50 mx-auto mt-3">
				<!-- <a 
					class="btn btn-primary col-md-6 text-uppercase" 
					style="border-radius: 0" 
					href="https://www.facebook.com/sharer/sharer.php?u=example.org" 
					target="_blank">

					<i class="fa fa-facebook"></i> &nbsp;&nbsp;&nbsp; compartilhar
				</a> -->

				<button 
					id="suporte"
					class="btn btn-success col-md-12 text-uppercase" 
					style="border-radius: 0">

					<i class="fa fa-whatsapp" aria-hidden="true"></i> &nbsp;&nbsp;&nbsp; fale conosco
				</button>
			</div>
		</div>
  </div>

<?php include_once 'app/views/partials/footer.php'; ?>

<script>

	window.onload = () => {
		var progress = document.getElementById('progress');
		var myVar    = setInterval(myTimer, 1);

		const total   = document.getElementById('total');
		const percent = document.getElementById('percent');

		localStorage.setItem('result', JSON.stringify({ "total": total.value, "percent": percent.value }));

		var i = 1;

		function myTimer() {
			i += 1;

			if (i <= percent.value) {
				progress.style.width = `${ i }%`;
			} else {
				myStopFunction();
			}
		}

		function myStopFunction() {
			clearInterval(myVar);
		}

		document.getElementById('suporte').addEventListener('click', (event) => {
			window.open('https://wa.me/5511942025955');
		});
	}

</script>
