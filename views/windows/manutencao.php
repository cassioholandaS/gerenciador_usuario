<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta http-equiv="content-language" content="pt-BR">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#005A24">
    <title>Estamos em manutenção - CREDE</title>

    <link rel="icon" type="image/png" href="https://i.postimg.cc/0N0dsxrM/Bras-o-do-Cear-svg-removebg-preview.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(0, 90, 36, 0.45); }
            50% { box-shadow: 0 0 0 12px rgba(0, 90, 36, 0); }
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-gray-50 via-gray-100 to-gray-200 flex items-center justify-center p-6">
    <main class="w-full max-w-2xl">
        <section class="relative bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-[#005A24] via-[#FFA500] to-[#005A24]"></div>

            <div class="p-8 sm:p-10 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-[#005A24] to-[#1A3C34] text-white mb-6 animate-[pulse-glow_2.5s_infinite]">
                    <i class="fa-solid fa-screwdriver-wrench text-3xl"></i>
                </div>

                <h1 class="font-[Poppins] text-2xl sm:text-3xl font-extrabold text-gray-800 mb-3">
                    Estamos em manutenção
                </h1>
                <p class="text-gray-600 text-base sm:text-lg leading-relaxed mb-6">
                    Estamos trabalhando para melhorar sua experiência. O sistema ficará indisponível por alguns instantes.
                </p>

                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 sm:p-5 mb-6">
                    <p id="retryText" class="text-gray-700"><i class="fa-solid fa-rotate-right text-[#005A24] mr-2"></i>Tentaremos novamente em <span id="countdown" class="font-semibold text-[#005A24]">60</span>s.</p>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-center gap-3">
                    <button type="button" onclick="manualReload()" class="inline-flex items-center justify-center px-5 py-3 rounded-xl text-white font-semibold bg-gradient-to-r from-[#005A24] to-[#1A3C34] hover:from-[#1A3C34] hover:to-[#005A24] transition-all shadow-md">
                        <i class="fa-solid fa-refresh mr-2"></i>
                        Atualizar página
                    </button>
                    <a href="../../login.php" class="inline-flex items-center justify-center px-5 py-3 rounded-xl font-semibold text-[#005A24] bg-white border border-[#005A24]/30 hover:bg-[#005A24]/5 transition-all">
                        <i class="fa-solid fa-home mr-2"></i>
                        Voltar ao início
                    </a>
                    <a href="mailto:suporte@sistema.com" class="inline-flex items-center justify-center px-5 py-3 rounded-xl font-semibold text-[#1A3C34] bg-white border border-gray-200 hover:bg-gray-50 transition-all">
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

    <script>
        let seconds = 60;
        const el = document.getElementById('countdown');
        const tick = () => {
            seconds--;
            if (seconds < 0) {
                location.reload();
                return;
            }
            el.textContent = String(seconds);
        };
        const intervalId = setInterval(tick, 1000);

        function manualReload() {
            clearInterval(intervalId);
            location.reload();
        }
    </script>
</body>
</html>


