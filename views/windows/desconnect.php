<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta http-equiv="content-language" content="pt-BR">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#F59E0B">
    <title>Conexão com o banco perdida - CREDE</title>

    <link rel="icon" type="image/png" href="https://i.postimg.cc/0N0dsxrM/Bras-o-do-Cear-svg-removebg-preview.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        @keyframes pulse-warn {
            0%, 100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.45); }
            50% { box-shadow: 0 0 0 12px rgba(245, 158, 11, 0); }
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-amber-50 via-gray-100 to-gray-200 flex items-center justify-center p-6">
    <main class="w-full max-w-2xl">
        <section class="relative bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-[#F59E0B] via-[#FFA500] to-[#F59E0B]"></div>

            <div class="p-8 sm:p-10 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-[#F59E0B] to-[#D97706] text-white mb-6 animate-[pulse-warn_2.5s_infinite]">
                    <i class="fa-solid fa-database text-3xl"></i>
                </div>

        		<h1 class="font-[Poppins] text-2xl sm:text-3xl font-extrabold text-gray-800 mb-3">
                    Conexão com o banco de dados perdida
                </h1>
                <p class="text-gray-600 text-base sm:text-lg leading-relaxed mb-6">
                    Não foi possível comunicar com o banco de dados. Verifique sua conexão de rede ou aguarde enquanto restabelecemos o serviço.
                </p>

                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 sm:p-5 mb-6">
                    <p class="text-amber-800"><i class="fa-solid fa-rotate-right text-[#F59E0B] mr-2"></i>Tentaremos reconectar em <span id="countdown" class="font-semibold text-[#D97706]">30</span>s.</p>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-center gap-3">
                    <button type="button" onclick="manualReload()" class="inline-flex items-center justify-center px-5 py-3 rounded-xl text-white font-semibold bg-gradient-to-r from-[#F59E0B] to-[#D97706] hover:from-[#D97706] hover:to-[#F59E0B] transition-all shadow-md">
                        <i class="fa-solid fa-link mr-2"></i>
                        Tentar reconectar
                    </button>
                    <a href="mailto:suporte@sistema.com?subject=Banco%20desconectado" class="inline-flex items-center justify-center px-5 py-3 rounded-xl font-semibold text-[#1A3C34] bg-white border border-gray-200 hover:bg-gray-50 transition-all">
                        <i class="fa-solid fa-envelope mr-2"></i>
                        Contatar suporte
                    </a>
                    <a href="../../login.php" class="inline-flex items-center justify-center px-5 py-3 rounded-xl font-semibold text-[#1A3C34] bg-white border border-gray-200 hover:bg-gray-50 transition-all">
                        <i class="fa-solid fa-home mr-2"></i>
                        Voltar ao início
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
        let seconds = 30;
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


