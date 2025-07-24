<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? 'Cotizador de Pérgolas - Sistema de Gestión y Cotización' }}</title>

<!-- SEO Meta Tags -->
<meta name="description" content="{{ $description ?? 'Sistema profesional para cotización y gestión de pérgolas. Genera cotizaciones precisas, gestiona clientes y administra proyectos de pérgolas de manera eficiente.' }}">
<meta name="keywords" content="cotizador pérgolas, gestión pérgolas, cotización pérgolas, sistema pérgolas, pérgolas precios, pérgolas presupuesto, estudio 3a">
<meta name="author" content="Cotizador Pérgolas">
<meta name="robots" content="index, follow">
<meta name="language" content="Spanish">
<meta name="geo.region" content="ES">
<meta name="geo.placename" content="España">

<!-- Open Graph Meta Tags (Facebook, LinkedIn) -->
<meta property="og:type" content="website">
<meta property="og:title" content="{{ $title ?? 'Cotizador de Pérgolas - Sistema de Gestión y Cotización' }}">
<meta property="og:description" content="{{ $description ?? 'Sistema profesional para cotización y gestión de pérgolas. Genera cotizaciones precisas y gestiona proyectos eficientemente.' }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:site_name" content="Cotizador Pérgolas">
<meta property="og:locale" content="es_ES">
<meta property="og:image" content="{{ asset('img/logo.webp') }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="Cotizador de Pérgolas - Sistema de Gestión">

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title ?? 'Cotizador de Pérgolas - Sistema de Gestión y Cotización' }}">
<meta name="twitter:description" content="{{ $description ?? 'Sistema profesional para cotización y gestión de pérgolas.' }}">
<meta name="twitter:image" content="{{ asset('img/logo.webp') }}">
<meta name="twitter:image:alt" content="Cotizador de Pérgolas">

<!-- Favicon -->
<link rel="icon" href="{{ asset('img/logo.webp') }}" type="image/png">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/logo.webp') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/logo.webp') }}">

<!-- Canonical URL -->
<link rel="canonical" href="{{ url()->current() }}">

<!-- Preconnect for performance -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
