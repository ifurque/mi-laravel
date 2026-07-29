@extends('layouts.forum')

@section('content')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Flip cards on click
        document.querySelectorAll('.flip-card').forEach(function (card) {
            card.addEventListener('click', function () {
                document.querySelectorAll('.flip-card').forEach(function (item) {
                    if (item !== card) {
                        item.classList.remove('is-flipped');
                    }
                });
                card.classList.toggle('is-flipped');
            });
        });

        // Lightbox for team photos
        var lightbox = document.createElement('div');
        lightbox.className = 'lightbox';
        lightbox.addEventListener('click', function () { lightbox.style.display = 'none'; lightbox.innerHTML = ''; });
        document.body.appendChild(lightbox);

        document.querySelectorAll('.team-photo').forEach(function(img){
            img.addEventListener('click', function (e) {
                e.stopPropagation();
                var big = document.createElement('img');
                big.src = img.src;
                lightbox.innerHTML = '';
                lightbox.appendChild(big);
                lightbox.style.display = 'flex';
            });
        });

        // Close on ESC
        document.addEventListener('keydown', function(ev){ if (ev.key === 'Escape') { lightbox.style.display = 'none'; lightbox.innerHTML = ''; } });
    });
</script>

<section class="hero">
    <div class="card">
        <h1>Los guardianes de la huerta</h1>
        <p class="lead">
            En nuestra escuela, cada curso tiene una misión dentro de
            la huerta. En el presente ciclo lectivo, los alumnos de sexto serían los
            encargados de controlar los diferentes tipos de plagas que se fueran
            presentando. La duda que surgió fue, al ser la nuestra una huerta agroecológica, ¿cómo podríamos tener una huerta sana sin usar químicos?
        </p>
        <div class="actions">
            <a href="{{ route('album') }}" class="btn btn-primary">Ver álbum</a>
            <a href="#quienes-somos" class="btn btn-secondary">Ver quiénes somos</a>
            <a href="https://forms.gle/VjqMw8opy645jAP67" target="_blank" rel="noopener" class="btn-survey">Ayúdanos a completar esta encuesta</a>
        </div>
        <div class="sr-only">Recetas Fertilizantes 100% Natural</div>
    </div>
</section>

<section class="card" id="quienes-somos">
    <h2 style="font-size: 2rem; margin-bottom: 1rem;">¿Quiénes somos?</h2>
    <div class="flip-card flip-card-large" style="max-width:100%;">
        <div class="flip-card-inner">
            <div class="flip-face flip-front">
                <h3>6to año A</h3>
                <ul class="team-list">
                    <li>ACUÑA, Bianca Angelina</li>
                    <li>AMEND, Aaron Alexis</li>
                    <li>ANDINO, Uriel</li>
                    <li>BALVERDI, Cristian Benjamin Alhi</li>
                    <li>DORADO, Emma Andrea</li>
                    <li>FERNANDEZ, Alma Serena</li>
                    <li>GODOY, Lisandro Tomas</li>
                    <li>GONZALEZ, Giovanni</li>
                    <li>GONZALEZ SAAVEDRA, Brandon Natanael</li>
                    <li>MARTINEZ, Milo Ryan</li>
                    <li>MOLINAS, Pedro Santino</li>
                    <li>MOREYRA, Ezequiel</li>
                    <li>MOYANO, Sofia Soledad</li>
                    <li>ORTELLI, Ludmila Magali</li>
                    <li>ORTELLI, Morena</li>
                    <li>ORUE FRUTOS, Maira Dahiana</li>
                    <li>PEREZLINDO, Bautista Emanuel</li>
                    <li>SEQUEIRA, Renata</li>
                    <li>TEMECHUK, Bianca Marcela</li>
                    <li>TORRES, Yoseli Yaima</li>
                    <li>VALENZUELA, Maiten Nahiara</li>
                </ul>
            </div>
            <div class="flip-face flip-back">
                <img class="team-photo" src="/images/ep33.jpg" alt="Foto del equipo de E.P n°33 Horacio Rega Molina" />
            </div>
        </div>
    </div>
</section>

<section class="card hero-what">
    <h1>¿Qué es una huerta agroecológica?</h1>
    <p class="lead">Una huerta agroecológica es un espacio de cultivo para producir alimentos sanos y nutritivos. No utiliza agrotóxicos ni fertilizantes químicos, prioriza la biodiversidad y cuida activamente la vida del suelo.</p>
</section>

<section class="card">
    <h1>Consejos para mantener tu huerta sana</h1>
    <ul>
        <li>Sembrá plantas aromáticas para ahuyentar insectos y/o plagas. Por ejemplo: romero, lavanda, tomillo, salvia.</li>
        <li>Sembrá plantas de colores anaranjado o amarillo para atraer a las plagas para que no ataquen a las hortalizas, y a insectos benéficos que se encargan de controlar a los perjudiciales. Por ejemplo: caléndula o copete.</li>
        <li>Elaborá biopreparados caseros para combatir las plagas.</li>
    </ul>
</section>

<section class="card hero-repelentes">
    <h1>Biopreparados fáciles</h1>
    <ul>
        <li><strong>Agua de ajo:</strong> Machacar 4 o 5 dientes de ajo. Dejar reposar en un litro de agua durante 24 horas. Hervir la mezcla 20 minutos. Enfriar y colar. Pulverizar sobre las plantas cada 3 o 5 días.</li>
        <li><strong>Bolitas de paraíso:</strong> Colocar un puñado de bolitas de paraíso en medio litro de agua. Dejar fermentar 10 días, destapado. Diluir en un balde de agua y regar la huerta.</li>
        <li><strong>Colocación de arroz partido y cáscaras de cítricos:</strong> Colocar en distintas zonas de la huerta para ahuyentar hormigas negras.</li>
    </ul>
</section>

@endsection
