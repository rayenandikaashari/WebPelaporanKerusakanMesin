document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('mobileMenuToggle');
    const sidebar = document.getElementById('appSidebar');
    const overlay = document.getElementById('mobileSidebarOverlay');

    if (!toggle || !sidebar || !overlay) {
        return;
    }

    const setMenuOpen = (isOpen) => {
        sidebar.classList.toggle('mobile-open', isOpen);
        overlay.classList.toggle('visible', isOpen);
        document.body.classList.toggle('sidebar-open', isOpen);
        toggle.setAttribute('aria-expanded', String(isOpen));
        toggle.setAttribute('aria-label', isOpen ? 'Tutup menu' : 'Buka menu');
        overlay.setAttribute('aria-hidden', String(!isOpen));
        toggle.textContent = isOpen ? '×' : '☰';
    };

    toggle.addEventListener('click', () => {
        setMenuOpen(!sidebar.classList.contains('mobile-open'));
    });

    overlay.addEventListener('click', () => {
        setMenuOpen(false);
    });

    sidebar.querySelectorAll('nav a').forEach((link) => {
        link.addEventListener('click', () => {
            setMenuOpen(false);
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && sidebar.classList.contains('mobile-open')) {
            setMenuOpen(false);
            toggle.focus();
        }
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 768 && sidebar.classList.contains('mobile-open')) {
            setMenuOpen(false);
        }
    });
});
