document.addEventListener('submit', (event) => {
    const form = event.target;

    if (!(form instanceof HTMLFormElement)) {
        return;
    }

    const message = form.dataset.confirm;

    if (message && !window.confirm(message)) {
        event.preventDefault();
    }
});

document.querySelectorAll('[data-print-now]').forEach((button) => {
    button.addEventListener('click', () => window.print());
});

// Theme toggle functionality
const themeToggle = document.getElementById('theme-toggle');
if (themeToggle) {
    themeToggle.addEventListener('click', () => {
        const htmlElement = document.documentElement;
        const currentTheme = htmlElement.getAttribute('data-theme') || 'light';
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        // Update frontend state
        htmlElement.setAttribute('data-theme', newTheme);
        document.cookie = "theme=" + newTheme + "; path=/; max-age=" + (60 * 60 * 24 * 365);
        
        // Update database if logged in via fetch POST
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            fetch('/theme', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ theme: newTheme })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    console.error('Gagal memperbarui tema di server:', data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });
}
