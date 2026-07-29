@extends('layouts.forum')

@section('content')
<div class="card">
    <div class="eyebrow">● Álbum de fotos</div>
    <h2>Así trabajamos en nuestra huerta</h2>
    <p class="lead">Una galería visual con recuerdos, eventos y escenas que representan el espíritu del grupo.</p>

    @php
        $galleryDir = public_path('foto feria de ciencias');
        $galleryItems = [];
        if (is_dir($galleryDir)) {
            $files = scandir($galleryDir);
            $galleryItems = array_values(array_filter($files, function ($name) {
                return $name !== '.' && $name !== '..';
            }));
            $galleryItems = array_values(array_filter($galleryItems, function ($name) {
                $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'mp4'], true);
            }));
            usort($galleryItems, function ($a, $b) {
                $isVideoA = strtolower(pathinfo($a, PATHINFO_EXTENSION)) === 'mp4';
                $isVideoB = strtolower(pathinfo($b, PATHINFO_EXTENSION)) === 'mp4';
                if ($isVideoA !== $isVideoB) {
                    return $isVideoA ? -1 : 1;
                }
                return strnatcasecmp($a, $b);
            });
        }
    @endphp

    <div class="gallery-grid" data-gallery>
        @foreach($galleryItems as $index => $filename)
            @php $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); @endphp
            <button type="button" class="gallery-item" data-index="{{ $index }}" data-media-type="{{ $extension === 'mp4' ? 'video' : 'image' }}" aria-label="Ver {{ $extension === 'mp4' ? 'video' : 'foto' }} {{ $index + 1 }}">
                @if($extension === 'mp4')
                    <video controls preload="metadata" playsinline>
                        <source src="{{ asset('foto feria de ciencias/' . $filename) }}" type="video/mp4">
                    </video>
                @else
                    <img src="{{ asset('foto feria de ciencias/' . $filename) }}" alt="Foto feria de ciencias {{ $index + 1 }}">
                @endif
            </button>
        @endforeach
    </div>
</div>

<div id="album-lightbox" class="lightbox" aria-hidden="true">
    <button type="button" class="lightbox-close" aria-label="Cerrar galería">×</button>
    <button type="button" class="lightbox-nav prev" aria-label="Foto anterior">‹</button>
    <div class="lightbox-content">
        <img src="" alt="Foto ampliada" />
        <video controls preload="metadata" playsinline style="display:none;"></video>
    </div>
    <button type="button" class="lightbox-nav next" aria-label="Foto siguiente">›</button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var galleryItems = @json($galleryItems);
        var lightbox = document.getElementById('album-lightbox');
        var lightboxImg = lightbox.querySelector('.lightbox-content img');
        var lightboxVideo = lightbox.querySelector('.lightbox-content video');
        var galleryBase = '{{ asset('foto feria de ciencias') }}'.replace(/ /g, '%20');
        var currentIndex = 0;

        function openLightbox(index) {
            currentIndex = index;
            var filename = galleryItems[currentIndex];
            var extension = (filename.split('.').pop() || '').toLowerCase();
            var fullPath = galleryBase + '/' + encodeURIComponent(filename);

            if (extension === 'mp4') {
                lightboxImg.style.display = 'none';
                lightboxImg.src = '';
                lightboxVideo.style.display = 'block';
                lightboxVideo.pause();
                lightboxVideo.currentTime = 0;
                lightboxVideo.innerHTML = '<source src="' + fullPath + '" type="video/mp4">';
                lightboxVideo.load();
            } else {
                lightboxVideo.style.display = 'none';
                lightboxVideo.pause();
                lightboxVideo.innerHTML = '';
                lightboxImg.style.display = 'block';
                lightboxImg.src = fullPath;
                lightboxImg.alt = 'Foto feria de ciencias ' + (currentIndex + 1);
            }

            lightbox.classList.add('open');
            lightbox.setAttribute('aria-hidden', 'false');
        }

        function closeLightbox() {
            lightbox.classList.remove('open');
            lightbox.setAttribute('aria-hidden', 'true');
            lightboxImg.style.display = 'block';
            lightboxImg.src = '';
            lightboxVideo.style.display = 'none';
            lightboxVideo.pause();
            lightboxVideo.innerHTML = '';
        }

        function showNext() {
            openLightbox((currentIndex + 1) % galleryItems.length);
        }

        function showPrev() {
            openLightbox((currentIndex - 1 + galleryItems.length) % galleryItems.length);
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
