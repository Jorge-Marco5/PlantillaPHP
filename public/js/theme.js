document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById("theme-toggle");
    const body = document.body;

    if (!toggle) return;

    // 1. Cargar preferencia guardada
    const currentTheme = localStorage.getItem("theme");
    if (currentTheme === "dark") {
        body.classList.add("dark-mode");
        toggle.checked = true; // Sincroniza el switch visual
    }

    // 2. Escuchar cambios en el switch
    toggle.addEventListener("change", () => {
        if (toggle.checked) {
            body.classList.add("dark-mode");
            localStorage.setItem("theme", "dark");
        } else {
            body.classList.remove("dark-mode");
            localStorage.setItem("theme", "light");
        }
    });
});
