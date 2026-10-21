<?php
require_once(__DIR__ . '/../models/sessions.php');
$session = new sessions();
$session->autenticar_session();
$session->tempo_session();

require_once(__DIR__ . '/../models/model.select.php');
$select = new select();
$sistemas = $select->listar_sistemas();
$tipos = $select->listar_tipos_usuarios_com_sistemas();
$falhaCarregamento = $sistemas === false || $tipos === false;

$status = '';
foreach (['criado', 'ja_existe', 'erro', 'falha'] as $possivelStatus) {
    if (isset($_GET[$possivelStatus])) {
        $status = $possivelStatus;
        break;
    }
}
$mensagens = [
    'criado' => ['Tipo de usuário cadastrado e vinculado ao sistema com sucesso.', true],
    'ja_existe' => ['Esse tipo já está cadastrado para o sistema selecionado.', false],
    'erro' => ['Informe um nome e um sistema válido para o tipo de usuário.', false],
    'falha' => ['Não foi possível cadastrar o tipo de usuário. Verifique a conexão e tente novamente.', false],
];
$mensagem = isset($mensagens[$status]) ? $mensagens[$status] : null;
$sistemas = is_array($sistemas) ? $sistemas : [];
$tipos = is_array($tipos) ? $tipos : [];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="content-language" content="pt-BR">
    <title>Tipos de Usuário - STGM</title>
    <link rel="icon" type="image/png" href="https://i.postimg.cc/0N0dsxrM/Bras-o-do-Cear-svg-removebg-preview.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/theme.css" rel="stylesheet">
    <script src="../assets/theme.js" defer></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#005A24',
                        secondary: '#FFA500',
                        dark: '#1A3C34',
                        light: '#F8FAF9'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Poppins', 'sans-serif']
                    },
                    backgroundImage: {
                        'gradient-primary': 'linear-gradient(135deg, #005A24 0%, #1A3C34 100%)'
                    }
                }
            }
        }
    </script>
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #F8FAF9 0%, #E8F4F8 100%);
            font-family: Inter, sans-serif;
        }

        .glass-card {
            border: 1px solid rgba(229, 231, 235, 0.75);
            background: linear-gradient(145deg, #ffffff 0%, #f8faf9 100%);
            box-shadow: 0 12px 36px rgba(26, 60, 52, 0.08);
            transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.35s ease, border-color 0.35s ease;
        }

        .glass-card:hover {
            border-color: rgba(0, 90, 36, 0.2);
            box-shadow: 0 20px 48px rgba(26, 60, 52, 0.14);
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-out both;
        }

        .animate-slide-up {
            animation: slideUp 0.6s ease-out both;
        }

        .animate-slide-in {
            animation: slideIn 0.5s ease-out both;
        }

        .bg-decoration {
            position: fixed;
            z-index: -1;
            pointer-events: none;
            border-radius: 50%;
        }

        .bg-circle-1 {
            top: 10%;
            right: 5%;
            width: 180px;
            height: 180px;
            background: radial-gradient(circle, rgba(0, 90, 36, 0.12) 0%, transparent 70%);
            animation: float 8s ease-in-out infinite;
        }

        .bg-circle-2 {
            bottom: 12%;
            left: 4%;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(255, 165, 0, 0.1) 0%, transparent 70%);
            animation: sway 6s ease-in-out infinite;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-24px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-14px); }
        }

        @keyframes sway {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(10px); }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                scroll-behavior: auto !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body class="text-gray-800">
    <div class="bg-decoration bg-circle-1" aria-hidden="true"></div>
    <div class="bg-decoration bg-circle-2" aria-hidden="true"></div>
    <header class="sticky top-0 z-10 border-b border-green-900/10 bg-white/90 backdrop-blur-xl animate-slide-in">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6">
            <a href="../index.php" class="inline-flex items-center gap-3 text-dark no-underline">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-primary text-white shadow-md">
                    <i class="fa-solid fa-user-tag" aria-hidden="true"></i>
                </span>
                <span>
                    <span class="block font-heading text-lg font-bold">Tipos de Usuário</span>
                    <span class="block text-xs text-gray-500">Gerenciamento STGM</span>
                </span>
            </a>
            <div class="flex items-center gap-2">
                <button type="button" class="theme-toggle" data-theme-toggle aria-label="Ativar modo escuro" aria-pressed="false">
                    <i class="fa-solid fa-sun" data-theme-icon="sun" aria-hidden="true"></i>
                    <i class="fa-solid fa-moon" data-theme-icon="moon" aria-hidden="true"></i>
                </button>
                <a href="../index.php" class="back-button">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                    <span>Voltar</span>
                </a>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 sm:py-12">
        <div class="mb-8 animate-slide-up">
            <p class="mb-2 text-sm font-semibold uppercase tracking-wider text-primary">Acessos e perfis</p>
            <h1 class="font-heading text-3xl font-bold text-dark sm:text-4xl">Tipos de Usuário</h1>
            <p class="mt-3 max-w-2xl text-gray-600">Cadastre os tipos de acesso e vincule cada tipo ao sistema onde será utilizado.</p>
        </div>

        <?php if ($mensagem !== null): ?>
            <div class="mb-6 rounded-xl border px-5 py-4 font-medium animate-fade-in <?php echo $mensagem[1] ? 'border-green-200 bg-green-50 text-green-800' : 'border-red-200 bg-red-50 text-red-800'; ?>" role="<?php echo $mensagem[1] ? 'status' : 'alert'; ?>">
                <?php echo htmlspecialchars($mensagem[0], ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>
        <?php if ($falhaCarregamento): ?>
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4 font-medium text-red-800 animate-fade-in" role="alert">
                Não foi possível carregar os sistemas e tipos de usuário. Tente novamente ou verifique a conexão com o banco de dados.
            </div>
        <?php endif; ?>

        <div class="grid gap-8 lg:grid-cols-[minmax(0,0.9fr)_minmax(0,1.1fr)]">
            <section class="glass-card h-fit rounded-3xl p-6 sm:p-8 animate-slide-up" style="animation-delay: 0.1s;">
                <div class="mb-6 flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-primary/10 text-primary transition-transform duration-300 hover:scale-110">
                        <i class="fa-solid fa-circle-plus text-xl" aria-hidden="true"></i>
                    </div>
                    <div>
                        <h2 class="font-heading text-xl font-bold text-dark">Cadastrar tipo</h2>
                        <p class="mt-1 text-sm text-gray-500">O tipo ficará associado ao sistema escolhido.</p>
                    </div>
                </div>

                <?php if ($falhaCarregamento): ?>
                    <p class="text-sm text-gray-600">O formulário está indisponível enquanto os dados não puderem ser carregados.</p>
                <?php elseif (empty($sistemas)): ?>
                    <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900" role="alert">
                        Nenhum sistema está cadastrado. Cadastre um sistema antes de criar tipos de usuário.
                    </div>
                <?php else: ?>
                    <form action="../controllers/controller_tipos_usuario.php" method="POST" class="space-y-5">
                        <div>
                            <label for="tipoNome" class="mb-2 block text-sm font-semibold text-dark">Nome do tipo *</label>
                            <input id="tipoNome" name="nome" type="text" maxlength="100" required
                                placeholder="Ex.: Admin_estoque"
                                class="w-full rounded-xl border-2 border-gray-200 bg-white px-4 py-3 text-gray-800 outline-none transition focus:border-primary focus:ring-4 focus:ring-primary/10">
                        </div>
                        <div>
                            <label for="tipoSistema" class="mb-2 block text-sm font-semibold text-dark">Sistema vinculado *</label>
                            <select id="tipoSistema" name="sistema_id" required
                                class="w-full rounded-xl border-2 border-gray-200 bg-white px-4 py-3 text-gray-800 outline-none transition focus:border-primary focus:ring-4 focus:ring-primary/10">
                                <option value="">Selecione um sistema</option>
                                <?php foreach ($sistemas as $sistema): ?>
                                    <option value="<?php echo (int) $sistema['id']; ?>"><?php echo htmlspecialchars((string) $sistema['nome'], ENT_QUOTES, 'UTF-8'); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-primary px-5 py-3 font-semibold text-white shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                            <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                            Salvar tipo
                        </button>
                    </form>
                <?php endif; ?>
            </section>

            <section class="glass-card rounded-3xl p-6 sm:p-8 animate-slide-up" style="animation-delay: 0.2s;">
                <div class="mb-6 flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-secondary/15 text-amber-700">
                        <i class="fa-solid fa-list-check text-xl" aria-hidden="true"></i>
                    </div>
                    <div>
                        <h2 class="font-heading text-xl font-bold text-dark">Tipos cadastrados</h2>
                        <p class="mt-1 text-sm text-gray-500">Cada tipo aparece junto ao sistema ao qual pertence.</p>
                    </div>
                </div>

                <?php if ($falhaCarregamento): ?>
                    <p class="py-8 text-center text-sm text-gray-600">A listagem está indisponível no momento.</p>
                <?php elseif (empty($tipos)): ?>
                    <div class="rounded-2xl border border-dashed border-gray-300 bg-white/70 px-5 py-10 text-center">
                        <i class="fa-solid fa-user-tag mb-3 text-3xl text-gray-300" aria-hidden="true"></i>
                        <p class="font-semibold text-gray-700">Ainda não há tipos cadastrados.</p>
                        <p class="mt-1 text-sm text-gray-500">Use o formulário para criar o primeiro tipo.</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-hidden rounded-2xl border border-gray-200">
                        <div class="grid grid-cols-[minmax(0,1fr)_minmax(0,1fr)] gap-4 bg-gray-50 px-4 py-3 text-xs font-bold uppercase tracking-wide text-gray-500 sm:px-5">
                            <span>Tipo de usuário</span>
                            <span>Sistema</span>
                        </div>
                        <ul class="divide-y divide-gray-100">
                            <?php foreach ($tipos as $index => $tipo): ?>
                                <li class="grid grid-cols-[minmax(0,1fr)_minmax(0,1fr)] items-center gap-4 bg-white px-4 py-4 transition-colors duration-200 hover:bg-green-50/70 animate-fade-in sm:px-5" style="animation-delay: <?php echo min($index * 0.05, 0.5); ?>s;">
                                    <span class="break-words font-semibold text-dark"><?php echo htmlspecialchars((string) $tipo['tipo'], ENT_QUOTES, 'UTF-8'); ?></span>
                                    <span class="break-words text-sm text-gray-600"><?php echo htmlspecialchars((string) ($tipo['nome_sistema'] ?: 'Sistema não definido'), ENT_QUOTES, 'UTF-8'); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </main>
</body>
</html>
