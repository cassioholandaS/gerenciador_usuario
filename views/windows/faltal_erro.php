<?php $incidentId = strtoupper(substr(md5(uniqid('', true)), 0, 8)); $timestamp = date('d/m/Y H:i'); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta http-equiv="content-language" content="pt-BR">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#EF4444">
    <title>Erro Fatal - CREDE</title>

    <link rel="icon" type="image/png" href="https://i.postimg.cc/0N0dsxrM/Bras-o-do-Cear-svg-removebg-preview.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        @keyframes pulse-danger {
            0%, 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.45); }
            50% { box-shadow: 0 0 0 12px rgba(239, 68, 68, 0); }
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-red-50 via-gray-100 to-gray-200 flex items-center justify-center p-6">
    <main class="w-full max-w-2xl">
        <section class="relative bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-[#EF4444] via-[#FFA500] to-[#EF4444]"></div>

            <div class="p-8 sm:p-10 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-[#EF4444] to-[#DC2626] text-white mb-6 animate-[pulse-danger_2.5s_infinite]">
                    <i class="fa-solid fa-triangle-exclamation text-3xl"></i>
                </div>

                <h1 class="font-[Poppins] text-2xl sm:text-3xl font-extrabold text-gray-800 mb-3">
                    Ocorreu um erro inesperado
                </h1>
                <p class="text-gray-600 text-base sm:text-lg leading-relaxed mb-6">
                    Pedimos desculpas pelo inconveniente. Anote o código do incidente abaixo e, se o problema persistir, entre em contato com o suporte.
                </p>

                <div class="bg-red-50 border border-red-200 rounded-xl p-4 sm:p-5 text-left mb-6">
                    <div class="flex items-center text-red-700 font-semibold mb-2"><i class="fa-solid fa-bug mr-2"></i>Detalhes</div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm text-red-800">
                        <div class="flex items-center"><span class="font-semibold mr-2">Código do incidente:</span> <span class="select-all"><?php echo $incidentId; ?></span></div>
                        <div class="flex items-center"><span class="font-semibold mr-2">Data/Hora:</span> <span><?php echo $timestamp; ?></span></div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-center gap-3">
                    <button type="button" onclick="location.reload()" class="inline-flex items-center justify-center px-5 py-3 rounded-xl text-white font-semibold bg-gradient-to-r from-[#EF4444] to-[#DC2626] hover:from-[#DC2626] hover:to-[#EF4444] transition-all shadow-md">
                        <i class="fa-solid fa-rotate-right mr-2"></i>
                        Tentar novamente
                    </button>
                    <a href="../../login.php" class="inline-flex items-center justify-center px-5 py-3 rounded-xl font-semibold text-[#1A3C34] bg-white border border-gray-200 hover:bg-gray-50 transition-all">
                        <i class="fa-solid fa-home mr-2"></i>
                        Voltar ao início
                    </a>
                    <a href="mailto:suporte@sistema.com?subject=Erro%20Fatal%20-%20<?php echo $incidentId; ?>" class="inline-flex items-center justify-center px-5 py-3 rounded-xl font-semibold text-[#1A3C34] bg-white border border-gray-200 hover:bg-gray-50 transition-all">
                        <i class="fa-solid fa-envelope mr-2"></i>
                        Contatar suporte
                    </a>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 text-center text-sm text-gray-500">
                <div class="flex items-center justify-center gap-2">
                    <img src="https://i.postimg.cc/0N0dsxrM/Bras-o-do-Cear-svg-removebg-preview.png" alt="Logo Ceará" class="w-5 h-5 object-contain"/>
                    <span>CREDE • Coordenadoria Regional de Desenvolvimento da Educação</span>
                </div>
            </div>
        </section>
    </main>
</body>
</html>


