<?php
require_once(__DIR__ . '/../../main/models/sessions.php');
require_once(__DIR__ . '/../../main/models/model.usuario.php');


$session = new sessions();
$session->autenticar_session();
$session->tempo_session();

$modelUsuario = new model_usuario();
$dadosUsuario = [];
try {
    $dadosUsuario = $modelUsuario->getDadosUsuario((int)($_SESSION['id'] ?? 0));
} catch (Throwable $e) {
    $dadosUsuario = [];
}

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
    <title>Gerenciador de Usuários - STGM</title>
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
                    backgroundImage: {
                        'gradient-primary': 'linear-gradient(135deg, #005A24 0%, #7FB069 50%, #1A3C34 100%)',
                        'gradient-secondary': 'linear-gradient(135deg, #F4A261 0%, #E76F51 100%)',
                        'gradient-light': 'linear-gradient(135deg, #E8F4F8 0%, #F7F3E9 100%)',
                        'gradient-dark': 'linear-gradient(135deg, #2D5016 0%, #005A24 100%)',
                        'gradient-hero': 'linear-gradient(135deg, #005A24 0%, #2D5016 25%, #7FB069 50%, #005A24 75%, #1A3C34 100%)',
                        'gradient-card': 'linear-gradient(145deg, #ffffff 0%, #f8faf9 100%)',
                        'gradient-glass': 'linear-gradient(145deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.7) 100%)'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Poppins', 'sans-serif']
                    },
                    boxShadow: {
                        'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
                        'medium': '0 4px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
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
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        slideIn: {
                            '0%': { transform: 'translateX(-100%)', opacity: '0' },
                            '100%': { transform: 'translateX(0)', opacity: '1' }
                        },
                        bounceSubtle: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-8px)' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px) rotate(0deg)' },
                            '50%': { transform: 'translateY(-20px) rotate(3deg)' }
                        },
                        sway: {
                            '0%, 100%': { transform: 'translateX(0px) rotate(0deg)' },
                            '50%': { transform: 'translateX(10px) rotate(1deg)' }
                        },
                        pulseGlow: {
                            '0%, 100%': { boxShadow: '0 0 20px rgba(0, 90, 36, 0.3)' },
                            '50%': { boxShadow: '0 0 30px rgba(0, 90, 36, 0.5)' }
                        },
                        scaleIn: {
                            '0%': { transform: 'scale(0.9)', opacity: '0' },
                            '100%': { transform: 'scale(1)', opacity: '1' }
                        },
                        shimmer: {
                            '0%': { transform: 'translateX(-100%)' },
                            '100%': { transform: 'translateX(100%)' }
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
        }

        .card-enhanced::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.6s;
        }

        .card-enhanced:hover::before {
            left: 100%;
        }
        
        .card-enhanced:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 60px -10px rgba(0, 0, 0, 0.15), 0 8px 25px -5px rgba(0, 0, 0, 0.1);
            border-color: rgba(0, 90, 36, 0.2);
        }

        .card-enhanced:hover h3 {
            color: #005A24 !important;
        }

        .card-enhanced:hover p {
            color: #374151 !important;
        }

        .card-enhanced:hover .text-primary {
            color: #005A24 !important;
        }

        /* Ensure all icons turn white on hover */
        .card-enhanced:hover .icon-container i {
            color: white !important;
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
            background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.3) 50%, transparent 70%);
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
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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
            background: rgba(0, 90, 36, 0.1);
            color: #005A24;
            border: 2px solid #005A24;
            padding: clamp(0.5rem, 2vw, 0.75rem) clamp(0.75rem, 3vw, 1.5rem);
            border-radius: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: clamp(0.75rem, 2.5vw, 0.875rem);
            white-space: nowrap;
        }

        .btn-logout:hover {
            background: #005A24;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 90, 36, 0.25);
        }

        /* Staggered animation for cards */
        .card-1 { animation-delay: 0.1s; }
        .card-2 { animation-delay: 0.2s; }
        .card-3 { animation-delay: 0.3s; }
    </style>
</head>

