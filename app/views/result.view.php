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
						aria-valuenow="50" 
						aria-valuemin="0" 
						aria-valuemax="100">
						</div>
					</div>
				</div>

				<div style="margin-top: -10px" class="col-md-4">
					<span class="font-weight-bold" style="font-size: 18pt"><?= $total ?> <span class="font-weight-light">/</span> 50</span>
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
	}
</script>
