<?php
require_once(__DIR__ . '/../models/sessions.php');
$session = new sessions();
$session->autenticar_session();
$dados = $session->tempo_session();

require_once(__DIR__ . "/../models/model.select.php");
$select = new select();
$usuarios = $select->listar_usuarios();
$setores = $select->listar_setores();

$userName = isset($_SESSION['nome']) ? $_SESSION['nome'] : 'Usuário';
$userSetor = isset($_SESSION['setor']) ? $_SESSION['setor'] : 'Sistema de Gestão';
$userEmail = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$userInitial = function_exists('mb_substr') ? mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8') : strtoupper(substr($userName, 0, 1));
$fotoPerfil = isset($_SESSION['foto_perfil']) ? $_SESSION['foto_perfil'] : '';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="content-language" content="pt-BR">
    <title>Gerenciar Usuários - STGM</title>
    <link rel="icon" type="image/png" href="https://i.postimg.cc/0N0dsxrM/Bras-o-do-Cear-svg-removebg-preview.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#005A24',
                        secondary: '#FFA500',
                        accent: '#E6F4EA',
                        dark: '#1A3C34',
                        light: '#F8FAF9',
                        white: '#FFFFFF',
                        gray: {
                            50: '#F9FAFB',
                            100: '#F3F4F6',
                            200: '#E5E7EB',
                            300: '#D1D5DB',
                            400: '#9CA3AF',
                            500: '#6B7280',
                            600: '#4B5563',
                            700: '#374151',
                            800: '#1F2937',
                            900: '#111827'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Poppins', 'sans-serif']
                    },
                    backgroundImage: {
                        'gradient-primary': 'linear-gradient(135deg, #005A24 0%, #1A3C34 100%)',
                        'gradient-secondary': 'linear-gradient(135deg, #F4A261 0%, #E76F51 100%)',
                        'gradient-light': 'linear-gradient(135deg, #E8F4F8 0%, #F7F3E9 100%)',
                        'gradient-dark': 'linear-gradient(135deg, #2D5016 0%, #005A24 100%)',
                        'gradient-hero': 'linear-gradient(135deg, #005A24 0%, #2D5016 25%, #7FB069 50%, #005A24 75%, #1A3C34 100%)',
                        'gradient-card': 'linear-gradient(145deg, #ffffff 0%, #f8faf9 100%)',
                        'gradient-glass': 'linear-gradient(145deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.7) 100%)'
                    },
                    boxShadow: {
                        'strong': '0 10px 40px -10px rgba(0, 0, 0, 0.15), 0 2px 10px -2px rgba(0, 0, 0, 0.05)',
                        'primary': '0 10px 25px -5px rgba(0, 90, 36, 0.3)',
                        'secondary': '0 10px 25px -5px rgba(255, 165, 0, 0.3)',
                        'glass': '0 8px 32px 0 rgba(31, 38, 135, 0.37)',
                        'glow': '0 0 20px rgba(0, 90, 36, 0.3)',
                        'card-hover': '0 20px 60px -10px rgba(0, 0, 0, 0.15), 0 8px 25px -5px rgba(0, 0, 0, 0.1)'
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.6s ease-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'slide-in': 'slideIn 0.5s ease-out',
                        'bounce-subtle': 'bounceSubtle 0.8s ease-in-out',
                        'float': 'float 6s ease-in-out infinite',
                        'sway': 'sway 4s ease-in-out infinite',
                        'pulse-glow': 'pulseGlow 2s ease-in-out infinite',
                        'scale-in': 'scaleIn 0.4s ease-out',
                        'shimmer': 'shimmer 2s linear infinite'
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(10px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            }
                        },
                        slideUp: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(30px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            }
                        },
                        slideIn: {
                            '0%': {
                                transform: 'translateX(-100%)',
                                opacity: '0'
                            },
                            '100%': {
                                transform: 'translateX(0)',
                                opacity: '1'
                            }
                        },
                        bounceSubtle: {
                            '0%, 100%': {
                                transform: 'translateY(0)'
                            },
                            '50%': {
                                transform: 'translateY(-8px)'
                            }
                        },
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0px) rotate(0deg)'
                            },
                            '50%': {
                                transform: 'translateY(-20px) rotate(3deg)'
                            }
                        },
                        sway: {
                            '0%, 100%': {
                                transform: 'translateX(0px) rotate(0deg)'
                            },
                            '50%': {
                                transform: 'translateX(10px) rotate(1deg)'
                            }
                        },
                        pulseGlow: {
                            '0%, 100%': {
                                boxShadow: '0 0 20px rgba(0, 90, 36, 0.3)'
                            },
                            '50%': {
                                boxShadow: '0 0 30px rgba(0, 90, 36, 0.5)'
                            }
                        },
                        scaleIn: {
                            '0%': {
                                transform: 'scale(0.9)',
                                opacity: '0'
                            },
                            '100%': {
                                transform: 'scale(1)',
                                opacity: '1'
                            }
                        },
                        shimmer: {
                            '0%': {
                                transform: 'translateX(-100%)'
                            },
                            '100%': {
                                transform: 'translateX(100%)'
                            }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #F8FAF9 0%, #E8F4F8 100%);
            min-height: 100vh;
        }

        /* Enhanced card styles */
        .card-enhanced {
            background: linear-gradient(145deg, #ffffff 0%, #f8faf9 100%);
            border: 1px solid rgba(229, 231, 235, 0.8);
            backdrop-filter: blur(10px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            width: 100%;
            height: fit-content;
            min-height: 280px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .card-enhanced::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 90, 36, 0.05), transparent);
            transition: left 0.6s ease;
        }

        .card-enhanced:hover::before {
            left: 100%;
        }

        .card-enhanced:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 60px -15px rgba(0, 90, 36, 0.15), 0 15px 35px -10px rgba(0, 0, 0, 0.1);
            border-color: rgba(0, 90, 36, 0.3);
        }

        /* Responsive grid improvements - FIXED */
        @media (min-width: 1024px) {
            .card-enhanced {
                min-width: 100%;
                max-width: 100%;
                width: 100%;
            }
        }

        @media (min-width: 1280px) {
            .card-enhanced {
                min-width: 100%;
                max-width: 100%;
                width: 100%;
            }
        }

        @media (min-width: 1536px) {
            .card-enhanced {
                min-width: 100%;
                max-width: 100%;
                width: 100%;
            }
        }

        /* Icon container with gradient background */
        .icon-container {
            background: linear-gradient(135deg, var(--bg-from), var(--bg-to));
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .icon-container::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.3) 50%, transparent 70%);
            transform: translateX(-100%);
            transition: transform 0.6s;
        }

        .card-enhanced:hover .icon-container::after {
            transform: translateX(100%);
        }

        /* Header with glass effect */
        .header-glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(229, 231, 235, 0.8);
        }

        /* Loading animation improvements */
        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f4f6;
            border-top: 4px solid #005A24;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Floating background elements */
        .bg-decoration {
            position: fixed;
            pointer-events: none;
            z-index: -1;
        }

        .bg-circle-1 {
            top: 10%;
            right: 10%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(0, 90, 36, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }

        .bg-circle-2 {
            bottom: 20%;
            left: 5%;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(255, 165, 0, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            animation: sway 6s ease-in-out infinite reverse;
        }

        /* Button enhancements */
        .btn-logout {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-logout::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transform: translateX(-100%);
            transition: transform 0.6s;
        }

        .btn-logout:hover::before {
            transform: translateX(100%);
        }

        .btn-logout:hover {
            background: #f3f4f6;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Staggered animation for cards */
        .card-1 {
            animation-delay: 0.1s;
        }

        .card-2 {
            animation-delay: 0.2s;
        }

        .card-3 {
            animation-delay: 0.3s;
        }

        /* Enhanced input styles */
        .input-enhanced {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
            border: 2px solid #E5E7EB;
        }

        .input-enhanced:focus {
            transform: none;
            outline: none;
            border-color: #FFA500;
            box-shadow: 0 0 0 3px rgba(255, 165, 0, 0.1);
            background: white;
        }

        /* Table enhancements */
        .table-enhanced {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(229, 231, 235, 0.8);
        }

        .table-row {
            transition: all 0.2s ease;
        }

        .table-row:hover {
            background: rgba(249, 250, 251, 0.8);
            transform: translateY(-1px);
        }

        /* Status badge enhancements */
        .status-badge {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            font-weight: 600;
            letter-spacing: 0.025em;
        }

        .status-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s;
        }

        .status-badge:hover::before {
            left: 100%;
        }

        /* Botão de ação melhorado */
        .action-btn {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .action-btn:hover::before {
            left: 100%;
        }

        /* Avatar com indicador online */
        .avatar-online {
            position: relative;
            transition: all 0.3s ease;
        }

        .avatar-online:hover {
            transform: scale(1.05);
        }

        .online-indicator {
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 16px;
            height: 16px;
            background: #10B981;
            border: 2px solid white;
            border-radius: 50%;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
        }
    </style>
</head>

<body class="text-gray-800 font-sans min-h-screen">
    <!-- Background decorations -->
    <div class="bg-decoration bg-circle-1"></div>
    <div class="bg-decoration bg-circle-2"></div>

    <div class="min-h-screen">
        <!-- Header -->
        <header class="header-glass sticky top-0 z-40 px-3 sm:px-6 py-3 sm:py-4 animate-slide-in">
            <div class="flex items-center justify-between max-w-7xl mx-auto">
                <div class="flex items-center gap-2 sm:gap-4">
                    <a href="../index.php" class="p-2 sm:p-3 rounded-xl hover:bg-gray-100 text-gray-600 transition-all group">
                        <i class="fa-solid fa-arrow-left text-base sm:text-lg group-hover:scale-110 transition-transform"></i>
                    </a>
                    <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl flex items-center justify-center">
                        <img class="w-6 h-6 sm:w-10 sm:h-10 object-contain" src="https://i.postimg.cc/Dy40VtFL/Design-sem-nome-13-removebg-preview.png" alt="Logo CREDE">
                    </div>
                    <div>
                        <h1 class="font-bold text-base sm:text-xl text-dark font-heading">STGM</h1>
                        <p class="text-xs text-gray-500 font-medium hidden sm:block">Gerenciamento de Usuários</p>
                    </div>
                </div>

                
            </div>
        </header>

        <!-- Main Content -->
        <main class="px-2 sm:px-4 py-4 sm:py-8">
            <div class="max-w-full mx-auto">
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row flex-wrap justify-center gap-3 sm:gap-6 mb-8 sm:mb-12 animate-slide-up">
                    <button onclick="openUserForm()" class="bg-gradient-to-r from-primary to-dark hover:from-primary/90 hover:to-dark/90 text-white px-6 py-3 rounded-xl font-semibold flex items-center justify-center sm:justify-start gap-2 sm:gap-3 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        <div class="w-6 h-6 rounded-lg bg-white/20 flex items-center justify-center">
                            <i class="fa-solid fa-user-plus text-white text-sm"></i>
                        </div>
                        <span class="text-sm sm:text-base">Cadastrar Usuário</span>
                    </button>
                    <?php if(isset($_SESSION['Dev_usuario'])){?>
                    <button onclick="openUserTypeForm()" class="bg-gradient-to-r from-secondary to-orange-500 hover:from-secondary/90 hover:to-orange-500/90 text-white px-6 py-3 rounded-xl font-semibold flex items-center justify-center sm:justify-start gap-2 sm:gap-3 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        <div class="w-6 h-6 rounded-lg bg-white/20 flex items-center justify-center">
                            <i class="fa-solid fa-user-tag text-white text-sm"></i>
                        </div>
                        <span class="text-sm sm:text-base">Cadastrar Tipo de Usuário</span>
                    </button>
                    <?php }?>
                </div>

                <!-- Users Table -->
                <div class="table-enhanced rounded-3xl overflow-hidden animate-fade-in">
                    <!-- Table Header -->
                    <div class="p-4 sm:p-6 lg:p-8 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 sm:gap-6 border-b border-gray-200/50">
                        <div class="flex flex-col sm:flex-row flex-wrap items-start sm:items-center gap-3 sm:gap-4 w-full lg:w-auto">
                            <div class="relative w-full sm:w-auto">
                                <i class="fa-solid fa-magnifying-glass absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm sm:text-base"></i>
                                <input id="tableSearch" type="text" class="input-enhanced pl-10 sm:pl-12 pr-3 sm:pr-4 py-3 sm:py-4 rounded-xl sm:rounded-2xl font-medium w-full sm:w-64 lg:w-80 text-sm sm:text-base" placeholder="Buscar por nome, e-mail...">
                            </div>
                        </div>

                        <div class="flex items-center justify-center lg:justify-end gap-3 sm:gap-4 w-full lg:w-auto">
                            <div class="text-xs sm:text-sm text-gray-600 bg-accent/30 px-3 sm:px-4 py-2 rounded-xl">
                                <span id="resultCount" class="font-semibold">0 resultados</span>
                            </div>
                        </div>
                    </div>

                    <!-- Cards Container -->
                    <div class="p-4 sm:p-6">
                        <div id="usersCards" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-5 sm:gap-6 w-full">
                            <?php foreach ($usuarios as $user): ?>
                                <div class="card-enhanced p-4 sm:p-5 lg:p-6 rounded-2xl sm:rounded-3xl animate-fade-in user-card group hover:shadow-2xl transition-all duration-500" data-nome="<?php echo strtolower(htmlspecialchars($user['nome'])); ?>" data-email="<?php echo strtolower(htmlspecialchars($user['email'])); ?>" data-setor="<?php echo strtolower(htmlspecialchars($user['id_setor'])); ?>">
                                    
                                    <!-- Header do Card com Avatar e Nome -->
                                    <div class="flex items-start gap-3 mb-4">
                                        <?php if (!empty($user['foto_perfil']) && $user['foto_perfil'] !== 'default.png') { ?>
                                            <div class="relative">
                                                <img src="<?php echo '../../../main/assets/fotos_perfil/' . htmlspecialchars($user['foto_perfil'], ENT_QUOTES, 'UTF-8'); ?>" alt="Foto de perfil de <?php echo htmlspecialchars($user['nome']); ?>" class="w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 rounded-2xl object-cover border-2 border-white shadow-lg flex-shrink-0">
                                                <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></div>
                                            </div>
                                        <?php } else { ?>
                                            <div class="relative">
                                                <div class="w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 rounded-2xl bg-gradient-to-br from-primary via-primary to-dark text-white flex items-center justify-center font-bold text-base sm:text-lg lg:text-xl flex-shrink-0 shadow-lg">
                                                    <?php echo htmlspecialchars($user['nome'][0]); ?>
                                                </div>
                                                <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></div>
                                            </div>
                                        <?php } ?>
                                        
                                        <div class="min-w-0 flex-1 pt-1">
                                            <h3 class="font-bold text-dark text-base sm:text-lg lg:text-xl truncate mb-1 group-hover:text-primary transition-colors duration-300">
                                                <?php echo htmlspecialchars($user['nome']); ?>
                                            </h3>
                                            <p class="text-gray-500 text-sm sm:text-base truncate flex items-center gap-2">
                                                <i class="fa-solid fa-envelope text-xs text-gray-400"></i>
                                                <?php echo htmlspecialchars($user['email']); ?>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Informações do Usuário -->
                                    <div class="space-y-3 mb-4">
                                        <!-- CPF -->
                                        <div class="bg-gradient-to-r from-gray-50 to-gray-100/50 p-2.5 rounded-xl border border-gray-200/50">
                                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">
                                                <i class="fa-solid fa-id-card text-primary mr-2"></i>CPF
                                            </label>
                                            <span class="text-sm font-mono text-gray-800 bg-white px-2.5 py-1.5 rounded-lg border border-gray-200/50 block">
                                                <?php echo htmlspecialchars($user['cpf']); ?>
                                            </span>
                                        </div>
                                        
                                        <!-- Setor -->
                                        <div class="bg-gradient-to-r from-accent/20 to-accent/10 p-2.5 rounded-xl border border-accent/30">
                                            <label class="block text-xs font-semibold text-primary mb-1.5 uppercase tracking-wide">
                                                <i class="fa-solid fa-building text-primary mr-2"></i>Setor
                                            </label>
                                            <span class="status-badge px-3 py-1.5 text-sm font-semibold rounded-xl bg-white text-primary border-2 border-accent/50 shadow-sm">
                                                <?php echo htmlspecialchars($user['nome_setor']); ?>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Barra de Ações -->
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-200/50">
                                        <div class="text-xs text-gray-400 font-medium">
                                            <i class="fa-solid fa-clock mr-1"></i>
                                            ID: <?php echo $user['id']; ?>
                                        </div>
                                        
                                        <div class="flex items-center gap-2">
                                            <button class="action-btn p-2.5 rounded-xl border-2 border-gray-200 hover:bg-primary hover:text-white hover:border-primary text-gray-600 transition-all duration-300 transform hover:scale-110 hover:shadow-lg" onclick="openEditUser(<?php echo $user['id']; ?>)" title="Editar usuário">
                                                <i class='fa-solid fa-pen text-sm'></i>
                                            </button>
                                            <button class="action-btn p-2.5 rounded-xl border-2 border-red-200 hover:bg-red-500 hover:text-white hover:border-red-500 text-red-500 transition-all duration-300 transform hover:scale-110 hover:shadow-lg" onclick="openDeleteUser(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['nome'], ENT_QUOTES, 'UTF-8'); ?>')" title="Remover usuário">
                                                <i class='fa-solid fa-trash text-sm'></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Cadastrar/Editar Usuário -->
    <div id="modalUser" class="fixed inset-0 bg-black/60 backdrop-blur-md hidden items-center justify-center p-2 sm:p-4 z-40">
        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto transform transition-all duration-300 scale-95 opacity-0" id="modalUserContent">
            <div class="p-6 sm:p-8 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary to-dark text-white flex items-center justify-center">
                        <i class="fa-solid fa-user text-xl"></i>
                    </div>
                    <div>
                        <h3 id="modalTitle" class="text-xl sm:text-2xl font-bold text-dark font-heading">Cadastrar Usuário</h3>
                        <p class="text-gray-600 text-sm">Preencha as informações do usuário</p>
                    </div>
                </div>
                <button class="absolute top-6 right-6 p-2 rounded-xl hover:bg-gray-100 transition-all group" onclick="closeModal('modalUser')">
                    <i class="fa-solid fa-xmark text-gray-400 text-lg group-hover:text-gray-600 group-hover:scale-110 transition-all"></i>
                </button>
            </div>
            <div class="p-6 sm:p-8">
                <form id="userForm" action="../controllers/controller_usuario.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="inpUserId" name="user_id" value="">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-dark mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-user text-primary"></i>
                                Nome Completo *
                            </label>
                            <input id="inpNome" name="nome" type="text" class="input-enhanced w-full px-4 py-4 rounded-xl transition-all text-base border-2 focus:border-primary focus:ring-4 focus:ring-primary/10" placeholder="Digite o nome completo" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-dark mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-envelope text-primary"></i>
                                E-mail *
                            </label>
                            <input id="inpEmail" name="email" type="email" class="input-enhanced w-full px-4 py-4 rounded-xl transition-all text-base border-2 focus:border-primary focus:ring-4 focus:ring-primary/10" placeholder="Digite o e-mail" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-dark mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-id-card text-primary"></i>
                                CPF *
                            </label>
                            <input id="inpCpf" name="cpf" type="text" class="input-enhanced w-full px-4 py-4 rounded-xl transition-all text-base border-2 focus:border-primary focus:ring-4 focus:ring-primary/10" placeholder="000.000.000-00" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-dark mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-building text-primary"></i>
                                Setor *
                            </label>
                            <select id="inpSetor" name="setor" class="input-enhanced w-full px-4 py-4 rounded-xl transition-all text-base border-2 focus:border-primary focus:ring-4 focus:ring-primary/10" required>
                                <option value="">Selecione um setor</option>
                                <?php foreach ($setores as $setor): ?>
                                    <option value="<?php echo htmlspecialchars($setor['id']); ?>"><?php echo htmlspecialchars($setor['nome']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>
                    <div class="p-6 sm:p-8 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3">
                        <button type="button" class="px-6 py-3 rounded-xl border-2 border-gray-300 font-semibold text-gray-700 hover:bg-gray-100 hover:border-gray-400 transition-all text-base" onclick="closeModal('modalUser')">
                            <i class="fa-solid fa-times mr-2"></i>Cancelar
                        </button>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-primary to-dark text-white font-semibold rounded-xl hover:from-primary/90 hover:to-dark/90 transition-all text-base shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fa-solid fa-save mr-2"></i>Salvar Usuário
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Cadastrar Tipo de Usuário -->
    <div id="modalTipoUsuario" class="fixed inset-0 bg-black/60 backdrop-blur-md hidden items-center justify-center p-2 sm:p-4 z-40">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto transform transition-all duration-300 scale-95 opacity-0" id="modalTipoUsuarioContent">
            <div class="p-6 sm:p-8 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-secondary to-orange-500 text-white flex items-center justify-center">
                        <i class="fa-solid fa-user-tag text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl sm:text-2xl font-bold text-dark font-heading">Cadastrar Tipo de Usuário</h3>
                        <p class="text-gray-600 text-sm">Defina um novo perfil de acesso</p>
                    </div>
                </div>
                <button class="absolute top-6 right-6 p-2 rounded-xl hover:bg-gray-100 transition-all group" onclick="closeModal('modalTipoUsuario')">
                    <i class="fa-solid fa-xmark text-gray-400 text-lg group-hover:text-gray-600 group-hover:scale-110 transition-all"></i>
                </button>
            </div>
            <div class="p-6 sm:p-8">
                <form id="userTypeForm" action="../controllers/controller_usuario.php" method="POST">
                    <div>
                        <label class="block text-sm font-semibold text-dark mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-tag text-secondary"></i>
                            Nome do Tipo *
                        </label>
                        <input id="inpNomeTipo" name="nome" type="text" class="input-enhanced w-full px-4 py-4 rounded-xl transition-all text-base border-2 focus:border-secondary focus:ring-4 focus:ring-secondary/10" placeholder="Digite o nome do tipo de usuário" required>
                    </div>
                    <div class="p-6 sm:p-8 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3">
                        <button type="button" class="px-6 py-3 rounded-xl border-2 border-gray-300 font-semibold text-gray-700 hover:bg-gray-100 hover:border-gray-400 transition-all text-base" onclick="closeModal('modalTipoUsuario')">
                            <i class="fa-solid fa-times mr-2"></i>Cancelar
                        </button>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-secondary to-orange-500 text-white font-semibold rounded-xl hover:from-secondary/90 hover:to-orange-500/90 transition-all text-base shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fa-solid fa-save mr-2"></i>Salvar Tipo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div id="modalDeleteUser" class="fixed inset-0 bg-black/60 backdrop-blur-md hidden items-center justify-center p-2 sm:p-4 z-50">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl transform transition-all duration-300 scale-95 opacity-0" id="modalDeleteUserContent">
            <div class="p-6 sm:p-8 text-center">
                <div class="w-20 h-20 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-exclamation-triangle text-3xl text-red-500"></i>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-dark font-heading mb-4">Confirmar Exclusão</h3>
                <p class="text-gray-600 text-base mb-6 leading-relaxed">
                    Tem certeza que deseja excluir o usuário <span class="font-semibold text-dark" id="deleteUserName"></span>?
                </p>
                <p class="text-sm text-red-600 bg-red-50 px-4 py-3 rounded-lg border border-red-200 mb-6">
                    <i class="fa-solid fa-info-circle mr-2"></i>
                    Esta ação não pode ser desfeita.
                </p>
                <form id="deleteForm" action="../controllers/controller_usuario.php" method="POST">
                    <input type="hidden" id="deleteUserId" name="user_id" value="">
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <button type="button" class="px-6 py-3 rounded-xl border-2 border-gray-300 font-semibold text-gray-700 hover:bg-gray-100 hover:border-gray-400 transition-all text-base" onclick="closeModal('modalDeleteUser')">
                            <i class="fa-solid fa-times mr-2"></i>Cancelar
                        </button>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white font-semibold rounded-xl hover:from-red-600 hover:to-red-700 transition-all text-base shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fa-solid fa-trash mr-2"></i>Excluir Usuário
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const usuarios = <?php echo json_encode($usuarios); ?>;



        function openUserForm() {
            document.getElementById('modalTitle').textContent = 'Cadastrar Usuário';
            document.getElementById('inpUserId').value = '';
            document.getElementById('inpNome').value = '';
            document.getElementById('inpEmail').value = '';
            document.getElementById('inpCpf').value = '';
            document.getElementById('inpSetor').value = '';
            document.getElementById('userForm').action = '../controllers/controller_usuario.php';
            openModal('modalUser');
        }

        function openUserTypeForm() {
            document.getElementById('inpNomeTipo').value = '';
            openModal('modalTipoUsuario');
        }

        function openEditUser(userId) {
            const user = usuarios.find(u => u.id === userId);
            if (user) {
                document.getElementById('modalTitle').textContent = 'Editar Usuário';
                document.getElementById('inpUserId').value = user.id || '';
                document.getElementById('inpNome').value = user.nome || '';
                document.getElementById('inpEmail').value = user.email || '';
                
                // Aplicar máscara ao CPF se existir
                if (user.cpf) {
                    const cpf = user.cpf.toString();
                    if (cpf.length === 11) {
                        // Formatar CPF com máscara
                        const cpfFormatado = cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
                        document.getElementById('inpCpf').value = cpfFormatado;
                    } else {
                        document.getElementById('inpCpf').value = user.cpf || '';
                    }
                } else {
                    document.getElementById('inpCpf').value = '';
                }
                
                document.getElementById('inpSetor').value = user.id_setor || ''; // Use id_setor instead of setor_id
                document.getElementById('userForm').action = '../controllers/controller_usuario.php';
                openModal('modalUser');
            }
        }

        function openDeleteUser(userId, userName) {
            document.getElementById('deleteUserName').textContent = userName;
            document.getElementById('deleteUserId').value = userId;
            openModal('modalDeleteUser');
        }

        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => {
                const content = modal.querySelector('[id$="Content"]');
                if (content) {
                    content.style.transform = 'scale(1)';
                    content.style.opacity = '1';
                }
            }, 10);
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            const content = modal.querySelector('[id$="Content"]');

            if (content) {
                content.style.transform = 'scale(0.95)';
                content.style.opacity = '0';
            }

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }

        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-xl shadow-lg transform transition-all duration-300 translate-x-full ${
                type === 'success' ? 'bg-green-500 text-white' : 
                type === 'error' ? 'bg-red-500 text-white' : 
                'bg-blue-500 text-white'
            }`;
            notification.innerHTML = `
                <div class="flex items-center gap-3">
                    <i class="fa-solid ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
                    <span class="font-medium">${message}</span>
                </div>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 10);

            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        function applyFilters() {
            const q = document.getElementById('tableSearch').value.toLowerCase();
            const cards = document.querySelectorAll('.user-card');
            let count = 0;

            cards.forEach(card => {
                const nome = card.dataset.nome;
                const email = card.dataset.email;
                const setor = card.dataset.setor;

                const matchesQuery = (nome.includes(q) || email.includes(q) || setor.includes(q));

                if (matchesQuery) {
                    card.style.display = '';
                    count++;
                } else {
                    card.style.display = 'none';
                }
            });

            document.getElementById('resultCount').textContent = `${count} resultado${count !== 1 ? 's' : ''}`;
        }

        // Função para aplicar máscara de CPF
        function aplicarMascaraCPF(input) {
            let value = input.value.replace(/\D/g, ''); // Remove tudo que não é dígito
            value = value.replace(/(\d{3})(\d)/, '$1.$2'); // Coloca ponto depois do 3º dígito
            value = value.replace(/(\d{3})(\d)/, '$1.$2'); // Coloca ponto depois do 6º dígito
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2'); // Coloca hífen antes dos últimos 2 dígitos
            input.value = value;
        }

        // Função para remover máscara do CPF (apenas números) - usada apenas para validação
        function removerMascaraCPF(cpf) {
            return cpf.replace(/\D/g, '');
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            applyFilters(); // Inicial
            document.getElementById('tableSearch').addEventListener('input', applyFilters);

            // Aplicar máscara de CPF
            const cpfInput = document.getElementById('inpCpf');
            if (cpfInput) {
                cpfInput.addEventListener('input', function() {
                    aplicarMascaraCPF(this);
                });
                
                // Limitar o campo a 14 caracteres (formato: 000.000.000-00)
                cpfInput.addEventListener('keypress', function(e) {
                    if (this.value.length >= 14 && e.key !== 'Backspace' && e.key !== 'Delete') {
                        e.preventDefault();
                    }
                });
            }

            // Form submissions
            document.getElementById('userForm').addEventListener('submit', function(e) {
                // Validação
                if (!this.inpNome.value.trim() || !this.inpEmail.value.trim() || !this.inpCpf.value.trim() || !this.inpSetor.value) {
                    e.preventDefault();
                    showNotification('Preencha todos os campos obrigatórios.', 'error');
                    return;
                }
                
                // Validar formato do CPF antes de enviar
                const cpf = removerMascaraCPF(this.inpCpf.value);
                if (cpf.length !== 11) {
                    e.preventDefault();
                    showNotification('CPF deve conter 11 dígitos.', 'error');
                    return;
                }
                
                // Manter o CPF com máscara (formato: 000.000.000-00) para enviar ao backend
                // O valor já está formatado no campo, então não precisa alterar
                // this.inpCpf.value permanece com a máscara
            });

            document.getElementById('userTypeForm').addEventListener('submit', function(e) {
                if (!this.inpNomeTipo.value.trim()) {
                    e.preventDefault();
                    showNotification('Digite o nome do tipo de usuário.', 'error');
                }
            });

            // Outros listeners existentes
            const userBtn = document.getElementById('userMenuButton');
            const userMenu = document.getElementById('userMenu');
            if (userBtn && userMenu) {
                userBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userMenu.classList.toggle('hidden');
                });
                document.addEventListener('click', function(e) {
                    if (!userMenu.contains(e.target)) {
                        userMenu.classList.add('hidden');
                    }
                });
            }
            document.documentElement.style.scrollBehavior = 'smooth';

            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.animate-fade-in, .animate-slide-up').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                observer.observe(el);
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const openModals = document.querySelectorAll('[id^="modal"]:not(.hidden)');
                    openModals.forEach(modal => {
                        if (!modal.classList.contains('hidden')) {
                            closeModal(modal.id);
                        }
                    });
                }
            });

            document.querySelectorAll('.table-row').forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-1px)';
                });

                row.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
</body>

</html>