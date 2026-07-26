@extends('layouts.forum')

@section('content')
<div class="card">
    <div class="eyebrow">● Álbum de fotos</div>
    <h2>Así trabajamos en nuestra huerta</h2>
    <p class="lead">Una galería visual con recuerdos, eventos y escenas que representan el espíritu del grupo.</p>

    @php
        $galleryDir = public_path('foto feria de ciencias');
        $imageFiles = [];
        if (is_dir($galleryDir)) {
            $imageFiles = glob($galleryDir.'/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
            $imageFiles = array_map('basename', $imageFiles);
            sort($imageFiles, SORT_NATURAL);
        }
    @endphp

    <div class="gallery-grid" data-gallery>
        @foreach($imageFiles as $index => $filename)
            <button type="button" class="gallery-item" data-index="{{ $index }}" aria-label="Ver foto {{ $index + 1 }}">
                <img src="{{ asset('foto feria de ciencias/' . $filename) }}" alt="Foto feria de ciencias {{ $index + 1 }}">
            </button>
        @endforeach
    </div>
</div>

<div id="album-lightbox" class="lightbox" aria-hidden="true">
    <button type="button" class="lightbox-close" aria-label="Cerrar galería">×</button>
    <button type="button" class="lightbox-nav prev" aria-label="Foto anterior">‹</button>
    <div class="lightbox-content">
        <img src="" alt="Foto ampliada" />
    </div>
    <button type="button" class="lightbox-nav next" aria-label="Foto siguiente">›</button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var imageFiles = @json($imageFiles);
        var lightbox = document.getElementById('album-lightbox');
        var lightboxImg = lightbox.querySelector('.lightbox-content img');
        var galleryBase = '{{ asset('foto feria de ciencias') }}'.replace(/ /g, '%20');
        var currentIndex = 0;

        function openLightbox(index) {
            currentIndex = index;
            var filename = imageFiles[currentIndex];
            lightboxImg.src = galleryBase + '/' + encodeURIComponent(filename);
            lightboxImg.alt = 'Foto feria de ciencias ' + (currentIndex + 1);
            lightbox.classList.add('open');
            lightbox.setAttribute('aria-hidden', 'false');
        }

        function closeLightbox() {
            lightbox.classList.remove('open');
            lightbox.setAttribute('aria-hidden', 'true');
            lightboxImg.src = '';
        }

        function showNext() {
            openLightbox((currentIndex + 1) % imageFiles.length);
        }

        function showPrev() {
            openLightbox((currentIndex - 1 + imageFiles.length) % imageFiles.length);
        }

        document.querySelectorAll('.gallery-item').forEach(function (button) {
            button.addEventListener('click', function () {
                openLightbox(parseInt(button.dataset.index, 10));
            });
        });

        lightbox.querySelector('.lightbox-close').addEventListener('click', closeLightbox);
        lightbox.querySelector('.lightbox-nav.prev').addEventListener('click', showPrev);
        lightbox.querySelector('.lightbox-nav.next').addEventListener('click', showNext);

        lightbox.addEventListener('click', function (event) {
            if (event.target === lightbox) {
                closeLightbox();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (!lightbox.classList.contains('open')) return;
            if (event.key === 'Escape') closeLightbox();
            if (event.key === 'ArrowRight') showNext();
            if (event.key === 'ArrowLeft') showPrev();
        });
    });
</script>
@endsection
