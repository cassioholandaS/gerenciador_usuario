(function () {
    const storageKey = 'stgm-theme';

    function applyTheme(theme) {
        const isDark = theme === 'dark';
        document.documentElement.classList.toggle('dark', isDark);

        document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
            button.setAttribute('aria-pressed', String(isDark));
            button.setAttribute('aria-label', isDark ? 'Ativar modo claro' : 'Ativar modo escuro');
            button.setAttribute('title', isDark ? 'Ativar modo claro' : 'Ativar modo escuro');
        });
    }

    let savedTheme = 'light';
    try {
        savedTheme = localStorage.getItem(storageKey) === 'dark' ? 'dark' : 'light';
    } catch (error) {
        console.warn('Não foi possível carregar a preferência de tema:', error);
    }
    applyTheme(savedTheme);

    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const nextTheme = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
            applyTheme(nextTheme);
            try {
                localStorage.setItem(storageKey, nextTheme);
            } catch (error) {
                console.warn('Não foi possível salvar a preferência de tema:', error);
            }
        });
    });

    window.addEventListener('storage', (event) => {
        if (event.key === storageKey) {
            applyTheme(event.newValue === 'dark' ? 'dark' : 'light');
        }
    });
})();