<body class="text-gray-800 font-sans min-h-screen">
    <!-- Background decorations -->
    <div class="bg-decoration bg-circle-1"></div>
    <div class="bg-decoration bg-circle-2"></div>
    
   

    <div class="min-h-screen">
        <!-- Header -->
        <header class="header-glass sticky top-0 z-40 px-6 py-4 animate-slide-in">
            <div class="flex items-center justify-between max-w-7xl mx-auto">
            <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center">
                        <img class="w-8 h-8 object-contain" src="https://i.postimg.cc/Dy40VtFL/Design-sem-nome-13-removebg-preview.png" alt="Logo STGM">
                </div>
                <div>
                        <h1 class="font-bold text-xl text-dark font-heading">STGM</h1>
                       
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                  
                    <button onclick="logout()" class="btn-logout">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span class="hidden sm:inline">Voltar</span>
                    </button>
                    </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <div class="flex justify-center px-4 py-8">
            <main class="w-full max-w-6xl">
                <!-- Hero Section -->
                <div class="text-center mb-12 animate-fade-in">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-accent/50 rounded-full text-primary font-medium text-sm mb-6">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span>Sistema Seguro e Confiável</span>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold text-dark font-heading mb-4 leading-tight">
                        Sistema de 
                        <span class="bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                            Gerenciamento
                        </span>
                    </h1>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
                        Plataforma completa para gerenciar usuários, setores e permissões do sistema CREDE com segurança e eficiência
                    </p>
                </div>

               
                
                <!-- Menu Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Usuários Card -->
                    <a href="views/usuario.php" class="card-enhanced card-1 rounded-3xl p-8 group animate-fade-in">
                        <div class="icon-container w-20 h-20 rounded-3xl flex items-center justify-center mb-6 group-hover:scale-110 transition-all duration-500" 
                             style="--bg-from: rgba(0, 90, 36, 0.1); --bg-to: rgba(0, 90, 36, 0.05);">
                            <i class="fa-solid fa-user-plus text-3xl text-primary group-hover:text-white transition-colors duration-300"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-dark mb-3 font-heading group-hover:text-primary transition-colors">
                            Usuários
                        </h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Cadastre e gerencie usuários do sistema com controle total de acesso e permissões
                        </p>
                        <div class="flex items-center text-primary font-semibold text-sm group-hover:translate-x-2 transition-transform">
                            <span>Acessar módulo</span>
                            <i class="fa-solid fa-arrow-right ml-2 group-hover:ml-3 transition-all"></i>
                        </div>
                    </a>

                    <!-- Setores Card -->
                    <a href="views/setor.php" class="card-enhanced card-2 rounded-3xl p-8 group animate-fade-in">
                        <div class="icon-container w-20 h-20 rounded-3xl flex items-center justify-center mb-6 group-hover:scale-110 transition-all duration-500"
                             style="--bg-from: rgba(255, 165, 0, 0.1); --bg-to: rgba(255, 165, 0, 0.05);">
                            <i class="fa-solid fa-building text-3xl text-primary group-hover:text-white transition-colors duration-300"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-dark mb-3 font-heading group-hover:text-primary transition-colors">
                            Perfis
                        </h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Organize e gerencie perfis com estrutura hierárquica completa
                        </p>
                        <div class="flex items-center text-primary font-semibold text-sm group-hover:translate-x-2 transition-transform">
                            <span>Acessar módulo</span>
                            <i class="fa-solid fa-arrow-right ml-2 group-hover:ml-3 transition-all"></i>
                        </div>
                    </a>

                    <!-- Permissões Card -->
                    <a href="views/permissoes.php" class="card-enhanced card-3 rounded-3xl p-8 group animate-fade-in md:col-span-2 lg:col-span-1">
                        <div class="icon-container w-20 h-20 rounded-3xl flex items-center justify-center mb-6 group-hover:scale-110 transition-all duration-500"
                             style="--bg-from: rgba(59, 130, 246, 0.1); --bg-to: rgba(59, 130, 246, 0.05);">
                            <i class="fa-solid fa-shield-halved text-3xl text-primary group-hover:text-white transition-colors duration-300"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-dark mb-3 font-heading group-hover:text-primary transition-colors">
                            Permissões
                        </h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Configure permissões detalhadas e controle de acesso para cada usuário do sistema
                        </p>
                        <div class="flex items-center text-primary font-semibold text-sm group-hover:translate-x-2 transition-transform">
                            <span>Acessar módulo</span>
                            <i class="fa-solid fa-arrow-right ml-2 group-hover:ml-3 transition-all"></i>
                        </div>
                    </a>
                </div>

                
            </main>
        </div>
    </div>

    <script>
        // Enhanced loading animation
        window.addEventListener('load', function() {
            setTimeout(() => {
                const overlay = document.getElementById('loadingOverlay');
                overlay.style.opacity = '0';
                overlay.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    overlay.style.display = 'none';
                }, 300);
            }, 1500);
        });



        // Update user menu toggle and animations
        document.addEventListener('DOMContentLoaded', function() {
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
            
            // Add smooth scroll behavior
            document.documentElement.style.scrollBehavior = 'smooth';
            
            // Add intersection observer for animations
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
            
            // Observe all animated elements
            document.querySelectorAll('.animate-fade-in, .animate-slide-up').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                observer.observe(el);
            });
        });

        // Add keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                // Close any modals or return to main view
                console.log('Escape pressed - could close modals');
            }
        });

        // Add hover effects for cards
        document.querySelectorAll('.card-enhanced').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.02)';
                
                // Ensure text colors are maintained on hover for all cards
                const title = this.querySelector('h3');
                const description = this.querySelector('p');
                const linkText = this.querySelector('.text-primary');
                
                if (title) title.style.color = '#005A24';
                if (description) description.style.color = '#374151';
                if (linkText) linkText.style.color = '#005A24';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
                
                // Reset text colors for all cards
                const title = this.querySelector('h3');
                const description = this.querySelector('p');
                const linkText = this.querySelector('.text-primary');
                
                if (title) title.style.color = '';
                if (description) description.style.color = '';
                if (linkText) linkText.style.color = '';
            });
        });

  
        const preloadLinks = [
            'views/usuario.php',
            'views/setor.php', 
            'views/permissoes.php'
        ];
        
        preloadLinks.forEach(link => {
            const linkElement = document.createElement('link');
            linkElement.rel = 'prefetch';
            linkElement.href = link;
            document.head.appendChild(linkElement);
        });

      
        function logout() {
            window.location.href = '../../main/views/subsystems.php';
        }
    </script>
</body>

</html>
