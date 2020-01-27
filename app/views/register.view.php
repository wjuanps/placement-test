<?php include_once 'app/views/partials/head.php'; ?>

<div class="w-75 mx-auto mt-5">
    <h2>PLACEMENT TEST - BASIC</h2>
    <hr class="hr mb-5" />

    <form id="form" action="/store" method="post" class="p-5" style="box-shadow: 0 0 10px rgba(0, 0, 0, .08)">
        <div class="form-row mb-3">
            <div class="form-group col-md-4">
                <label for="name">Nome</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Nome" />
            </div>

            <div class="form-group col-md-4">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Email" />
            </div>

            <div class="form-group col-md-4">
                <label for="phone">Telefone</label>
                <input type="phone" class="form-control" name="phone" id="phone" placeholder="Telefone" />
            </div>
        </div>
        
        <div class="form-row mb-3">
            <div class="form-group col-md-4">
                <label for="country">País</label>
                <select id="country" name="country" class="form-control">
                    <option selected disabled>Choose...</option>

                    <option value="brazil_3469034">Brasil</option>
                    <option value="usa_6252001">Estados Unidos</option>
                    <option value="japan_1861060">Japão</option>
                </select>
            </div>

            <div class="form-group col-md-4">
                <label for="state">Estado</label>
                <select id="state" name="state" class="form-control">
                    <option selected disabled>Choose...</option>
                </select>
            </div>

            <div class="form-group col-md-4">
                <label for="city">Cidade</label>
                <select id="city" name="city" class="form-control">
                    <option selected disabled>Choose...</option>
                </select>
            </div>
        </div>
        
        <div class="form-group mb-4">
            <h6>Ao me cadastrar, confirmo que lí e aceito os <a href="">Termos de Uso</a> e as <a href="">Politicas de Privacidade</a></h6>
        </div>

        <button type="submit" class="btn btn-primary">INICIAR TESTE</button>
    </form>
</div>

<?php include_once 'app/views/partials/footer.php'; ?>

<script>
    "use strict";

    window.onload = () => {
        try {
            let form = document.getElementById('form');

            form.addEventListener('submit', (event) => {
                event.preventDefault();

                localStorage.removeItem('placement');
                localStorage.removeItem('questions');

                event.currentTarget.submit();
            });

            let country = document.getElementById('country');
            country.addEventListener('change', (event) => {
                let city = document.getElementById('city');
                city.innerHTML = '<option value="">Choose ...</option>';

                geoNames.getPlaces(event.currentTarget.value, 'state');
            });

            let state = document.getElementById('state');
            state.addEventListener('change', (event) => geoNames.getPlaces(event.currentTarget.value, 'city'));
        } catch (error) { console.error(error); }
    }
</script>
