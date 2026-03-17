<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Encontre Distribuidores')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- jQuery UI CSS (para autocomplete) -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <!-- Estilos customizados -->
    <style>
        :root {
            /* Cores do layout de busca */
            --cor-fundo: #7b9c49;
            --cor-header-card: #3d5a47;
            --cor-body-card: #f5e6d3;
            --cor-botao: #7b9b46;
            --cor-input-bg: #f8f9fa;

            /* Cores da identidade visual Haskell */
            --haskell-teal: #0d8784;
            --haskell-teal-dark: #0a6663;
            --haskell-teal-light: #10a19d;
            --haskell-pink: #e91e8c;
            --haskell-lime: #c3d933;
            --haskell-lime-dark: #a8bd2c;
        }

        /* === SOBRESCRITA DAS CLASSES BOOTSTRAP COM CORES HASKELL === */

        /* Botões Primary */
        .btn-primary {
            background-color: var(--haskell-teal) !important;
            border-color: var(--haskell-teal) !important;
        }
        .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
            background-color: var(--haskell-teal-dark) !important;
            border-color: var(--haskell-teal-dark) !important;
        }
        .btn-outline-primary {
            color: var(--haskell-teal) !important;
            border-color: var(--haskell-teal) !important;
        }
        .btn-outline-primary:hover {
            background-color: var(--haskell-teal) !important;
            border-color: var(--haskell-teal) !important;
            color: #fff !important;
        }

        /* Botões Success */
        .btn-success {
            background-color: var(--haskell-lime) !important;
            border-color: var(--haskell-lime) !important;
            color: #000 !important;
        }
        .btn-success:hover, .btn-success:focus {
            background-color: var(--haskell-lime-dark) !important;
            border-color: var(--haskell-lime-dark) !important;
            color: #000 !important;
        }

        /* Backgrounds */
        .bg-primary {
            background-color: var(--haskell-teal) !important;
        }
        .bg-success {
            background-color: var(--haskell-lime) !important;
        }

        /* Textos */
        .text-primary {
            color: var(--haskell-teal) !important;
        }
        .text-success {
            color: var(--haskell-lime-dark) !important;
        }

        /* Links */
        a {
            color: var(--haskell-teal);
        }
        a:hover {
            color: var(--haskell-pink);
        }

        /* Cards */
        .card-header.bg-primary {
            background-color: var(--haskell-teal) !important;
            border-color: var(--haskell-teal) !important;
        }

        /* Badges */
        .badge.bg-primary {
            background-color: var(--haskell-teal) !important;
        }
        .badge.bg-success {
            background-color: var(--haskell-lime) !important;
            color: #000 !important;
        }

        /* Forms */
        .form-control:focus {
            border-color: var(--haskell-teal);
            box-shadow: 0 0 0 0.2rem rgba(13, 135, 132, 0.25);
        }

        /* Alert Success */
        .alert-success {
            background-color: rgba(195, 217, 51, 0.2);
            border-color: var(--haskell-lime);
            color: #3d5a47;
        }

        /* Alert Info */
        .alert-info {
            background-color: rgba(13, 135, 132, 0.1);
            border-color: var(--haskell-teal);
            color: var(--haskell-teal-dark);
        }

        body {
            background-color: var(--cor-fundo);
            min-height: 100vh;
            margin: 0; /* Remove margem padrão que causa linha branca no iframe */
        }

        /* Campo de busca principal */
        .search-box {
            background: #fff;
            border-radius: 15px;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Em telas menores (iframe mobile), search-box ocupa toda a largura */
        @media (max-width: 768px) {
            .search-box {
                max-width: 100%;
            }
        }

        .search-box input {
            border: none;
            outline: none;
            flex: 1;
            font-size: 16px;
            color: #666;
            background: transparent;
        }

        .search-box input::placeholder {
            color: #999;
        }

        .search-box button {
            background: var(--cor-botao);
            border: 1px solid var(--cor-botao);
            border-radius: 10px;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            flex-shrink: 0; /* Impede que o botão encolha no mobile */
        }

        .search-box button:hover {
            background: var(--cor-header-card);
            border-color: var(--cor-header-card);
        }

        .search-icon {
            width: 24px;
            height: 24px;
            stroke: #fff;
            stroke-width: 2.5;
            fill: none;
        }

        /* Spinner de loading no botão de busca */
        .search-spinner {
            width: 24px;
            height: 24px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top: 3px solid #fff;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            display: none; /* Oculto por padrão, exibido via JS no submit */
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Card do distribuidor */
        .distributor-card {
            background: var(--cor-body-card);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            margin-bottom: 20px;
        }

        .distributor-card-header {
            background: var(--cor-header-card);
            color: #fff;
            padding: 20px 25px;
        }

        .distributor-card-header h3 {
            margin: 0;
            font-weight: bold;
            font-size: 1.4rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .distributor-card-body {
            padding: 25px;
            overflow: hidden; /* Impede que conteúdo vaze para fora do card */
        }

        /* Reduz padding no mobile para aproveitar melhor o espaço */
        @media (max-width: 768px) {
            .distributor-card-body {
                padding: 15px;
            }
        }

        /* Vendedor */
        .seller-section {
            margin-bottom: 25px;
        }

        .seller-section:last-child {
            margin-bottom: 0;
        }

        .seller-name {
            font-weight: bold;
            color: var(--cor-header-card);
            font-size: 1rem;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        /* Campos de contato */
        .contact-row {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .contact-field {
            flex: 1;
            min-width: 0; /* Permite que o flex item encolha abaixo do tamanho do conteúdo */
            background: #fff;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 14px;
            color: #555;
            border-left: 4px solid var(--cor-header-card);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .contact-btn {
            width: 45px;
            min-width: 45px; /* Impede que o botão encolha no mobile */
            height: 45px;
            background: var(--cor-botao);
            border: none;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 10px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            flex-shrink: 0; /* Garante que o botão não seja comprimido */
        }

        .contact-btn:hover {
            background: var(--cor-header-card);
            transform: scale(1.05);
        }

        .contact-btn svg,
        .contact-btn i {
            color: #fff;
            font-size: 20px;
        }

        /* Mensagem sem resultados */
        .no-results {
            background: rgba(255,255,255,0.9);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            max-width: 500px;
            margin: 30px auto;
        }

        .no-results h4 {
            color: var(--cor-header-card);
            margin-bottom: 15px;
        }

        .no-results p {
            color: #666;
        }

        /* Autocomplete customizado */
        .ui-autocomplete {
            max-height: 300px;
            overflow-y: auto;
            overflow-x: hidden;
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }

        .ui-menu-item-wrapper {
            padding: 10px 15px;
        }

        .ui-state-active {
            background: var(--cor-header-card) !important;
            border-color: var(--cor-header-card) !important;
        }
    </style>

    @yield('styles')
</head>
<body>
    <main class="py-2">
        @yield('content')
    </main>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @yield('scripts')

    <!-- Comunica altura do conteúdo para o iframe pai (redimensionamento automático) -->
    <script>
    (function() {
        // Só executa se estiver dentro de um iframe
        if (window.parent === window) return;

        var lastHeight = 0; // Armazena a última altura enviada para evitar envios repetidos
        var debounceTimer = null; // Timer para debounce — evita loop de redimensionamento

        // Envia a altura real do conteúdo para a janela pai via postMessage
        function sendHeightToParent() {
            // Debounce de 150ms: agrupa múltiplas mudanças rápidas em um único envio
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function() {
                var height = document.documentElement.scrollHeight;

                // Só envia se a altura mudou (evita loop infinito com o pai)
                if (height !== lastHeight) {
                    lastHeight = height;
                    window.parent.postMessage({
                        type: 'iframeResize',
                        height: height
                    }, '*');
                }
            }, 150);
        }

        // Envia ao carregar a página
        window.addEventListener('load', sendHeightToParent);

        // Envia quando o DOM muda (resultados de busca, formulário abre/fecha, etc.)
        var observer = new MutationObserver(sendHeightToParent);
        observer.observe(document.body, {
            childList: true,
            subtree: true,
            attributes: true,
            attributeFilter: ['style', 'class']
        });

        // Envia quando a janela é redimensionada (ex: rotação de tela)
        window.addEventListener('resize', sendHeightToParent);
    })();
    </script>
</body>
</html>
