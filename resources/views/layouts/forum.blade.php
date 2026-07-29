<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foro Comunidad</title>
    <style>
        :root {
            color-scheme: light;
            --bg: #f7efe5;
            --panel: #fffdf8;
            --panel-2: #f2e5d0;
            --text: #2f2a22;
            --muted: #6d6256;
            --accent: #5c7a3a;
            --accent-2: #8c5a2b;
            --border: rgba(87, 66, 43, 0.12);
        }

        * { box-sizing: border-box; }
        html, body { height: 100%; }
        body {
            margin: 0;
            font-family: Inter, "Segoe UI", Roboto, Arial, sans-serif;
            background: linear-gradient(135deg, #f9f2e7 0%, #e7f0dc 100%);
            color: var(--text);
            min-height: 100vh;
        }

        a { color: inherit; text-decoration: none; }

        /* Topbar */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 40;
            background: rgba(255,253,248,0.95);
            border-bottom: 1px solid var(--border);
        }

        .topbar-inner {
            max-width: 1120px;
            margin: 0 auto;
            padding: 0.6rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .brand { display: inline-flex; align-items:center; gap:0.75rem; font-weight:800; }
        .brand img { width:40px; height:40px; border-radius:50%; object-fit:cover; }

        .nav-links { display:flex; gap:0.8rem; }
        .nav-links a { padding:0.35rem 0.75rem; border-radius:8px; color:var(--muted); font-weight:700; }
        .nav-links a.active, .nav-links a:hover { background: rgba(92,122,58,0.12); color:var(--accent); }

        /* Container and cards */
        .container { max-width: 1120px; margin: 0 auto; padding: 1.5rem; }
        .card { background: var(--panel); border:1px solid var(--border); border-radius:18px; padding:1.25rem; box-shadow: 0 12px 32px rgba(87,66,43,0.06); }

        /* Hero */
        .hero { margin-bottom: 1.25rem; }
        .hero .card { padding: 1rem 1.25rem; min-height: 220px; display:flex; flex-direction:column; gap:0.75rem; }
        .hero h1 { font-size: clamp(1.8rem, 3.8vw, 2.6rem); margin:0; }
        .hero-what h1 { color: var(--text); }
        .lead { color:var(--muted); line-height:1.6; }

        .actions { display:flex; gap:0.75rem; align-items:center; }
        .btn { padding:0.6rem 0.9rem; border-radius:999px; font-weight:700; }
        .btn-primary { background: linear-gradient(135deg,var(--accent),var(--accent-2)); color:white; }
        .btn-secondary { background: rgba(0,0,0,0.04); }
        .btn-survey { border:1px solid rgba(92,122,58,0.12); padding:0.45rem 0.8rem; border-radius:999px; color:var(--accent); font-weight:700; }

        /* Flip card (clean implementation) */
        .flip-wrapper { margin-top: 1rem; }
        .flip-card { width:100%; perspective:1500px; }
        .flip-card-inner { position:relative; width:100%; min-height:560px; transition: transform .7s; transform-style: preserve-3d; }
        .flip-card.is-flipped .flip-card-inner { transform: rotateY(180deg); }

        .flip-face { position: absolute; inset: 0; border-radius: 14px; padding: 1.25rem; backface-visibility: hidden; border:1px solid var(--border); display:flex; flex-direction:column; gap:1rem; background:var(--panel); overflow:auto; }
        .flip-front { background: linear-gradient(135deg,#eef6e3 0%, #f8efe1 100%); }
        .flip-back { transform: rotateY(180deg); background: linear-gradient(135deg,#dcecc3 0%, #f1e2c8 100%); display:flex; align-items:center; justify-content:center; padding:1rem; }
        /* Make the back photo smaller so card borders remain visible and easier to click */
        .flip-back .team-photo { width: calc(100% - 2rem); height: calc(100% - 2rem); object-fit:cover; border-radius:12px; display:block; }

        /* Team list fills remaining space in front face */
        .flip-front .team-list { display:grid; grid-template-columns: repeat(3,1fr); gap:0.6rem 1rem; margin:0; padding:0; align-content:start; width:100%; }
        .flip-front .team-list li { list-style:none; padding:0.5rem 0.6rem; border-radius:8px; background: rgba(255,255,255,0.03); font-weight:700; }

        /* Lightbox */
        .lightbox { position:fixed; inset:0; display:none; background: rgba(0,0,0,0.92); align-items:center; justify-content:center; z-index:9999; padding:1.5rem; }
        .lightbox.open { display:flex; }
        .lightbox .lightbox-content { max-width: 90vw; max-height: 90vh; width: 100%; display:flex; align-items:center; justify-content:center; position:relative; }
        .lightbox img,
        .lightbox video { max-width:100%; max-height:100%; border-radius:12px; box-shadow: 0 24px 60px rgba(0,0,0,0.35); object-fit: contain; }
        .lightbox video { width: min(90vw, 960px); height: auto; max-height: 85vh; background: #000; }
        .lightbox-close,
        .lightbox-nav {
            position:absolute;
            border:none;
            background: rgba(255,255,255,0.16);
            color: white;
            width:44px;
            height:44px;
            border-radius:50%;
            cursor:pointer;
            font-size:1.6rem;
            display:flex;
            align-items:center;
            justify-content:center;
            transition: background .2s ease;
        }
        .lightbox-close { top:1rem; right:1rem; }
        .lightbox-nav.prev { left:1rem; top:50%; transform:translateY(-50%); }
        .lightbox-nav.next { right:1rem; top:50%; transform:translateY(-50%); }
        .lightbox-close:hover,
        .lightbox-nav:hover { background: rgba(255,255,255,0.28); }

        .gallery-grid { display:grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap:1rem; margin-top:1.25rem; }
        .gallery-item { border:none; background:none; padding:0; cursor:pointer; overflow:hidden; border-radius:20px; box-shadow: 0 16px 40px rgba(0,0,0,0.08); }
        .gallery-item img,
        .gallery-item video { width:100%; height:240px; object-fit:cover; display:block; transition: transform .3s ease; background:#000; }
        .gallery-item:hover img,
        .gallery-item:hover video { transform: scale(1.04); }

        /* Responsive */
        @media (max-width: 980px) { .flip-front .team-list { grid-template-columns: repeat(2,1fr); } .flip-card-inner { min-height:620px; } }
        @media (max-width: 680px) { .flip-card-inner { min-height:540px; } }
        @media (max-width: 580px) { .flip-front .team-list { grid-template-columns: 1fr; } .topbar-inner { padding:0.5rem; } .hero .card { min-height: auto; } .flip-card-inner { min-height: 480px; } .flip-back .team-photo { width: calc(100% - 1.5rem); height: calc(100% - 1.5rem); } .flip-face { overflow:auto; } }

        @media (max-width: 860px) {
            .gallery-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 580px) {
            .gallery-grid { grid-template-columns: 1fr; }
            .gallery-item img,
            .gallery-item video { height: 200px; }
        }

        @media (max-width: 480px) {
            .flip-card .flip-face { padding: 1rem; }
            .flip-card-inner { min-height: 420px; }
            .flip-back .team-photo { width: calc(100% - 1rem); height: calc(100% - 1rem); }
        }

        /* Utility */
        .sr-only { position:absolute !important; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0 0 0 0); border:0; }
        @media (max-width: 860px) {
            .hero { grid-template-columns: 1fr; }
            .gallery { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .flip-grid { grid-template-columns: 1fr 1fr; }
            .flip-card { min-height: 320px; }
            .flip-card-large { min-height: 620px; }
        }

        @media (max-width: 580px) {
            .topbar-inner { flex-direction: column; align-items: flex-start; }
            .nav-links { width: 100%; }
            .brand { font-size: 0.9rem; }
            .brand img { width: 36px; height: 36px; }
            .gallery { grid-template-columns: 1fr; }
            .stats { grid-template-columns: 1fr; }
            .flip-grid { grid-template-columns: 1fr; }
            .flip-card { min-height: 320px; }
            .flip-card-large { min-height: 520px; }
            .team-list { column-count: 1; }
        }

        @media (min-width: 1120px) {
            .team-list { column-count: 3; }
        }
        /* Add consistent vertical spacing between section cards */
        section.card {
            margin: 2rem 0;
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <a href="{{ route('home') }}" class="brand">
                <img src="data:image/jpeg;base64,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" alt="Logo de Los guardianes de la huerta" />
                <span>E.P n°33 Horacio Rega Molina</span>
            </a>
            <nav class="nav-links">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Inicio</a>
                <a href="{{ route('album') }}" class="{{ request()->routeIs('album') ? 'active' : '' }}">Álbum</a>
            </nav>
        </div>
    </header>

    <main class="container">
        @yield('content')
    </main>
</body>
</html>
