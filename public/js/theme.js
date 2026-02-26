document.addEventListener("DOMContentLoaded", () => {
    const toggle = document.getElementById("theme-toggle");
    const body = document.body;
    const themePreference = window.matchMedia("(prefers-color-scheme: dark)");

    /**
     * Determina y aplica el tema adecuado basándose en la preferencia guardada
     * en localStorage o, en su defecto, en la configuración del sistema.
     */
    const applyTheme = () => {
        const storedTheme = localStorage.getItem("theme");
        const isDark = storedTheme
            ? storedTheme === "dark"
            : themePreference.matches;

        body.classList.toggle("dark-mode", isDark);
        if (toggle) toggle.checked = isDark;
    };

    applyTheme();

    if (toggle) {
        toggle.addEventListener("change", () => {
            const isDark = toggle.checked;
            body.classList.toggle("dark-mode", isDark);
            localStorage.setItem("theme", isDark ? "dark" : "light");
        });
    }

    themePreference.addEventListener("change", () => {
        // Solo actualiza automáticamente si el usuario no ha establecido una preferencia manual
        if (!localStorage.getItem("theme")) {
            applyTheme();
        }
    });
});