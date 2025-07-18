// Modern JavaScript for enhanced functionality
// Enable Bootstrap 5 multi-level dropdowns (submenus)
document.addEventListener('DOMContentLoaded', function() {
    // Partner card click logic: always load in iframe, never open in new tab
    const cardUrls = {
        cardMahindra: 'https://example.com/' // For testing, use a site that allows embedding
        // Add other partner cards here as needed
    };
    Object.keys(cardUrls).forEach(function(cardId) {
        const card = document.getElementById(cardId);
        if (card) {
            card.style.cursor = 'pointer';
            // Attach to card
            card.addEventListener('click', function(e) {
                const url = cardUrls[cardId];
                const iframe = document.getElementById('mainContentIframe');
                if (iframe) {
                    iframe.src = url;
                }
            });
            // Attach to image inside card
            const img = card.querySelector('img');
            if (img) {
                img.style.cursor = 'pointer';
                img.addEventListener('click', function(e) {
                    const url = cardUrls[cardId];
                    const iframe = document.getElementById('mainContentIframe');
                    if (iframe) {
                        iframe.src = url;
                    }
                });
            }
        }
    });

    // Home menu logic
    const mainContentIframe = document.getElementById('mainContentIframe');
    const homeMenuItem = document.querySelector('.navbar-nav .nav-link[data-home="true"]');
    if (homeMenuItem && mainContentIframe) {
        homeMenuItem.addEventListener('click', function(e) {
            e.preventDefault();
            mainContentIframe.src = 'home.html';
        });
    }

    // Navbar scroll effect
    const navbar = document.getElementById('mainNav');
    window.addEventListener('scroll', function() {
        if (window.scrollY > 100) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Enable multi-level dropdowns (submenus) with ARIA
    document.querySelectorAll('.dropdown-menu .dropdown-submenu > a').forEach(function(element) {
        element.setAttribute('aria-haspopup', 'true');
        element.setAttribute('aria-expanded', 'false');
        element.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            let submenu = this.nextElementSibling;
            if (submenu && submenu.classList.contains('dropdown-menu')) {
                submenu.classList.toggle('show');
                this.setAttribute('aria-expanded', submenu.classList.contains('show'));
                let parentMenu = this.closest('.dropdown-menu');
                parentMenu.querySelectorAll('.dropdown-menu.show').forEach(function(otherSubmenu) {
                    if (otherSubmenu !== submenu) {
                        otherSubmenu.classList.remove('show');
                    }
                });
            }
        });
    });
    document.querySelectorAll('.dropdown').forEach(function(dropdown) {
        dropdown.addEventListener('hide.bs.dropdown', function() {
            this.querySelectorAll('.dropdown-menu.show').forEach(function(submenu) {
                submenu.classList.remove('show');
            });
        });
    });

    // Keyboard navigation for dropdowns
    document.querySelectorAll('.dropdown-menu a').forEach(function(link) {
        link.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                let next = this.parentElement.nextElementSibling;
                if (next) {
                    let nextLink = next.querySelector('a');
                    if (nextLink) nextLink.focus();
                }
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                let prev = this.parentElement.previousElementSibling;
                if (prev) {
                    let prevLink = prev.querySelector('a');
                    if (prevLink) prevLink.focus();
                }
            }
        });
    });
}); 